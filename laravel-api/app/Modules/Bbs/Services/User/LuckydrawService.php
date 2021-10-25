<?php

namespace App\Modules\Bbs\Services\User;

use App\Exceptions\Bbs\FailException;
use App\Models\Dynamic\Dynamic;
use App\Models\Dynamic\DynamicCollection;
use App\Models\Dynamic\DynamicComment;
use App\Models\Dynamic\DynamicPraise;
use App\Models\Luckydraw\Luckydraw;
use App\Models\Luckydraw\LuckydrawConfig;
use App\Models\Luckydraw\LuckydrawLog;
use App\Models\Luckydraw\LuckydrawProduct;
use App\Models\Luckydraw\LuckydrawQualificationSource;
use App\Models\System\Notify;
use App\Models\User\UserInfo;
use App\Services\Service;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LuckydrawService extends Service
{
    /**
     * 抽奖活动流程处理
     * @param array $params
     */
    public function lotteryProcessHandle($user_id, $activity_id, $params = [])
    {
        /**
         * 1.检测会员是否有抽奖的资格
         */
        $user_info = UserInfo::lock(true)->find($user_id);
        if ($user_info->luckydraw_times <= 0){
            $this->setError('您的抽奖资格已不足！');
            return false;
        }

        /**
         * 2.检测当前开启抽奖活动的方案
         *
         * 是否已开启抽奖
         *
         * 活动方法是否已经设置了奖项配置
         */
        $activity = Luckydraw::where(['activity_id' => $activity_id, 'is_open' => 1])->first();
        if (empty($activity)){
            $this->setError('系统尚未开放抽奖活动');
            return false;
        }
        $activity_details = LuckydrawConfig::leftJoin(LuckydrawProduct::getInstance()->getTable(), 'ad.product_id = lp.product_id')
            ->where(
                ['ad.activity_id', '=', $activity['activity_id']],

                ['ad.reward_category', '<>', 0],
                ['ad.probability_of_winning', '>', 0],
                ['ad.awards_num', '>', 0],
            )->orWhere(
                ['ad.reward_category', '=', 0],
                ['ad.activity_id', '=', $activity['activity_id']],
            )
            ->select(['*', 'lp.title', 'lp.images'])
            ->lock(true)
            ->first();
        if (empty($activity_details)){
            $this->setError('活动方案尚未设置奖项配置');
            return false;
        }

        $_activity_details = [];
        //设置抽奖比例与Id标识对应的数组
        foreach ($activity_details as $key => $detail) {
            $_activity_details[$detail['detail_id']] = $detail;//key 转换为 detail_id 方便后续快速查找
            $arr[$detail['detail_id']] = $detail['probability_of_winning'];
        }

        //抽奖活动的奖励类型列表
        $common_luckydraw_activity_reward_category_list = lang('common_luckydraw_activity_reward_category_list');

        //最终抽奖结果为：
        $detail_id = self::checkRandAvailable($arr, $_activity_details);

        //抽奖最终的数据为：
        if (empty($lucky_draw_results = $_activity_details[$detail_id])){
            $this->setError('抽奖失败');
            return false;
        }

        if (!array_key_exists($lucky_draw_results['reward_category'], $common_luckydraw_activity_reward_category_list)){
            $this->setError('后台配置奖励有误');
            return false;
        }

        Db::beginTransaction();
        try {
            $operation_ip = get_ip();
            /**
             * 无奖励的类型，那么无需减去发奖的次数
             *
             * 其他类型，默认减去1次可抽奖的机会
             */
            if (intval($lucky_draw_results['reward_category']) != 0) {
                //剩余可抽奖获取奖励的次数 递减
                LuckydrawConfig::where([
                    'detail_id' => $lucky_draw_results['detail_id'],
                ])->decrement('awards_num');
            }

            // 会员当前可抽奖的机会次数 递减
            $user_info->decrement('luckydraw_times');

            //抽奖获取记录新增
            $luckydrawLog = LuckydrawLog::create([
                'activity_id' => $lucky_draw_results['activity_id'],
                'detail_id' => $lucky_draw_results['detail_id'],
                'user_id' => $user_id,
                'reward_category' => $lucky_draw_results['reward_category'],
                'reward_quota' => $lucky_draw_results['reward_quota'],
                'product_id' => $lucky_draw_results['product_id'],
                'create_ip' => $operation_ip,
                'is_receive' => in_array(intval($lucky_draw_results['reward_category']), [0, 1, 2]) ? 1 : 0,//是否领取【只有 积分与抵扣分 才需要领取】
            ]);
            $log_id = $luckydrawLog->log_id;

            /**
             * 抽奖资格的消耗记录变更
             */
            LuckydrawQualificationSource::create([
                'user_id' => $user_id,
                'source_type' => 2,//抽奖减少资格
                'luckydraw_times' => 1,
                'create_ip' => $operation_ip,
                'extend_info' => json_encode([
                    'log_id' => $log_id,
                ]),
            ]);

            $msg = '抽奖成功，' . '恭喜您获得了‘' . $lucky_draw_results['reward_name'] . '’，奖励：';
            /**
             * 资金奖励，应该默认发放给会员
             */
            switch (intval($lucky_draw_results['reward_category'])) {
                case 0:
                    $msg = '谢谢参与，期望您再接再厉！';
                    break;
                case 1:
                    $msg .= '消费积分[' . $lucky_draw_results['reward_quota'] . ']';

                    $after_integral_money = amount_conversion($user_money['integral_money'] + $lucky_draw_results['reward_quota'], 2);
                    Db::name('UserMoney')->where('user_id', $user_id)->update([
                        'integral_money' => $after_integral_money,
                        'update_time' => $update_time,
                    ]);
                    //5.资金表变动记录
                    IntegralService::setUserIntegralLogAdd(
                        intval($user_id),
                        amount_conversion($user_money['integral_money']),
                        amount_conversion($after_integral_money),
                        '抽奖获得消费积分奖励',
                        1,
                        2);
                    break;
                case 2:
                    $msg .= '抵扣积分[' . $lucky_draw_results['reward_quota'] . ']';

                    $after_reward_quota = amount_conversion($user_money['deduction_point'] + $lucky_draw_results['reward_quota'], 2);
                    Db::name('UserMoney')->where('user_id', $user_id)->update([
                        'deduction_point' => $after_reward_quota,
                        'update_time' => $update_time,
                    ]);
                    //5.资金表变动记录
                    IntegralService::setUserIntegralLogAdd(
                        intval($user_id),
                        amount_conversion($user_money['deduction_point']),
                        amount_conversion($after_reward_quota),
                        '抽奖获得抵扣积分奖励',
                        1,
                        7);
                    break;
                case 3:
                    $msg .= '产品奖励[' . $lucky_draw_results['title'] . ']';
                    break;
            }

            Db::commit();// 提交事务
            $this->setError($msg);
            return ['id' => $detail_id, 'images' => $lucky_draw_results['images'], 'reward_category' => $lucky_draw_results['reward_category']];
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            $this->setError('抽奖失败');
            return false;
        }
    }

    /**
     * 检测抽奖的结果是否有效可用
     * @param $arr
     * @param $activity_details
     * @return int|string
     */
    private static function checkRandAvailable($arr, $activity_details)
    {
        $detail_id = get_random_probability($arr);
        if (empty($activity_details[$detail_id])) return self::checkRandAvailable($arr, $activity_details);
        /**
         * 奖励次数已发完的，排除该奖项，重新计算抽奖
         * 无奖励类型 --- 另算
         */
        if (empty($activity_details[$detail_id]['awards_num']) && intval($activity_details[$detail_id]['reward_category']) != 0 && $activity_details[$detail_id]['probability_of_winning'] == 0){
            return self::checkRandAvailable($arr, $activity_details);
        }
        return $detail_id;
    }
}

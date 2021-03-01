<?php

declare(strict_types = 1);

namespace App\Model\Bbs;

use App\Exception\Exception;
use App\Model\Model;
use App\Model\User\UserInfo;
use Hyperf\DbConnection\Db;

class FriendApply extends Model
{
    protected $primaryKey = 'apply_id';
    public    $is_delete  = 1;// 是否删除：0.假删除；1.真删除【默认全部假删除】

    public function friend()
    {
        return $this->belongsTo(UserInfo::class, 'friend_id', 'user_id');
    }

    /**
     * 指定人员的好友申请列表
     *
     * @param  int  $user_id
     * @param  int  $limit
     *
     * @return array
     */
    public function getApplyList(int $user_id, int $limit = 10) : array
    {
        $list = $this->cnpscyWhere('user_id', $user_id)
                     ->with(['friend.avatar'])
                     ->orderBy('created_time', 'DESC')
                     ->paginate($limit);
        // 设置分页数据格式
        return $this->setPaginate($list);
    }

    /**
     * 最新一条申请记录
     *
     * @param  int  $friend_id
     * @param  int  $user_id
     *
     * @return mixed
     */
    public function getLastApply(int $friend_id, int $user_id)
    {
        return $this->cnpscyWhere(['friend_id' => $friend_id, 'user_id' => $user_id])
                    ->orderBy('apply_id', 'DESC')
                    ->lock(true)
                    ->first();
    }

    /**
     * 好友申请流程
     *
     * @param  int     $user_id
     * @param  int     $friend_id
     * @param  string  $apply_remark
     *
     * @return bool
     */
    public function apply(int $user_id, int $friend_id, string $apply_remark = '') : bool
    {
        // 当前是否已在申请中
        if ( $detail = $this->getLastApply($user_id, $friend_id) ) {
            switch ($detail->is_check) {
                case 0:
                    $this->setError('您已申请该会员成为好友，请耐心等待');
                    return false;
                    break;
                case 1:
                    $this->setError('您与该会员已成为好友');
                    break;
                case 2:
                    break;
            }
        }

        // 录入申请信息
        if ( $res = $this->record($user_id, $friend_id, $apply_remark) ) {
            $this->setError('申请好友成功，请等待对方回应');
            return true;
        } else {
            $this->setError('好友申请失败');
            return false;
        }
    }

    /**
     * 录入申请记录
     *
     * @param  int     $friend_id
     * @param  int     $user_id
     * @param  string  $apply_remark
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model
     */
    public function record(int $friend_id, int $user_id, string $apply_remark = '')
    {
        return $this->add(['user_id' => $user_id, // 接受者
                              'friend_id' => $friend_id, // 申请人
                              'apply_remark' => filtering_html_tags($apply_remark)]);
    }

    /**
     * 好友申请信息：审核流程
     *
     * @param          $apply
     * @param  int     $is_check
     * @param  string  $user_reason
     *
     * @return bool
     */
    public function respond($apply, int $is_check = 1, string $user_reason = '') : bool
    {
        $user_reason = filtering_html_tags($user_reason);
        Db::beginTransaction();
        try {
            $this->whereUpdate(['apply_id' => $apply->apply_id], ['is_check' => $is_check, 'user_reason' => $user_reason,]);
            switch ($is_check) {
                case 1: // 同意
                    if ( !BbsFriend::agreeAddFriend($apply->user_id, $apply->friend_id) ) {
                        throw new Exception('好友关系录入失败');
                    }
                    $this->setError('添加好友成功');
                    break;
                case 2: // 拒绝
                    $this->setError('已拒绝添加好友');
                    break;
            }
            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollBack();
            $this->setError(empty($e->getMessage()) ? '回应失败' : $e->getMessage());
            return false;
        }
    }
}
<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni @down="downCallback" @up="upCallback">
			<block v-for="(item, index) in topList" :key="index">
				<view class="plr18r ptb18r bgwhite mb18r">
					<view class="flex plr18r ptb18r bbs2r">
						<user-avatar @click="fnUserInfo(item.sender)" :src="item.sender.user_avatar" tag="" size="md"></user-avatar>
						<view class="flexc-jsa ml28r flex-gitem">
							<view>
								<text class="f28r fbold mr18r">{{ item.sender.nick_name }}</text>
								<i-icon v-if="[0, 1].indexOf(item.sender.user_sex) > -1" :type="item.sender.user_sex_text == '男' ? 'nan':'nv' " size="28"
								 :color="item.sender.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
							</view>
							<view class="f24r cgray">{{ calDateTime(item.created_time) }}</view>
						</view>
						<view class="f28r cgray flex-asc">{{ item.explain }}</view>
					</view>
					<!-- 动态 -->
					<view class="flex flex-ais br4r" @tap="fnOpenInfo(item)">
						<image class="hw128r br4r" v-if="item.relation.dynamic_images" :src="item.relation.dynamic_images[0]" mode="aspectFill"></image>
						<view class="flex-fitem f28r ptb8r plr18r bgf8 c555 flex flex-aic">{{ item.relation.dynamic_title }}</view>
					</view>
				</view>
			</block>
		</mescroll-uni>
	</view>
</template>

<script>
import { fnFormatLocalDate, getYearMonth } from '@/utils/CommonUtil.js';
import { getPraiseByNotify } from '@/api/MessageServer.js';
import {dynamicDetailPage} from '@/utils/common.js'

export default {
	data() {
		return {
			// 点赞数据列表
			topList: [],
			search_month:'',
		};
	},
	onLoad() {
		this.search_month = getYearMonth();
	},
	methods: {
		/// 下拉刷新的回调
		downCallback(mescroll) {
			// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
			mescroll.resetUpScroll();
		},
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			let search_month = this.search_month;
			getPraiseByNotify({
				search_month: search_month,
				page: mescroll.num,
				limit: mescroll.size
			})
				.then(res => {
					let lists = res.data;
					this.topList = this.topList.concat(lists.data);
					
					// 更新未读消息数量
					if(lists.set_read_nums){
						let newsCount = this.$store.getters['getNewsCountData'];
						newsCount.praise_unreads = newsCount.praise_unreads - lists.set_read_nums;
						this.$store.commit('setNewsCountData', newsCount);
					}

					/**
					 * 如果当前月份记录查询完成，那么继续查询上一个月份的
					 */
					this.search_month = lists.month_table;
					// 如果月份不一致，那么page需要重置
					if(search_month != this.search_month){
						mescroll.setPageNum(1);
					}

					if (lists.data.length <= 0 && search_month == this.search_month) {
						// 数据加载完毕
						mescroll.endSuccess(0, false);
					} else {
						mescroll.endSuccess(lists.per_page, true);
					}
				})
				.catch(() => {
					mescroll.endErr();
				});
		},
		/// 计算时间格式 下午 08:12 | 昨日 09:12 | 2019-12-03 20:12
		calDateTime(str) {
			return fnFormatLocalDate(str * 1000);
		},
		/// 跳转用户信息页
		fnUserInfo(e) {
			uni.navigateTo({
				url: `/pages/user-info/user-info?user_id=${e.user_id}`
			});
		},
		/// 跳转详情页
		fnOpenInfo(e) {
			dynamicDetailPage(e.relation, this, 'comment');
		}
	}
};
</script>

<style></style>

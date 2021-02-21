<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni @down="downCallback" @up="upCallback">
			<block v-for="(top, index) in topList" :key="index">
				<view class="plr18r ptb18r bgwhite mb18r">
					<view class="flex plr18r ptb18r bbs2r">
						<user-avatar @click="fnUserInfo(top.sender)" :src="top.sender.user_avatar" tag="" size="md"></user-avatar>
						<view class="flexc-jsa ml28r flex-gitem">
							<view>
								<text class="f28r fbold mr18r">{{ top.sender.nick_name }}</text>
								<i-icon v-if="[0, 1].indexOf(top.sender.user_sex) > -1" :type="top.sender.user_sex_text == '男' ? 'nan':'nv' " size="28"
								 :color="top.sender.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
							</view>
							<view class="f24r cgray">{{ calDateTime(top.created_time) }}</view>
						</view>
						<view class="f28r cgray flex-asc">{{ top.explain }}</view>
					</view>
					<!-- 动态 -->
					<view class="flex flex-ais br4r" @tap="fnOpenInfo(top)">
						<image class="hw128r br4r" v-if="top.relation.dynamic_images" :src="top.relation.dynamic_images[0]" mode="aspectFill"></image>
						<view class="flex-fitem f28r ptb8r plr18r bgf8 c555 flex flex-aic">{{ top.relation.dynamic_title }}</view>
					</view>
				</view>
			</block>
		</mescroll-uni>
	</view>
</template>

<script>
import { fnFormatLocalDate, getYearMonth } from '@/utils/CommonUtil.js';
import { getPraiseByNotify } from '@/api/MessageServer.js';

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
			console.log(mescroll)
			getPraiseByNotify({
				search_month: search_month,
				page: mescroll.num,
				limit: mescroll.size
			})
				.then(res => {
					let lists = res.data;
					
					this.topList = this.topList.concat(lists.data);

					/**
					 * 如果当前月份记录查询完成，那么继续查询上一个月份的
					 */
					this.search_month = lists.month_table;
					// 如果月份不一致，那么page需要重置
					if(search_month != this.search_month){
						mescroll.setPageNum(1);
					}
					console.log(mescroll)

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
				url: `/pages/user-info/user-info?user_id=${e.ID}`
			});
		},
		/// 跳转详情页
		fnOpenInfo(e) {
			console.log(e.ObjectType);
			if (e.ObjectType == 'trend') {
				uni.navigateTo({
					url: `/pages/trend-details/trend-details?dynamic_id=${e.dynamic_id}&fromPage=comment`
				});
				return;
			}
			if (e.ObjectType == 'album') {
				uni.navigateTo({
					url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=comment`
				});
				return;
			}
			if (e.ObjectType == 'topic') {
				uni.navigateTo({
					url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=comment`
				});
				return;
			}
			if (e.ObjectType == 'topicreply') {
				uni.navigateTo({
					url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=comment`
				});
				return;
			}
			if (e.ObjectType == 'video') {
				uni.navigateTo({
					url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=comment`
				});
				return;
			}
		}
	}
};
</script>

<style></style>

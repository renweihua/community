<template>
	<view>
		<mescroll-uni :top="0" @down="downCallback" @up="upCallback" @init="mescrollInit">
			<block v-for="(infoData, index) in userSaveAllListData" :key="index">
				<trend-card
					:item="infoData.dynamic"
					@click="fnCardInfo"
					@user="fnCardUser"
					@huiba="fnCardHuiba"
					@top="fnCardTop"
					@comm="fnCardComm"
					@save="fnCardSave"
					@follow="fnCardFollow"
					@black="fnCardBlack"
					@report="fnCardReport"
				></trend-card>
			</block>
		</mescroll-uni>
	</view>
</template>

<script>
import { dynamicPraise, getCollectionList, dynamicCollection } from '@/api/InteractServer.js';
import { followUser, addUserBlack, delUserBlack } from '@/api/UserServer.js';
import { dynamicDetailPage } from '@/utils/common.js';

// 动态信息项卡片组件
import TrendCard from '@/components/trend-card/trend-card';

export default {
	components: {
		TrendCard
	},
	data() {
		return {
			// 导航项滑动初始id
			scrollInto: 'all',
			// 顶部导航滑动页选中
			current: 0,
			// 双击刷新
			clickRefresh: false,
			// 刷新间隔
			timeOutCollection: 0,
			// 刷新组件实例
			mescroll: {
				all: null
			}
		};
	},
	computed: {
		// 全部
		userSaveAllListData() {
			return this.$store.getters['interact/getUserSaveAllListData'];
		}
	},
	onLoad(options) {},
	methods: {
		/// mescroll组件初始化的回调,可获取到mescroll对象
		mescrollInit(mescroll) {
			this.mescroll[this.scrollInto] = mescroll;
		},
		/// 下拉刷新的回调
		downCallback(mescroll) {
			// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
			this.mescroll[this.scrollInto].resetUpScroll();
		},
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			getCollectionList({
				page: mescroll.num,
				limit: mescroll.size
			})
				.then(res => {
					let lists = res.data;

					if (mescroll.num == 1) {
						this.$store.commit('interact/setUserSaveAllListData', lists.data);
					} else {
						this.$store.commit('interact/setUserSaveAllListData', this.userSaveAllListData.concat(lists.data));
					}

					mescroll.endSuccess(lists.data.length, mescroll.num < res.data.count_page);
				})
				.catch(() => {
					mescroll.endErr();
				});
		},
		// 展卡跳转详情页
		fnCardInfo(e) {
			dynamicDetailPage(e, this, 'collection');
		},
		/// 展卡评论跳转详情页
		fnCardComm(e) {
			console.log(e.ObjectType);
			if (e.ObjectType == 'trend') {
				uni.navigateTo({
					url: `/pages/trend-details/trend-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
				});
				return;
			}
			if (e.ObjectType == 'album') {
				uni.navigateTo({
					url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
				});
				return;
			}
			if (e.ObjectType == 'topic') {
				uni.navigateTo({
					url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
				});
				return;
			}
			if (e.ObjectType == 'topicreply') {
				uni.navigateTo({
					url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
				});
				return;
			}
			if (e.ObjectType == 'video') {
				uni.navigateTo({
					url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
				});
				return;
			}
		},
		/// 展卡跳转用户中心页
		fnCardUser(e) {
			uni.navigateTo({
				url: `/pages/user-info/user-info?user_id=${e.user_info.user_id}`
			});
		},
		/// 展卡跳转荟吧页
		fnCardHuiba(e) {
			uni.navigateTo({
				url: `/pages/huiba-details/huiba-details?topic_id=${e.topic_id}`
			});
		},
		/// 展卡点赞
		fnCardTop(e) {
			let filItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			dynamicPraise(filItem.dynamic.dynamic_id).then(res => {
				uni.showToast({
					title: res.msg,
					icon: res.status == 1 ? 'success' : 'none'
				});
				if (!res.status) return;
				// 用户是否点过赞
				if (filItem.dynamic.is_praise) {
					filItem.dynamic.praise_count--;
					filItem.dynamic.is_praise = false;
				} else {
					filItem.dynamic.praise_count++;
					filItem.dynamic.is_praise = true;
				}
			});
		},
		// 展卡收藏
		fnCardSave(e) {
			let filItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			dynamicCollection(filItem.dynamic.dynamic_id).then(res => {
				uni.showToast({
					title: res.msg,
					icon: res.status == 1 ? 'success' : 'none'
				});
				if (!res.status) return;

				// 用户是否已收藏
				if (filItem.dynamic.is_collection) {
					filItem.dynamic.collection_count--;
					filItem.dynamic.is_collection = false;
				} else {
					filItem.dynamic.collection_count++;
					filItem.dynamic.is_collection = true;
				}
			});
		},
		/// 展卡更多-关注
		fnCardFollow(e) {
			// 用户被关注
			if (e.user_info.is_follow) {
				followUser(e.user_info.user_id).then(res => {
					if (!res.status) return;
					this.userSaveAllListData.filter(item => item.user_info.user_id == e.user_info.user_id).map(item => (item.user_info.is_follow = false));
					// 登录用户关注数减
					let tempUser = this.$store.getters['user/getLoginUserInfoData'];
					tempUser.user_info.follows_count--;
					this.$store.commit('user/setLoginUserInfoData', tempUser);
				});
			} else {
				followUser(e.user_info.user_id).then(res => {
					if (!res.status) return;
					this.userSaveAllListData.filter(item => item.user_info.user_id == e.user_info.user_id).map(item => (item.user_info.is_follow = true));
					// 登录用户关注数加
					let tempUser = this.$store.getters['user/getLoginUserInfoData'];
					tempUser.user_info.follows_count++;
					this.$store.commit('user/setLoginUserInfoData', tempUser);
				});
			}
		},
		// 展卡更多-拉黑
		fnCardBlack(e) {
			// 用户是否被列入黑名单
			e.User.Black ? delUserBlack(e.user_info.user_id) : addUserBlack(e.user_info.user_id);
		},
		// 展卡更多-跳转举报页
		fnCardReport(e) {
			uni.navigateTo({
				url: `/pages/report/report?id=${e.dynamic_id}&type=${e.ObjectType}`
			});
		}
	}
};
</script>

<style></style>

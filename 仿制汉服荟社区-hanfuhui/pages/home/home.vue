<template>
	<view>
		<!-- 主页顶部导航栏 -->
		<view class="posif posi-tlr0 z500 bgtheme">
			<!-- #ifdef APP-PLUS -->
			<view class="status-bar"></view>
			<!-- #endif -->
			<view class="flex plr18r">
				<view class="w128r hl90r f32r fcenter cwhite-a6" :class="{ 'barsh-home': current == 0 }" @tap="fnBarClick(0)">推荐</view>
				<view class="w128r hl90r f32r fcenter cwhite-a6" :class="{ 'barsh-home': current == 1 }" @tap="fnBarClick(1)">关注</view>
			</view>
		</view>

		<!-- 滑动切换视图 -->
		<swiper class="posia posi-all0" :current="current" @change="fnBarClick">
			<!-- 推荐 -->
			<swiper-item>
				<mescroll-uni v-if="status.recommend" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(item, index) in mainData" :key="index">
						<trend-card
							:item="item"
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
			</swiper-item>

			<!-- 关注 -->
			<swiper-item>
				<mescroll-uni v-if="status.follow" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(infoData, index) in atteData" :key="index">
						<trend-card
							:item="infoData"
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
			</swiper-item>
		</swiper>
	</view>
</template>

<script>
import { dynamicPraise, dynamicCollection } from '@/api/InteractServer.js';
import { followUser, addUserBlack, delUserBlack } from '@/api/UserServer.js';
import { getDiscoverList, getFollowDynamics } from '@/api/home.js';

import { dynamicDetailPage } from '@/utils/common.js';

// 动态信息项卡片组件
import TrendCard from '@/components/trend-card/trend-card';

export default {
	components: {
		TrendCard
	},
	props: {
		// 底部导航双击连续触发刷新
		refresh: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return {
			// 顶部导航选中
			current: 0,
			// 激活顶部导航关联页状态
			status: {
				recommend: true,
				follow: false
			},
			// 双击刷新
			clickRefresh: false,
			// 刷新间隔
			timeOutHome: 0,
			// 列表最大ID定位
			maxID: [-1, -1, -1],
			// 刷新组件实例
			mescroll: []
		};
	},
	watch: {
		// 监听底部导航双击触发
		refresh(val) {
			if (val && !this.clickRefresh) this.fnRefreshData();
		}
	},
	computed: {
		// 推荐列表数据
		mainData() {
			return this.$store.getters['trend/getMainData'];
		},
		// 关注列表数据
		atteData() {
			return this.$store.getters['trend/getAtteData'];
		},
	},
	methods: {
		/// mescroll组件初始化的回调,可获取到mescroll对象
		mescrollInit(mescroll) {
			this.mescroll[this.current] = mescroll;
		},
		/// 下拉刷新的回调
		downCallback(mescroll) {
			this.maxID[this.current] = -1;
			// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
			mescroll.resetUpScroll();
		},
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			// 联网获取数据
			[getDiscoverList, getFollowDynamics]
				[this.current]({
					page: mescroll.num,
					last_id: this.maxID[this.current],
					limit: mescroll.size
				})
				.then(res => {
					let lists = res.data;

					// 固定项数据往后加载
					if (mescroll.num == 1) this.maxID[this.current] = lists.data[0].dynamic_id;
					// 不同标签存入不同数据变量
					if (this.current == 0) {
						let arrayData = mescroll.num == 1 ? lists.data : this.mainData.concat(lists.data);
						this.$store.commit('trend/setMainData', arrayData);
					}
					if (this.current == 1) {
						let arrayData = mescroll.num == 1 ? lists.data : this.atteData.concat(lists.data);
						this.$store.commit('trend/setAtteData', arrayData);
					}
					
					// 数据获取成功关闭loading区
					mescroll.endSuccess(lists.data.length, mescroll.num < lists.count_page);
				})
				.catch(() => {
					// mescroll.endErr();
					mescroll.endSuccess(0, false);
				});
		},
		// 导航选项双击刷新
		fnRefreshData() {
			this.maxID[this.current] = -1;
			this.mescroll[this.current].scrollTo(0, 300);
			setTimeout(() => {
				this.mescroll[this.current].resetUpScroll(true);
			}, 1000);
		},
		// 顶部导航选项点击
		fnBarClick(e) {			
			let current = typeof e == 'object' ? e.detail.current : e;
			// 是否当前项点击
			if (this.current == current) {
				this.timeOutHome += 1;
				// 是否为刷新值和连续触发
				if (!this.clickRefresh && this.timeOutHome >= 2) {
					// 刷新值开
					this.clickRefresh = true;
					// 获取新数据
					this.fnRefreshData();
					// 定时器重置
					this.timeOutHome = setTimeout(() => {
						// 清除定时器
						clearTimeout(this.timeOutHome);
						// 连续触发记录重置
						this.timeOutHome = 0;
						// 刷新值关
						this.clickRefresh = false;
					}, 5000);
				}
				return;
			} else {
				// 改变顶部导航选中
				this.current = current;
				// 首次选中激活顶部导航关联页状态
				if (!this.status.recommend && current == 0) this.status.recommend = true;
				if (!this.status.follow && current == 1) this.status.follow = true;
				// 清除定时器
				clearTimeout(this.timeOutHome);
				// 连续触发记录重置
				this.timeOutHome = 0;
				// 刷新值关
				this.clickRefresh = false;
			}
		},
		// 展卡跳转详情页
		fnCardInfo(e) {
			dynamicDetailPage(e, this);
			return;
			e.ObjectType = 'trend';
			if (e.dynamic_type == 'trend') {
				uni.navigateTo({
					url: `/pages/trend-details/trend-details?dynamic_id=${e.dynamic_id}&fromPage=home&current=${this.current}`
				});
				return;
			}
			if (e.ObjectType == 'album') {
				uni.navigateTo({
					url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=home&current=${this.current}`
				});
				return;
			}
			if (e.ObjectType == 'topic') {
				uni.navigateTo({
					url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=home&current=${this.current}`
				});
				return;
			}
			if (e.ObjectType == 'topicreply') {
				uni.navigateTo({
					url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=home&current=${this.current}`
				});
				return;
			}
			if (e.ObjectType == 'video') {
				uni.navigateTo({
					url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=home&current=${this.current}`
				});
				return;
			}
			if (e.ObjectType == 'word') {
				uni.navigateTo({
					url: `/pages/word-details/word-details?id=${e.dynamic_id}&fromPage=home&current=${this.current}`
				});
				return;
			}
		},
		/// 展卡评论跳转详情页
		fnCardComm(e) {
			dynamicDetailPage(e, this, 'home', '&comm=true');
		},
		// 展卡跳转用户中心页
		fnCardUser(e) {
			uni.navigateTo({
				url: `/pages/user-info/user-info?user_id=${e.user_id}`
			});
		},
		// 展卡跳转荟吧页
		fnCardHuiba(e) {
			uni.navigateTo({
				url: `/pages/huiba-details/huiba-details?topic_id=${e.topic_id}`
			});
		},
		// 展卡点赞
		fnCardTop(e) {
			let filItem = {};
			// 推荐
			if (this.current == 0) filItem = this.mainData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			// 关注
			if (this.current == 1) filItem = this.atteData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			// 点赞动态
			dynamicPraise(filItem.dynamic_id).then(res => {
				uni.showToast({
					title: res.msg,
					icon: res.status == 1 ? 'success' : 'none'
				});
				if (!res.status) return;

				// 用户是否点过赞
				if (filItem.is_praise) {
					filItem.cache_extends.praises_count--;
					filItem.is_praise = false;
				} else {
					filItem.cache_extends.praises_count++;
					filItem.is_praise = true;
				}
			});
		},
		/// 展卡收藏
		fnCardSave(e) {
			let filItem = {};
			// 推荐
			if (this.current == 0) filItem = this.mainData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			// 关注
			if (this.current == 1) filItem = this.atteData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			dynamicCollection(filItem.dynamic_id).then(res => {
				uni.showToast({
					title: res.msg,
					icon: res.status == 1 ? 'success' : 'none'
				});
				if (!res.status) return;
				// 用户是否已收藏
				if (filItem.is_collection) {
					filItem.cache_extends.collection_count--;
					filItem.is_collection = false;
				} else {
					filItem.cache_extends.collection_count++;
					filItem.is_collection = true;
				}
			});
		},
		// 展卡更多-关注
		fnCardFollow(e) {
			console.log(e.user_id);
			let login_user = this.$store.getters['user/getLoginUserInfoData'];
			// 用户被关注
			if (e.user_info.is_follow) {
				uni.showModal({
					content: '确定要取消关注TA吗？',
					success: res => {
						if (res.confirm) {
							followUser(e.user_id).then(follow => {
								uni.showToast({
									title: follow.msg,
									icon: 'none'
								});
								if (!follow.status) return;
								this.atteData.filter(item => item.user_id == e.user_id).map(item => (item.user_info.is_follow = false));
								this.mainData.filter(item => item.user_id == e.user_id).map(item => (item.user_info.is_follow = false));
								// 登录用户关注数减
								if(!login_user.user_info) return;
								login_user.user_info.follows_count--;
								this.$store.commit('user/setLoginUserInfoData', login_user);
							});
						}
					}
				});
			} else {
				followUser(e.user_id).then(follow => {
					uni.showToast({
						title: follow.msg,
						icon: follow.status == 1 ? 'success' : 'none'
					});
					if (!follow.status) return;
					this.atteData.filter(item => item.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
					this.mainData.filter(item => item.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
					// 登录用户关注数加
					if(!login_user.user_info) return;
					login_user.user_info.follows_count++;
					this.$store.commit('user/setLoginUserInfoData', login_user);
				});
			}
		},
		// 展卡更多-拉黑
		fnCardBlack(e) {
			// 用户是否被列入黑名单
			e.user_info.is_blck ? delUserBlack(e.user_info.user_id) : addUserBlack(e.user_info.user_id);
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

<style>
/* 主页导航选中高亮 */
.barsh-home {
	color: #ffffff;
	font-size: 36rpx;
	font-weight: bold;
}
</style>

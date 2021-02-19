<template>
	<view>
		<!-- 滚动列表内容 -->
		<mescroll-uni :down="{ use: false }" :up="{ onScroll: true }" @up="upCallback" @scroll="scroll" @init="mescrollInit">
			<!-- 荟吧背景封面 -->
			<view class="posir" :class="{ mb18r: !huibaTopData }">
				<image class="huiba-cover" :src="calHuibaFacePic" mode="aspectFill"></image>
				<view class="posia posi-blr0">
					<view class="flex plr28r ptb18r">
						<image class="hw128r br8r" style="overflow: hidden;" :src="calHuibaIcon" mode="aspectFill"></image>
						<view class="flex-fitem flexc-jsa plr18r">
							<view class="f32r cwhite">{{ huibaInfoData.topic_name }}</view>
							<view class="f24r cwhite">
								{{ huibaInfoData.follow_count || 0 }} 人关注
								<text class="mlr8r">|</text>
								{{ huibaInfoData.dynamic_count || 0 }} 条动态
							</view>
						</view>
						<view class="bgwhite f28r fcenter w128r br8r ptb8r flex-asc" :class="[huibaInfoData.is_follow ? 'cgray' : 'ctheme']" @tap="fnHuibaFollow">
							{{ huibaInfoData.is_follow ? '已关注' : '关注' }}
						</view>
					</view>
					<view class="plr28r ptb18r bgblack-a3 f28r cwhite">{{ huibaInfoData.Describe }}</view>
				</view>
			</view>
			<!-- 置顶公告 -->
			<view class="plr28r bgwhite mb18r" v-if="huibaTopData">
				<block v-for="top in huibaTopData" :key="top.ID">
					<view class="flex flex-aic bbs2r">
						<view class="f24r bgtheme cwhite ptb8r plr18r br4r mr18r">置顶</view>
						<view class="f28r c111 ellipsis hl80r flex-fitem">{{ top.Title }}</view>
					</view>
				</block>
			</view>

			<!-- 选择导航 -->
			<view v-if="isFixed" class="hl90r bgwhite"></view>
			<view id="tabbar" class="flexr-jsa flex-ais hl90r bgwhite bbs2r" :class="{ 'tabbar-fixed': isFixed }">
				<view class="f32r fbold fcenter w128r tabbar" :class="{ tabbarsh: current == 0 }" @tap="fnBarClick(0)">热门</view>
				<view class="f32r fbold fcenter w128r tabbar" :class="{ tabbarsh: current == 1 }" @tap="fnBarClick(1)">最新</view>
			</view>
			<!-- 最热 -->
			<view v-if="status.hottest" :style="{ display: current == 0 ? 'block' : 'none' }">
				<block v-for="(infoData, index) in huibaHottestListData" :key="index">
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
			</view>
			<!-- 最新 -->
			<view v-if="status.latest" :style="{ display: current == 1 ? 'block' : 'none' }">
				<block v-for="(infoData, index) in huibaLatestListData" :key="index">
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
			</view>
		</mescroll-uni>
	</view>
</template>

<script>
import { getHuibaInfo, getHuibaTop, getHuibaTrend, addHuibaFollows, delHuibaFollows } from '@/api/HuibaServer.js';
import { followUser, addUserBlack } from '@/api/UserServer.js';
import { dynamicPraise, dynamicCollection } from '@/api/InteractServer.js';
	import{
		dynamicDetailPage
	} from "@/utils/common.js";

// 动态信息项卡片组件
import TrendCard from '@/components/trend-card/trend-card';

export default {
	components: {
		TrendCard
	},
	data() {
		return {
			// 选中 最热
			current: 0,
			// 激活顶部导航关联页状态
			status: {
				hottest: true,
				latest: false
			},
			// 滚动动实例
			mescroll: null,
			// 荟吧id
			topic_id: 0,
			maxID: [-1, -1],
			// 双击刷新
			clickRefresh: false,
			// 刷新间隔
			timeOutHuiba: 0,
			// 导航距离顶部
			tabbarTop: 0,
			// 是否固定导航
			isFixed: false,
			// 距离顶部达到导航距离
			topNum: 0
			//
		};
	},

	onLoad(option) {
		if (option && option.topic_id) {
			uni.showLoading({
				title: '加载中',
				mask: true
			});
			this.topic_id = parseInt(option.topic_id);
			// 获取荟吧信息和置顶信息
			Promise.all([
				getHuibaInfo(this.topic_id)
				// getHuibaTop(this.topic_id)
			]).then(resArray => {
				this.$store.commit('huiba/setHuibaInfoData', resArray[0].data);
				// this.$store.commit('huiba/setHuibaTopData', resArray[1].data.Data)
				// 导航标题
				uni.setNavigationBarTitle({
					title: resArray[0].data.topic_name
				});
			});
			// 等待一秒页面渲染,$nextTick使用不能准确
			setTimeout(() => {
				uni.hideLoading();
				// 获取导航条距顶部高度
				this.setTabbarTop();
			}, 1500);
		}
	},

	computed: {
		// 荟吧信息
		huibaInfoData() {
			return this.$store.getters['huiba/getHuibaInfoData'];
		},
		// 荟吧置顶公告信息
		huibaTopData() {
			return this.$store.getters['huiba/getHuibaTopData'];
		},
		// 荟吧最新信息
		huibaLatestListData() {
			return this.$store.getters['huiba/getHuibaLatestListData'];
		},
		// 荟吧最热信息
		huibaHottestListData() {
			let dynamics = this.$store.getters['huiba/getHuibaHottestListData'];
			console.log(dynamics);
			return dynamics;
		},
		/// 计算荟吧icon图
		calHuibaIcon() {
			let topic_cover = this.huibaInfoData.topic_cover;
			return !!topic_cover ? topic_cover : '/static/default_avatar.png';
		},
		/// 计算荟吧背景封面
		calHuibaFacePic() {
			let topic_cover = this.huibaInfoData.topic_cover;
			return !!topic_cover ? topic_cover : '/static/default_image.png';
		}
	},

	methods: {
		/// mescroll组件初始化的回调,可获取到mescroll对象
		mescrollInit(mescroll) {
			this.mescroll = mescroll;
		},
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			let params = {
				topic_id: this.topic_id,
				ishot: this.current == 0,
				// max_id: this.maxID[this.current],
				page: mescroll.num,
				limit: mescroll.size
			};
			getHuibaTrend(params)
				.then(res => {
					let lists = res.data;
					// 最热
					if (this.current == 0) {
						if (mescroll.num == 1) {
							this.$store.commit('huiba/setHuibaHottestListData', lists.data);
						} else {
							this.$store.commit('huiba/setHuibaHottestListData', this.huibaHottestListData.concat(lists.data));
						}
					}
					// 最新
					if (this.current == 1) {
						if (mescroll.num == 1) {
							this.$store.commit('huiba/setHuibaLatestListData', lists.data);
						} else {
							this.$store.commit('huiba/setHuibaLatestListData', this.huibaLatestListData.concat(lists.data));
						}
					}
					// this.maxID[this.current] = lists.data[0].dynamic_id

					mescroll.endSuccess(lists.data.length, mescroll.num < res.data.count_page);
				})
				.catch(() => {
					mescroll.endErr();
				});
		},
		/// 滚动事件 (需在up配置onScroll:true才生效)
		scroll(mescroll) {
			this.topNum = mescroll.getScrollTop();
			if (mescroll.getScrollTop() >= this.tabbarTop) {
				this.isFixed = true; // 显示悬浮菜单
			} else {
				this.isFixed = false; // 隐藏悬浮菜单
			}
		},
		/// 设置nav到顶部的距离 (滚动条为0, 菜单顶部的数据加载完毕获取到的数值是最精确的)
		setTabbarTop() {
			let view = uni
				.createSelectorQuery()
				.in(this)
				.select('#tabbar');
			view.boundingClientRect(data => {
				// 到屏幕顶部的距离
				this.tabbarTop = data.top;
			}).exec();
		},

		/// 顶部导航选项点击
		fnBarClick(current) {
			// 是否当前项点击
			if (this.current == current) {
				this.timeOutHuiba += 1;
				// 是否为刷新值和连续触发
				if (!this.clickRefresh && this.timeOutHuiba >= 2) {
					// 刷新值开
					this.clickRefresh = true;
					// 获取新数据
					this.mescroll.resetUpScroll();
					// 定时器重置
					this.timeOutHuiba = setTimeout(() => {
						// 清除定时器
						clearTimeout(this.timeOutHuiba);
						// 连续触发记录重置
						this.timeOutHuiba = 0;
						// 刷新值关
						this.clickRefresh = false;
					}, 5000);
				}
			} else {
				// 改变顶部导航选中
				this.current = current;
				// 首次选中激活顶部导航关联页状态
				if (!this.status.latest && this.current == 1) this.status.latest = true;
				// 获取新数据
				this.mescroll.resetUpScroll();
				// 清除定时器
				clearTimeout(this.timeOutHuiba);
				// 连续触发记录重置
				this.timeOutHuiba = 0;
				// 刷新值关
				this.clickRefresh = false;
			}
			// 滚动上滑
			this.mescroll.scrollTo(this.tabbarTop, 300);
		},
		/// 荟吧用户关注
		fnHuibaFollow() {
			// 荟吧被关注
			if (this.huibaInfoData.is_follow) {
				uni.showModal({
					content: '确定要取消关注吗？',
					success: res => {
						if (res.confirm) {
							addHuibaFollows(this.huibaInfoData.topic_id).then(result => {
								if (!result.status) return;
								uni.showToast({
									title: result.msg,
									icon: 'none'
								});
								this.huibaInfoData.follow_count--;
								this.huibaInfoData.is_follow = false;
							});
						}
					}
				});
				return;
			} else {
				addHuibaFollows(this.huibaInfoData.topic_id).then(result => {
					if (!result.status) return;
					uni.showToast({
						title: result.msg,
						icon: 'success'
					});
					this.huibaInfoData.follow_count++;
					this.huibaInfoData.is_follow = true;
				});
			}
		},
		/// 展卡跳转详情页
		fnCardInfo(e) {
			dynamicDetailPage(e, this);
		},
		/// 展卡评论跳转详情页
		fnCardComm(e) {
			console.log(e.ObjectType);
			if (e.ObjectType == 'trend') {
				uni.navigateTo({
					url: `/pages/trend-details/trend-details?id=${e.dynamic_id}&fromPage=huiba&current=${this.current}&comm=true`
				});
				return;
			}
			if (e.ObjectType == 'album') {
				uni.navigateTo({
					url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=huiba&current=${this.current}&comm=true`
				});
				return;
			}
			if (e.ObjectType == 'topic') {
				uni.navigateTo({
					url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=huiba&current=${this.current}&comm=true`
				});
				return;
			}
			if (e.ObjectType == 'topicreply') {
				uni.navigateTo({
					url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=huiba&current=${this.current}&comm=true`
				});
				return;
			}
			if (e.ObjectType == 'video') {
				uni.navigateTo({
					url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=huiba&current=${this.current}&comm=true`
				});
				return;
			}
		},
		/// 展卡跳转用户中心页
		fnCardUser(e) {
			uni.navigateTo({
				url: `/pages/user-info/user-info?id=${e.User.ID}`
			});
		},
		/// 展卡跳转荟吧页
		fnCardHuiba(e) {
			if (e.ID == this.id) return;
			uni.navigateTo({
				url: `/pages/huiba-details/huiba-details?id=${e.ID}`
			});
		},
		/// 展卡点赞
		fnCardTop(e) {
			let filItem = {};
			// 最热
			if (this.current == 0) filItem = this.huibaHottestListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			// 最新
			if (this.current == 1) filItem = this.huibaLatestListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			// 用户是否点过赞
			dynamicPraise(filItem.dynamic_id).then(res => {
				if (!res.status) return;
				uni.showToast({
					title: res.msg,
					icon: 'none'
				});
				if (filItem.is_praise) {
					filItem.praise_count--;
					filItem.is_praise = false;
				} else {
					filItem.praise_count++;
					filItem.is_praise = true;
				}
			});
		},
		/// 展卡收藏
		fnCardSave(e) {
			let filItem = {};
			// 最热
			if (this.current == 0) filItem = this.huibaHottestListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			// 最新
			if (this.current == 1) filItem = this.huibaLatestListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
			// 用户是否已收藏
			dynamicCollection(filItem.dynamic_id).then(res => {
				if (!res.status) return;
				uni.showToast({
					title: res.msg,
					icon: 'none'
				});
				if (filItem.is_collection) {
					filItem.collection_count++;
					filItem.is_collection = false;
				} else {
				filItem.collection_count++;
				filItem.is_collection = true;
				}
			});
		},
		/// 展卡更多-关注
		fnCardFollow(e) {
			// 用户被关注
			if (e.User.UserAtte) {
				uni.showModal({
					content: '确定要取消关注TA吗？',
					success: res => {
						if (res.confirm) {
							followUser(e.User.ID).then(delRes => {
								if (delRes.data.Data == false) return;
								this.huibaHottestListData.filter(item => item.User.ID == e.User.ID).map(item => (item.User.UserAtte = false));
								this.huibaLatestListData.filter(item => item.User.ID == e.User.ID).map(item => (item.User.UserAtte = false));
								// 登录用户关注数减
								let tempUser = this.$store.getters['user/getLoginUserInfoData'];
								tempUser.user_info.follows_count--;
								this.$store.commit('user/setLoginUserInfoData', tempUser);
							});
						}
					}
				});
				return;
			} else {
				followUser(e.User.ID).then(addRes => {
					if (addRes.data.Data == false) return;
					this.huibaHottestListData.filter(item => item.User.ID == e.User.ID).map(item => (item.User.UserAtte = true));
					this.huibaLatestListData.filter(item => item.User.ID == e.User.ID).map(item => (item.User.UserAtte = true));
					// 登录用户关注数加
					let tempUser = this.$store.getters['user/getLoginUserInfoData'];
					tempUser.user_info.follows_count++;
					this.$store.commit('user/setLoginUserInfoData', tempUser);
				});
			}
		},
		/// 展卡更多-拉黑
		fnCardBlack(e) {
			// 用户是否被列入黑名单
			e.User.Black ? delUserBlack(e.User.ID) : addUserBlack(e.User.ID);
		},
		/// 展卡更多-跳转举报页
		fnCardReport(e) {
			uni.navigateTo({
				url: `/pages/report/report?id=${e.dynamic_id}&type=${e.ObjectType}`
			});
		}
		//
	}
};
</script>

<style>
.huiba-cover {
	display: block;
	width: 100%;
	height: 300rpx;
}
</style>

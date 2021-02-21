<template>
	<view>
		<!-- 滚动列表内容 -->
		<mescroll-uni :down="{ use: false }" :up="{ onScroll: true }" @up="upCallback" @scroll="scroll" @init="mescrollInit">
			<!-- 话题内容 -->
			<view class="plr18r ptb18r bgwhite mb18r">
				<!-- 标题 -->
				<view class="f36r fbold cblack mb18r">{{ topicInfoData.Name }}</view>
				<!-- 内容 -->
				<view class="fword f28r c111" :class="{ mb18r: calImageSrcs }" v-if="topicInfoData.Describe">{{ topicInfoData.Describe }}</view>
				<!-- 图片格 -->
				<view class="flex flex-fww" v-if="calImageSrcs">
					<block v-for="(img, index) in calImageSrcs" :key="index">
						<image
							class="hw100v br4r flex-33v"
							:class="{ mlr05v: index == 1 || index == 4 || index == 7, mb05v: (index == 1 && calImageSrcs.length > 3) || (index == 4 && calImageSrcs.length > 6) }"
							@tap="fnPreviewImage(index)"
							:src="img"
							:lazy-load="true"
							mode="widthFix"
						></image>
					</block>
				</view>
				<!-- 信息关注 -->
				<view class="flexr-jsb flex-aic ptb18r">
					<view class="f28r c111">
						{{ topicInfoData.UserCount || 0 }}
						<text class="ml8r cgray">人关注</text>
						<text class="mlr18r">|</text>
						{{ topicInfoData.TrendCount || 0 }}
						<text class="ml8r cgray">条动态</text>
					</view>
					<view class="bgwhite f28r ctheme ball2r-ctheme fcenter w128r br8r ptb8r" @tap="fnTopicFollow">{{ topicInfoData.TopicFollow ? '已关注' : '关注' }}</view>
				</view>
			</view>

			<!-- 选择导航 -->
			<view v-if="isFixed" class="hl90r bgwhite"></view>
			<view id="tabbar" class="flexr-jsa flex-ais hl90r bgwhite bbs2r" :class="{ 'tabbar-fixed': isFixed }">
				<view class="f32r fbold fcenter w128r tabbar" :class="{ tabbarsh: current == 0 }" @tap="fnBarClick(0)">最热</view>
				<view class="f32r fbold fcenter w128r tabbar" :class="{ tabbarsh: current == 1 }" @tap="fnBarClick(1)">最新</view>
			</view>
			<!-- 最热 -->
			<view v-if="status.hottest" :style="{ display: current == 0 ? 'block' : 'none' }">
				<block v-for="(infoData, index) in topicHottestListData" :key="index">
					<trend-card
						:info-data="infoData"
						@click="fnCardInfo"
						@user="fnCardUser"
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
				<block v-for="(infoData, index) in topicLatestListData" :key="index">
					<trend-card
						:info-data="infoData"
						@click="fnCardInfo"
						@user="fnCardUser"
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
import { previewImage } from '@/utils/UniUtil.js';
import { getTopicInfo, getTopicReplyList, addTopicFollow, delTopicFollow } from '@/api/TopicServer.js';
import { followUser, addUserBlack, delUserBlack } from '@/api/UserServer.js';
import { dynamicPraise } from '@/api/InteractServer.js';

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
			// 话题id
			id: 0,
			// 双击刷新
			clickRefresh: false,
			// 刷新间隔
			timeOutTopic: 0,
			// 导航距离顶部
			tabbarTop: 0,
			// 是否固定导航
			isFixed: false
			//
		};
	},

	onLoad(options) {
		if (options && options.id) {
			uni.showLoading({
				title: '加载中',
				mask: true
			});
			console.log(options);
			this.id = parseInt(options.id);
			// 获取话题信息和置顶信息
			getTopicInfo(this.id).then(topicRes => {
				this.$store.commit('topic/setTopicInfoData', topicRes.data.Data);
				// 导航标题
				uni.setNavigationBarTitle({
					title: topicRes.data.Data.Name
				});
				// 等待一秒页面渲染,$nextTick使用不能准确
				setTimeout(() => {
					uni.hideLoading();
					// 获取导航条距顶部高度
					this.setTabbarTop();
				}, 1500);
			});
		}
	},
	computed: {
		// 话题信息
		topicInfoData() {
			return this.$store.getters['topic/getTopicInfoData'];
		},
		// 话题最新信息
		topicLatestListData() {
			return this.$store.getters['topic/getTopicLatestListData'];
		},
		// 荟吧最热信息
		topicHottestListData() {
			return this.$store.getters['topic/getTopicHottestListData'];
		},
		/// 计算显示图片格
		calImageSrcs() {
			let imgArray = this.topicInfoData.ImageSrcs;
			let suffix = '_200x200.jpg/format/webp';
			if (imgArray) {
				imgArray = imgArray.map(item => (item.indexOf(suffix) == -1 ? (item += suffix) : '/static/default_image.png'));
			}
			return imgArray;
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
				topicid: this.id,
				hot: this.current == 0,
				page: mescroll.num,
				limit: mescroll.size
			};
			getTopicReplyList(params)
				.then(res => {
					// 最热
					if (this.current == 0) {
						if (mescroll.num == 1) {
							this.$store.commit('topic/setTopicHottestListData', res.data.Data);
						} else {
							this.$store.commit('topic/setTopicHottestListData', this.topicHottestListData.concat(res.data.Data));
						}
					}
					// 最新
					if (this.current == 1) {
						if (mescroll.num == 1) {
							this.$store.commit('topic/setTopicLatestListData', res.data.Data);
						} else {
							this.$store.commit('topic/setTopicLatestListData', this.topicLatestListData.concat(res.data.Data));
						}
					}
					mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size);
				})
				.catch(() => {
					mescroll.endErr();
				});
		},
		/// 滚动事件 (需在up配置onScroll:true才生效)
		scroll(mescroll) {
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
				this.timeOutTopic += 1;
				// 是否为刷新值和连续触发
				if (!this.clickRefresh && this.timeOutTopic >= 2) {
					// 刷新值开
					this.clickRefresh = true;
					// 获取新数据
					this.mescroll.resetUpScroll();
					// 定时器重置
					this.timeOutTopic = setTimeout(() => {
						// 清除定时器
						clearTimeout(this.timeOutTopic);
						// 连续触发记录重置
						this.timeOutTopic = 0;
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
				clearTimeout(this.timeOutTopic);
				// 连续触发记录重置
				this.timeOutTopic = 0;
				// 刷新值关
				this.clickRefresh = false;
			}
			// 滚动上滑
			this.mescroll.scrollTo(this.tabbarTop, 300);
		},
		/// 预览图片组
		fnPreviewImage(current) {
			let urls = this.topicInfoData.ImageSrcs.map(url => (url += '_0.jpg/format/webp'));
			previewImage(current, urls);
		},
		/// 话题用户关注
		fnTopicFollow() {
			// 荟吧被关注
			if (this.topicInfoData.TopicFollow) {
				uni.showModal({
					content: '确定要取消关注吗？',
					success: res => {
						if (res.confirm) {
							delTopicFollow(this.topicInfoData.ID).then(delRes => {
								if (delRes.data.Data == false) return;
								this.topicInfoData.UserCount--;
								this.topicInfoData.TopicFollow = false;
							});
						}
					}
				});
				return;
			} else {
				addTopicFollow(this.topicInfoData.ID).then(addRes => {
					if (addRes.data.Data == false) return;
					this.topicInfoData.UserCount++;
					this.topicInfoData.TopicFollow = true;
				});
			}
		},
		/// 展卡跳转详情页
		fnCardInfo(e) {
			uni.navigateTo({
				url: `/pages/topicreply-details/topicreply-details?id=${e.ID}&fromPage=topic&current=${this.current}`
			});
		},
		/// 展卡评论跳转详情页
		fnCardComm(e) {
			uni.navigateTo({
				url: `/pages/topicreply-details/topicreply-details?id=${e.ID}&fromPage=topic&current=${this.current}&comm=true`
			});
		},
		/// 展卡跳转用户中心页
		fnCardUser(e) {
			uni.navigateTo({
				url: `/pages/user-info/user-info?user_id=${e.user_info.user_id}`
			});
		},
		/// 展卡点赞
		fnCardTop(e) {
			let filItem = {};
			// 最热
			if (this.current == 0) filItem = this.topicHottestListData.filter(item => item.ID == e.ID)[0];
			// 最新
			if (this.current == 1) filItem = this.topicLatestListData.filter(item => item.ID == e.ID)[0];
			let params = {
				objecttype: 'topicreply',
				objectid: filItem.ID
			};
			// 用户是否点过赞
			if (filItem.UserTop) {
			} else {
				dynamicPraise(params).then(addRes => {
					if (addRes.data.Data == false) return;
					filItem.TopCount++;
					filItem.UserTop = true;
				});
			}
		},
		/// 展卡收藏
		fnCardSave(e) {
			let filItem = {};
			// 最热
			if (this.current == 0) filItem = this.topicHottestListData.filter(item => item.ID == e.ID)[0];
			// 最新
			if (this.current == 1) filItem = this.topicLatestListData.filter(item => item.ID == e.ID)[0];
			let params = {
				objecttype: 'topicreply',
				objectid: filItem.ID
			};
			// 用户是否已收藏
			if (filItem.UserSave) {
			} else {
				dynamicCollection(params).then(addRes => {
					if (addRes.data.Data == false) return;
					filItem.SaveCount++;
					filItem.UserSave = true;
				});
			}
		},
		/// 展卡更多-关注
		fnCardFollow(e) {
			let login_user = this.$store.getters['user/getLoginUserInfoData'];
			// 用户被关注
			if (e.user_info.is_follow) {
				uni.showModal({
					content: '确定要取消关注TA吗？',
					success: res => {
						if (res.confirm) {
							followUser(e.user_info.user_id).then(follow => {
								uni.showToast({
									title: follow.msg,
									icon: 'none'
								});
								if (!follow.status) return;
								this.topicHottestListData.filter(item => item.user_info.user_id == e.user_info.user_id).map(item => (item.user_info.is_follow = false));
								this.topicLatestListData.filter(item => item.user_info.user_id == e.user_info.user_id).map(item => (item.user_info.is_follow = false));
								// 登录用户关注数减
								if (!login_user.user_info) return;
								login_user.user_info.follows_count--;
								this.$store.commit('user/setLoginUserInfoData', login_user);
							});
						}
					}
				});
				return;
			} else {
				followUser(e.user_info.user_id).then(follow => {
					uni.showToast({
						title: follow.msg,
						icon: follow.status == 1 ? 'success' : 'none'
					});
					if (!follow.status) return;
					this.topicHottestListData.filter(item => item.user_info.user_id == e.user_info.user_id).map(item => (item.user_info.is_follow = true));
					this.topicLatestListData.filter(item => item.user_info.user_id == e.user_info.user_id).map(item => (item.user_info.is_follow = true));
					// 登录用户关注数加
					if (!login_user.user_info) return;
					login_user.user_info.follows_count++;
					this.$store.commit('user/setLoginUserInfoData', login_user);
				});
			}
		},
		/// 展卡更多-拉黑
		fnCardBlack(e) {
			// 用户是否被列入黑名单
			e.User.Black ? delUserBlack(e.user_info.user_id) : addUserBlack(e.user_info.user_id);
		},
		/// 展卡更多-跳转举报页
		fnCardReport(e) {
			uni.navigateTo({
				url: `/pages/report/report?id=${e.ID}&type=topicreply`
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
	height: 400rpx;
}
</style>

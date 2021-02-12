<template>
	<view>
		<!-- 主页顶部导航栏 -->
		<view class="posif posi-tlr0 z500 bgtheme">
			<!-- #ifdef APP-PLUS -->
			<view class="status-bar"></view>
			<!-- #endif -->
			<view class="flex plr18r">
				<view class="w128r hl90r f32r fcenter cwhite-a6" :class="{'barsh-home': current == 0}" @tap="fnBarClick(0)">发现</view>
				<view class="w128r hl90r f32r fcenter cwhite-a6" :class="{'barsh-home':current == 1}" @tap="fnBarClick(1)">关注</view>
				<view class="w128r hl90r f32r fcenter cwhite-a6" :class="{'barsh-home':current == 2}" @tap="fnBarClick(2)">广场</view>
			</view>
		</view>

		<!-- 滑动切换视图 -->
		<swiper class="posia posi-all0" :current="current" @change="fnBarClick">
			<!-- 推荐 -->
			<swiper-item>
				<mescroll-uni v-if="status.recommend" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(item,index) in mainData" :key="index">
						<trend-card :item="item" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop" @comm="fnCardComm"
						 @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
					</block>
				</mescroll-uni>
			</swiper-item>

			<!-- 关注 -->
			<swiper-item>
				<mescroll-uni v-if="status.follow" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(infoData,index) in atteData" :key="index">
						<trend-card :info-data="infoData" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop"
						 @comm="fnCardComm" @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
					</block>
				</mescroll-uni>
			</swiper-item>

			<!-- 广场 -->
			<swiper-item>
				<mescroll-uni v-if="status.square" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(infoData,index) in squareData" :key="index">
						<trend-card :info-data="infoData" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop"
						 @comm="fnCardComm" @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
					</block>
				</mescroll-uni>
			</swiper-item>
		</swiper>
	</view>
</template>

<script>
	import {
		getSquareList,
		getDiscoverList,
		getAtteList
	} from "@/api/TrendServer.js"
	import {
		dynamicPraise,
		dynamicCollection,
	} from "@/api/InteractServer.js"
	import {
		addUserAtte,
		delUserAtte,
		addUserBlack,
		delUserBlack
	} from "@/api/UserServer.js"

	// 动态信息项卡片组件
	import TrendCard from '@/components/trend-card/trend-card'

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
					follow: false,
					square: false,
				},
				// 双击刷新
				clickRefresh: false,
				// 刷新间隔
				timeOutHome: 0,
				// 列表最大ID定位
				maxID: [-1, -1, -1],
				// 刷新组件实例
				mescroll: [],
				//
			}
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
				return this.$store.getters['trend/getMainData']
			},
			// 关注列表数据
			atteData() {
				return this.$store.getters['trend/getAtteData']
			},
			// 广场列表数据
			squareData() {
				return this.$store.getters['trend/getSquareData']
			},
			//
		},

		methods: {
			/// mescroll组件初始化的回调,可获取到mescroll对象
			mescrollInit(mescroll) {
				this.mescroll[this.current] = mescroll
			},
			/// 下拉刷新的回调
			downCallback(mescroll) {
				this.maxID[this.current] = -1;
				// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
				mescroll.resetUpScroll()
			},
			/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
			upCallback(mescroll) {
				console.log('---mescroll---');
				console.log(mescroll);
				// 联网获取数据
				[getDiscoverList, getAtteList, getSquareList][this.current]({
					page: mescroll.num,
					last_id: this.maxID[this.current],
					limit: mescroll.size
				}).then(res => {
					console.log(res);
					// 固定项数据往后加载
					if (mescroll.num == 1) this.maxID[this.current] = res.data.data[0].dynamic_id;
					// 不同标签存入不同数据变量
					if (this.current == 0) {
						let arrayData = mescroll.num == 1 ? res.data.data : this.mainData.concat(res.data.data)
						console.log(arrayData);
						this.$store.commit('trend/setMainData', arrayData)
					}
					if (this.current == 1) {
						let arrayData = mescroll.num == 1 ? res.data.data : this.atteData.concat(res.data.data)
						this.$store.commit('trend/setAtteData', arrayData)
					}
					if (this.current == 2) {
						let arrayData = mescroll.num == 1 ? res.data.data : this.squareData.concat(res.data.data)
						this.$store.commit('trend/setSquareData', arrayData)
					}
					// 数据获取成功关闭loading区
					mescroll.endSuccess(res.data.data.length, res.data.data.length > 0);
				}).catch(() => {
					// mescroll.endErr();
					mescroll.endSuccess(0, false);
				});
			},

			/// 导航选项双击刷新
			fnRefreshData() {
				this.maxID[this.current] = -1;
				this.mescroll[this.current].scrollTo(0, 300);
				setTimeout(() => {
					this.mescroll[this.current].resetUpScroll(true)
				}, 1000)
			},
			/// 顶部导航选项点击
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
							clearTimeout(this.timeOutHome)
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
					if (!this.status.follow && current == 1) this.status.follow = true;
					if (!this.status.square && current == 2) this.status.square = true;
					// 清除定时器
					clearTimeout(this.timeOutHome)
					// 连续触发记录重置
					this.timeOutHome = 0;
					// 刷新值关
					this.clickRefresh = false;
				}
			},

			/// 展卡跳转详情页
			fnCardInfo(e) {
				e.ObjectType = 'trend';
				console.log(e);
				if (e.ObjectType == 'trend') {
					uni.navigateTo({
						url: `/pages/trend-details/trend-details?dynamic_id=${e.dynamic_id}&fromPage=home&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'album') {
					uni.navigateTo({
						url: `/pages/album-details/album-details?id=${e.ObjectID}&fromPage=home&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'topic') {
					uni.navigateTo({
						url: `/pages/topic-details/topic-details?id=${e.ObjectID}&fromPage=home&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'topicreply') {
					uni.navigateTo({
						url: `/pages/topicreply-details/topicreply-details?id=${e.ObjectID}&fromPage=home&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'video') {
					uni.navigateTo({
						url: `/pages/video-details/video-details?id=${e.ObjectID}&fromPage=home&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'word') {
					uni.navigateTo({
						url: `/pages/word-details/word-details?id=${e.ObjectID}&fromPage=home&current=${this.current}`
					})
					return
				}
			},
			/// 展卡评论跳转详情页
			fnCardComm(e) {
				console.log(e.ObjectType);
				if (e.ObjectType == 'trend') {
					uni.navigateTo({
						url: `/pages/trend-details/trend-details?id=${e.ObjectID}&fromPage=home&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'album') {
					uni.navigateTo({
						url: `/pages/album-details/album-details?id=${e.ObjectID}&fromPage=home&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'topic') {
					uni.navigateTo({
						url: `/pages/topic-details/topic-details?id=${e.ObjectID}&fromPage=home&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'topicreply') {
					uni.navigateTo({
						url: `/pages/topicreply-details/topicreply-details?id=${e.ObjectID}&fromPage=home&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'video') {
					uni.navigateTo({
						url: `/pages/video-details/video-details?id=${e.ObjectID}&fromPage=home&current=${this.current}&comm=true`
					})
					return
				}
			},
			/// 展卡跳转用户中心页
			fnCardUser(e) {
				uni.navigateTo({
					url: `/pages/user-info/user-info?id=${e.User.ID}`
				})
			},
			/// 展卡跳转荟吧页
			fnCardHuiba(e) {
				uni.navigateTo({
					url: `/pages/huiba-details/huiba-details?id=${e.ID}`
				})
			},
			/// 展卡点赞
			fnCardTop(e) {
				let filItem = {};
				// 推荐
				if (this.current == 0) filItem = this.mainData.filter(item => item.ObjectID == e.ObjectID)[0];
				// 关注
				if (this.current == 1) filItem = this.atteData.filter(item => item.ObjectID == e.ObjectID)[0];
				// 广场
				if (this.current == 2) filItem = this.squareData.filter(item => item.ObjectID == e.ObjectID)[0];

				console.log(filItem);
				// 点赞动态
				dynamicPraise(filItem.dynamic_id).then(res => {
					uni.showToast({
						title: res.msg,
						icon: res.status == 1 ? 'success' : 'none'
					});
					if (!res.status) return;

					// 用户是否点过赞
					if (filItem.is_praise) {
						filItem.praise_count--;
						filItem.is_praise = false;
					} else {
						filItem.praise_count++;
						filItem.is_praise = true
					}
				});
			},
			/// 展卡收藏
			fnCardSave(e) {
				let filItem = {};
				// 推荐
				if (this.current == 0) filItem = this.mainData.filter(item => item.ObjectID == e.ObjectID)[0];
				// 关注
				if (this.current == 1) filItem = this.atteData.filter(item => item.ObjectID == e.ObjectID)[0];
				// 广场
				if (this.current == 2) filItem = this.squareData.filter(item => item.ObjectID == e.ObjectID)[0];

				dynamicCollection(filItem.dynamic_id).then(res => {
					uni.showToast({
						title: res.msg,
						icon: res.status == 1 ? 'success' : 'none'
					});
					if (!res.status) return;

					// 用户是否已收藏
					if (filItem.is_collection) {
						filItem.collection_count--;
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
								delUserAtte(e.User.ID).then(delRes => {
									if (delRes.data.Data == false) return
									this.atteData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
										false)
									this.mainData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
										false)
									this.squareData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
										false)
									// 登录用户关注数减
									let tempUser = this.$store.getters['user/getUserInfoData']
									tempUser.AtteCount--
									this.$store.commit('user/setUserInfoData', tempUser)
								})
							}
						}
					})
					return
				} else {
					addUserAtte(e.User.ID).then(addRes => {
						if (addRes.data.Data == false) return
						this.atteData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte = true)
						this.mainData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte = true)
						this.squareData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte = true)
						// 登录用户关注数加
						let tempUser = this.$store.getters['user/getUserInfoData']
						tempUser.AtteCount++
						this.$store.commit('user/setUserInfoData', tempUser)
					})
				}
			},
			/// 展卡更多-拉黑
			fnCardBlack(e) {
				// 用户是否被列入黑名单
				e.User.Black ? delUserBlack(e.User.ID) : addUserBlack(e.User.ID)
			},
			/// 展卡更多-跳转举报页
			fnCardReport(e) {
				uni.navigateTo({
					url: `/pages/report/report?id=${e.ObjectID}&type=${e.ObjectType}`
				})
			},
			//
		}
	}
</script>

<style>
	/* 主页导航选中高亮 */
	.barsh-home {
		color: #FFFFFF;
		font-size: 36rpx;
		font-weight: bold;
	}
</style>

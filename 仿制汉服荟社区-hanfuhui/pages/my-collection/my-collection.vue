<template>
	<view>
		<!-- 顶部导航栏 -->
		<view class="posif posi-tlr0 z500 bgf8">
			<!-- 导航条 -->
			<scroll-view class="scroll-bar" :scroll-x="true" :show-scrollbar="false" :scroll-into-view="scrollInto"
			 :scroll-with-animation="true">
				<view v-for="tab in tabBars" :key="tab.id" class="scroll-bar-item25v" :class="{'scroll-bar-itemsh':current == tab.current}"
				 :id="tab.id" :data-current="tab.current" @tap="fnBarClick(tab)">
					{{tab.name}}
				</view>
			</scroll-view>
		</view>

		<!-- 滑动切换视图 -->
		<swiper class="posia posi-all0" :current="current" @change="fnBarClick">
			<!-- 全部 -->
			<swiper-item>
				<mescroll-uni v-if="status.all" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(infoData,index) in userSaveAllListData" :key="index">
						<trend-card :info-data="infoData.dynamic" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop"
						 @comm="fnCardComm" @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
					</block>
				</mescroll-uni>
			</swiper-item>

			<!-- 摄影 -->
			<swiper-item>
				<mescroll-uni v-if="status.album" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(infoData,index) in userSaveAlbumListData" :key="index">
						<trend-card :info-data="infoData.dynamic" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop"
						 @comm="fnCardComm" @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
					</block>
				</mescroll-uni>
			</swiper-item>

			<!-- 视频 -->
			<swiper-item>
				<mescroll-uni v-if="status.video" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(infoData,index) in userSaveVideoListData" :key="index">
						<trend-card :info-data="infoData.dynamic" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop"
						 @comm="fnCardComm" @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
					</block>
				</mescroll-uni>
			</swiper-item>

			<!-- 文章 -->
			<swiper-item>
				<mescroll-uni v-if="status.word" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(infoData,index) in userSaveWordListData" :key="index">
						<trend-card :info-data="infoData.dynamic" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop"
						 @comm="fnCardComm" @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
					</block>
				</mescroll-uni>
			</swiper-item>
		</swiper>
	</view>
</template>

<script>
	import {
		dynamicPraise,
        getCollectionList,
	} from "@/api/InteractServer.js"
	import {
		followUser,
		addUserBlack,
		delUserBlack
	} from "@/api/UserServer.js"

	// 动态信息项卡片组件
	import TrendCard from '@/components/trend-card/trend-card'

	export default {
		components: {
			TrendCard
		},
		data() {
			return {
				// 导航项滑动初始id
				scrollInto: "all",
				// 顶部导航滑动页选中
				current: 0,
				// 导航项列表
				tabBars: [{
					id: "all",
					name: '全部',
					current: 0
				}, {
					id: "album",
					name: '摄影',
					current: 1
				}, {
					id: "video",
					name: '视频',
					current: 2
				}, {
					id: "word",
					name: '文章',
					current: 3,
				}],
				// 激活顶部导航关联页状态
				status: {
					all: true,
					album: false,
					video: false,
					word: false,
				},
				// 双击刷新
				clickRefresh: false,
				// 刷新间隔
				timeOutCollection: 0,
				// 刷新组件实例
				mescroll: {
					all: null,
					album: null,
					video: null,
					word: null,
				},
				//
			}
		},
		computed: {
			// 全部
			userSaveAllListData() {
				return this.$store.getters['interact/getUserSaveAllListData']
			},
			// 摄影
			userSaveAlbumListData() {
				return this.$store.getters['interact/getUserSaveAlbumListData']
			},
			// 视频
			userSaveVideoListData() {
				return this.$store.getters['interact/getUserSaveVideoListData']
			},
			// 文章
			userSaveWordListData() {
				return this.$store.getters['interact/getUserSaveWordListData']
			},
			//
		},
		onLoad(options) {

		},
		methods: {
			/// mescroll组件初始化的回调,可获取到mescroll对象
			mescrollInit(mescroll) {
				this.mescroll[this.scrollInto] = mescroll;
			},
			/// 下拉刷新的回调
			downCallback(mescroll) {
				// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
				this.mescroll[this.scrollInto].resetUpScroll()
			},
			/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
			upCallback(mescroll) {
                getCollectionList({
					page: mescroll.num,
					limit: mescroll.size,
					dynamic_type: this.scrollInto == 'all' ? '' : this.scrollInto
				}).then(res => {
				    let lists = res.data;
					// 全部
					if (this.scrollInto == 'all') {
						if (mescroll.num == 1) {
							this.$store.commit('interact/setUserSaveAllListData', lists.data)
						} else {
							this.$store.commit('interact/setUserSaveAllListData', this.userSaveAllListData.concat(lists.data))
						}
					}
					// 摄影
					if (this.scrollInto == 'album') {
						if (mescroll.num == 1) {
							this.$store.commit('interact/setUserSaveAlbumListData', lists.data)
						} else {
							this.$store.commit('interact/setUserSaveAlbumListData', this.userSaveAlbumListData.concat(lists.data))
						}
					}
					// 视频
					if (this.scrollInto == 'video') {
						if (mescroll.num == 1) {
							this.$store.commit('interact/setUserSaveVideoListData', lists.data)
						} else {
							this.$store.commit('interact/setUserSaveVideoListData', this.userSaveVideoListData.concat(lists.data))
						}
					}
					// 文章
					if (this.scrollInto == 'word') {
						if (mescroll.num == 1) {
							this.$store.commit('interact/setUserSaveWordListData', lists.data)
						} else {
							this.$store.commit('interact/setUserSaveWordListData', this.userSaveWordListData.concat(lists.data))
						}
					}
                    mescroll.endSuccess(lists.data.length, mescroll.num < res.data.count_page);
				}).catch(() => {
					mescroll.endErr()
				})
			},
			/// 导航选项双击刷新获取新数据
			fnRefreshData() {
				this.mescroll[this.scrollInto].scrollTo(0, 300);
				setTimeout(() => {
					this.mescroll[this.scrollInto].resetUpScroll(true)
				}, 1000)
			},
			/// 顶部导航选项点击
			fnBarClick(e) {
				let current = e.hasOwnProperty("detail") ? e.detail.current : e.current;
				this.scrollInto = this.tabBars[current].id;
				// 是否当前项点击
				if (e.hasOwnProperty("id") && this.current == current) {
					this.timeOutCollection += 1;
					// 是否为刷新值和连续触发
					if (!this.clickRefresh && this.timeOutCollection >= 2) {
						// 刷新值开
						this.clickRefresh = true;
						// 获取新数据
						this.fnRefreshData();
						// 定时器重置
						this.timeOutCollection = setTimeout(() => {
							// 清除定时器
							clearTimeout(this.timeOutCollection)
							// 连续触发记录重置
							this.timeOutCollection = 0;
							// 刷新值关
							this.clickRefresh = false;
						}, 5000);
					}
					return;
				} else {
					// 改变顶部导航选中
					this.current = current;
					// 首次选中激活顶部导航关联页状态
					if (!this.status.album && current == 1) this.status.album = true;
					if (!this.status.video && current == 2) this.status.video = true;
					if (!this.status.word && current == 3) this.status.word = true;
					// 清除定时器
					clearTimeout(this.timeOutCollection)
					// 连续触发记录重置
					this.timeOutCollection = 0;
					// 刷新值关
					this.clickRefresh = false;
				}
			},
			/// 展卡跳转详情页
			fnCardInfo(e) {
				console.log(e.ObjectType);
				if (e.ObjectType == 'trend') {
					uni.navigateTo({
						url: `/pages/trend-details/trend-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'album') {
					uni.navigateTo({
						url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'topic') {
					uni.navigateTo({
						url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'topicreply') {
					uni.navigateTo({
						url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'video') {
					uni.navigateTo({
						url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'word') {
					uni.navigateTo({
						url: `/pages/word-details/word-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}`
					})
					return
				}
			},
			/// 展卡评论跳转详情页
			fnCardComm(e) {
				console.log(e.ObjectType);
				if (e.ObjectType == 'trend') {
					uni.navigateTo({
						url: `/pages/trend-details/trend-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'album') {
					uni.navigateTo({
						url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'topic') {
					uni.navigateTo({
						url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'topicreply') {
					uni.navigateTo({
						url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'video') {
					uni.navigateTo({
						url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=collection&current=${this.current}&comm=true`
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
				let filAllItem = {};
				// 全部
				if (this.current == 0) {
					filAllItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					if (e.ObjectType == 'album') {
						filItem = this.userSaveAlbumListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					} else if (e.ObjectType == 'video') {
						filItem = this.userSaveVideoListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					} else if (e.ObjectType == 'word') {
						filItem = this.userSaveWordListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					}
				}
				// 摄影
				if (this.current == 1) {
					filItem = this.userSaveAlbumListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					filAllItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				}
				// 视频
				if (this.current == 2) {
					filItem = this.userSaveVideoListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					filAllItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				}
				// 文章
				if (this.current == 3) {
					filItem = this.userSaveWordListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					filAllItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				}
				let params = {
					objecttype: filItem.ObjectType,
					objectid: filItem.dynamic_id
				}
				// 用户是否点过赞
				if (filItem.UserTop) {} else {
					dynamicPraise(params).then(addRes => {
						if (addRes.data.Data == false) return
						filItem.TopCount++;
						filItem.UserTop = true
						filAllItem.TopCount++;
						filAllItem.UserTop = true
					})
				}
			},
			/// 展卡收藏
			fnCardSave(e) {
				let filItem = {};
				let filAllItem = {};
				// 全部
				if (this.current == 0) {
					filAllItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					if (e.ObjectType == 'album') {
						filItem = this.userSaveAlbumListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					} else if (e.ObjectType == 'video') {
						filItem = this.userSaveVideoListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					} else if (e.ObjectType == 'word') {
						filItem = this.userSaveWordListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					}
				}
				// 摄影
				if (this.current == 1) {
					filItem = this.userSaveAlbumListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					filAllItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				}
				// 视频
				if (this.current == 2) {
					filItem = this.userSaveVideoListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					filAllItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				}
				// 文章
				if (this.current == 3) {
					filItem = this.userSaveWordListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
					filAllItem = this.userSaveAllListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				}
				let params = {
					objecttype: filItem.ObjectType,
					objectid: filItem.dynamic_id
				}
				// 用户是否已收藏
				if (filItem.UserSave) {

				} else {
					dynamicCollection(params).then(addRes => {
						if (addRes.data.Data == false) return
						filItem.SaveCount++;
						filItem.UserSave = true
						filAllItem.SaveCount++;
						filAllItem.UserSave = true
					})
				}
			},
			/// 展卡更多-关注
			fnCardFollow(e) {
				// 用户被关注
				if (e.User.UserAtte) {
                    followUser(e.User.ID).then(delRes => {
						if (delRes.data.Data == false) return
						this.userSaveAllListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
							false)
						this.userSaveAlbumListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
							false)
						this.userSaveVideoListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
							false)
						this.userSaveWordListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
							false)
						// 登录用户关注数减
						let tempUser = this.$store.getters['user/getLoginUserInfoData']
						tempUser.user_info.follows_count--
						this.$store.commit('user/setLoginUserInfoData', tempUser)
					})
				} else {
					followUser(e.User.ID).then(addRes => {
						if (addRes.data.Data == false) return
						this.userSaveAllListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
							true)
						this.userSaveAlbumListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
							true)
						this.userSaveVideoListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
							true)
						this.userSaveWordListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
							true)
						// 登录用户关注数加
						let tempUser = this.$store.getters['user/getLoginUserInfoData']
						tempUser.user_info.follows_count++
						this.$store.commit('user/setLoginUserInfoData', tempUser)
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
					url: `/pages/report/report?id=${e.dynamic_id}&type=${e.ObjectType}`
				})
			},
			//
		}
	}
</script>

<style>
</style>

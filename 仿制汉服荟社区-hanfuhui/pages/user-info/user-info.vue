<template>
	<view>
		<!-- 滚动列表内容 -->
		<mescroll-uni :down="{use:false}" :up="{onScroll:true}" @up="upCallback" @scroll="scroll" @init="mescrollInit">
			<!-- 用户背景封面 -->
			<image class="info-cover" @tap="fnMainBgPic" :src="tempUserInfoData.MainBgPic ? tempUserInfoData.MainBgPic + '_850x300.jpg/format/webp' : '/static/default_image.png'"
			 mode="aspectFill"></image>
			<view class="plr28r pb18r bgwhite">
				<!-- 用户头像关注 -->
				<view class="posir flexr-jfe flex-aic ptb18r">
					<view class="info-avatar">
						<user-avatar :src="tempUserInfoData.user_info.user_avatar ? tempUserInfoData.user_info.user_avatar : '/static/default_avatar.png'"
						 tag="" size="lg"></user-avatar>
					</view>
					<view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r mr28r" v-if="!calIsLoginUser && tempUserInfoData.ShowIM"
					 @tap="fnUserChat">勾搭TA</view>
					<view class="bgtheme f28r cwhite fcenter w128r br8r ptb8r" v-if="!calIsLoginUser" @tap="fnUserFollow">{{tempUserInfoData.user_info.is_follow?'已关注':'关注'}}</view>
					<view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r" v-if="calIsLoginUser" @tap="fnUserEdit">编辑信息</view>
				</view>
				<!-- 用户名小荟号 -->
				<view class="flex flex-aic mb18r">
					<text class="f36r c111 fbold mr18r">{{tempUserInfoData.NickName}}</text>
					<i-icon v-if="[0, 1].indexOf(tempUserInfoData.user_info.user_sex) > -1" :type="tempUserInfoData.user_info.user_sex_text == '男' ? 'nan':'nv' "
					 size="28" :color="tempUserInfoData.user_info.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
					<text v-if="tempUserInfoData.user_name" class="f24r cgray ml28r">小荟号：{{tempUserInfoData.user_name}}</text>
					<text v-else-if="tempUserInfoData.user_email" class="f24r cgray ml28r">邮箱：{{tempUserInfoData.user_email}}</text>
					<text v-else-if="tempUserInfoData.user_mobile" class="f24r cgray ml28r">手机号：{{tempUserInfoData.user_mobile}}</text>
				</view>
				<!-- UUID 唯一标识 -->
				<view v-if="tempUserInfoData.user_info.user_uuid" class="f24r cgray fword">UUID：{{tempUserInfoData.user_info.user_uuid}}</view>
				<!-- 简介说明 -->
				<view class="f24r c555 fword">{{tempUserInfoData.Describe || '该同袍还不知道怎么描述寄己 (╯▽╰)╭'}}</view>
				<!-- 城市位置 -->
				<view class="flex flex-aic mtb18r">
					<i-icon type="weizhi" size="32" color="#8F8F94"></i-icon>
					<text class="f24r cgray ml8r">{{calAddress}}</text>
				</view>
				<!-- 粉丝关注人气统计 -->
				<view>
					<text class="f28r fbold c555 mr18r">{{tempUserInfoData.FansCount || 0}}</text><text class="f24r cgray mr28r">粉丝</text>
					<text class="f28r fbold c555 mr18r">{{tempUserInfoData.user_info.follows_count || 0}}</text><text class="f24r cgray mr28r">关注</text>
					<text class="f28r fbold c555 mr18r">{{tempUserInfoData.Popularity || 0}}</text><text class="f24r cgray">人气</text>
				</view>
			</view>
			<!-- 选择导航 -->
			<view v-if="isFixed" class="hl90r bgwhite mb18r"></view>
			<view id="tabbar" class="flexr-jsa flex-ais hl90r bgwhite mb18r" :class="{'tabbar-fixed':isFixed}">
				<view class="f32r fbold fcenter w128r tabbar" :class="{'tabbarsh':current == 0}" @tap="fnBarClick(0)">发布</view>
				<view class="f32r fbold fcenter w128r tabbar" :class="{'tabbarsh':current == 1}" @tap="fnBarClick(1)">赞过</view>
			</view>
			<!-- 发布 -->
			<view v-if="status.publish" :style="{display: current==0 ? 'block' :'none'}">
				<block v-for="(infoData,index) in userPublishListData" :key="index">
					<trend-card :head="false" :info-data="infoData" @click="fnCardInfo" @huiba="fnCardHuiba" @top="fnCardTop" @comm="fnCardComm"
					 @save="fnCardSave"></trend-card>
				</block>
			</view>
			<!-- 赞过 -->
			<view v-if="status.praise" :style="{display: current==1 ? 'block' :'none'}">
				<block v-for="(infoData,index) in userTopListData" :key="index">
					<trend-card :head="false" :info-data="infoData" @click="fnCardInfo" @huiba="fnCardHuiba" @top="fnCardTop" @comm="fnCardComm"
					 @save="fnCardSave"></trend-card>
				</block>
			</view>
		</mescroll-uni>

	</view>
</template>

<script>
	import {
		fnUploadUpyunPic
	} from "@/utils/UniUtil.js"
	import {
		getUserPublishList,
		getUserTopList,
		getUserInfo,
		modifyUserMainBgPic,
		followUser,
	} from "@/api/UserServer.js"
	import {
		dynamicPraise,
	} from "@/api/InteractServer.js"

	// 动态信息项卡片组件
	import TrendCard from '@/components/trend-card/trend-card'

	export default {
		components: {
			TrendCard
		},
		data() {
			return {
				// 选中 发布
				current: 0,
				// 激活顶部导航关联页状态
				status: {
					publish: true,
					praise: false,
				},
				// 滚动动实例
				mescroll: null,
				// 用户id
				user_id: 0,
				// 双击刷新
				clickRefresh: false,
				// 刷新间隔
				timeOutUserInfo: 0,
				// 导航距离顶部
				tabbarTop: 0,
				// 是否固定导航
				isFixed: false,
				// 距离顶部达到导航距离
				topNum: 0,
				//
			};
		},

		onLoad(option) {
			if (option && option.user_id) {
				uni.showLoading({
					title: "加载中",
					mask: true
				})
				this.user_id = parseInt(option.user_id);
				// 初始获取用户信息
				getUserInfo(this.user_id).then(userRes => {
					this.$store.commit('user/setTempUserInfoData', userRes.data)
					if (this.user_id == this.$store.getters['user/getLoginUserInfoData'].user_id) {
						this.$store.commit('user/setLoginUserInfoData', userRes.data)
					}
					// 导航标题
					uni.setNavigationBarTitle({
						title: userRes.data.user_info.nick_name
					});
				});
				// 等待一秒页面渲染,$nextTick使用不能准确
				setTimeout(() => {
					uni.hideLoading()
					// 获取导航条距顶部高度
					this.setTabbarTop();
				}, 1500);
			}
		},
		computed: {
			// 所有发布
			userPublishListData() {
				return this.$store.getters['user/getUserPublishListData']
			},
			// 点赞过
			userTopListData() {
				return this.$store.getters['user/getUserTopListData']
			},
			// 临时用户信息
			tempUserInfoData() {
				return this.$store.getters['user/getTempUserInfoData']
			},
			/// 计算修改地址逗号换空格
			calAddress() {
				let addr = this.tempUserInfoData.CityNames;
				return !!addr ? addr.split(',').join(' ') : '未知 未知'
			},
			/// 计算是否当前登录用户
			calIsLoginUser() {
				return this.tempUserInfoData.user_id == this.$store.getters['user/getLoginUserInfoData'].user_id
			},
		},

		methods: {
			/// mescroll组件初始化的回调,可获取到mescroll对象
			mescrollInit(mescroll) {
				this.mescroll = mescroll;
			},
			/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
			upCallback(mescroll) {
				let params = {
					userid: this.user_id,
					page: mescroll.num,
					limit: mescroll.size
				};
				if (this.current == 0) params.objecttype = '';
				// 正常加载数据
				[getUserPublishList, getUserTopList][this.current](params).then(res => {
					// 发布
					if (this.current == 0) {
						if (mescroll.num == 1) {
							this.$store.commit('user/setUserPublishListData', res.data.Data)
						} else {
							this.$store.commit('user/setUserPublishListData', this.userPublishListData.concat(res.data.Data))
						}
					}
					// 赞过
					if (this.current == 1) {
						if (mescroll.num == 1) {
							this.$store.commit('user/setUserTopListData', res.data.Data)
						} else {
							this.$store.commit('user/setUserTopListData', this.userTopListData.concat(res.data.Data))
						}
					}
					mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size);
				}).catch(() => {
					// mescroll.endErr();
					mescroll.endSuccess(0, false);
				});
			},
			/// 滚动事件 (需在up配置onScroll:true才生效)
			scroll(mescroll) {
				this.topNum = mescroll.getScrollTop()
				if (mescroll.getScrollTop() >= this.tabbarTop) {
					this.isFixed = true // 显示悬浮菜单
				} else {
					this.isFixed = false // 隐藏悬浮菜单
				}
			},
			/// 设置nav到顶部的距离 (滚动条为0, 菜单顶部的数据加载完毕获取到的数值是最精确的)
			setTabbarTop() {
				let view = uni.createSelectorQuery().in(this).select('#tabbar');
				view.boundingClientRect(data => {
					// 到屏幕顶部的距离
					this.tabbarTop = data.top
				}).exec();
			},

			/// 顶部导航选项点击
			fnBarClick(current) {
				// 是否当前项点击
				if (this.current == current) {
					this.timeOutUserInfo += 1;
					// 是否为刷新值和连续触发
					if (!this.clickRefresh && this.timeOutUserInfo >= 2) {
						// 刷新值开
						this.clickRefresh = true;
						// 获取新数据
						this.mescroll.resetUpScroll();
						// 定时器重置
						this.timeOutUserInfo = setTimeout(() => {
							// 清除定时器
							clearTimeout(this.timeOutUserInfo)
							// 连续触发记录重置
							this.timeOutUserInfo = 0;
							// 刷新值关
							this.clickRefresh = false;
						}, 5000);
					}
				} else {
					// 改变顶部导航选中
					this.current = current;
					// 首次选中激活顶部导航关联页状态
					if (!this.status.praise && this.current == 1) this.status.praise = true;
					// 获取新数据
					this.mescroll.resetUpScroll();
					// 清除定时器
					clearTimeout(this.timeOutUserInfo)
					// 连续触发记录重置
					this.timeOutUserInfo = 0;
					// 刷新值关
					this.clickRefresh = false;
				}
				// 滚动上滑
				this.mescroll.scrollTo(this.tabbarTop, 300);
			},

			/// 用户跳转信息编辑页
			fnUserEdit() {
				uni.navigateTo({
					url: `/pages/user-info-modify/user-info-modify?id=${this.tempUserInfoData.user_id}`
				})
			},
			/// 用户跳转聊天页
			fnUserChat() {
				console.log('跳转用户聊天页', this.tempUserInfoData);
				// 由于IM接入删减
			},
			/// 修改用户背景封面图
			fnMainBgPic() {
				// 是否为当前登录用户修改
				if (this.tempUserInfoData.user_id != this.$store.getters['user/getLoginUserInfoData'].user_id) return
				fnUploadUpyunPic(1).then(res => {
					if (res) return res
				}).then(uploadRes => {
					if (typeof uploadRes == 'undefined') return
					modifyUserMainBgPic(uploadRes.url)
					let userInfo = Object.assign({}, this.$store.getters['user/getUserInfoData']);
					userInfo.MainBgPic = 'https://pic.hanfugou.com' + uploadRes.url;
					this.$store.commit('user/setAccountInfoMainBgPicData', userInfo.MainBgPic)
					this.$store.commit('user/setLoginUserInfoData', userInfo)
					this.$store.commit('user/setTempUserInfoData', userInfo)
				}).catch(() => {
					uni.showToast({
						title: '上传背景失败',
						icon: 'none'
					})
				})
			},
			/// 用户关注
			fnUserFollow() {
				let login_user = this.$store.getters['user/getLoginUserInfoData'];
				// 用户被关注
				if (this.tempUserInfoData.is_follow) {
                    getLoginUserInfoData(this.tempUserInfoData.user_id).then(delRes => {
						if (delRes.data.Data == false) return
						this.userPublishListData.map(item => item.user_info.is_follow = false)
						this.tempUserInfoData.is_follow = false;
						// 登录用户关注数减
						if(!login_user.user_info) return;
						login_user.user_info.follows_count--;
						this.$store.commit('user/setLoginUserInfoData', login_user);
					})
				} else {
					followUser(this.tempUserInfoData.user_id).then(addRes => {
						if (addRes.data.Data == false) return
						this.userPublishListData.map(item => item.user_info.is_follow = true)
						this.tempUserInfoData.is_follow = true;
						// 登录用户关注数减
						if(!login_user.user_info) return;
						login_user.user_info.follows_count++;
						this.$store.commit('user/setLoginUserInfoData', login_user);
					})
				}
			},
			/// 展卡跳转详情页
			fnCardInfo(e) {
				console.log(e.ObjectType);
				if (e.ObjectType == 'trend') {
					uni.navigateTo({
						url: `/pages/trend-details/trend-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'album') {
					uni.navigateTo({
						url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'topic') {
					uni.navigateTo({
						url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'topicreply') {
					uni.navigateTo({
						url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'video') {
					uni.navigateTo({
						url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}`
					})
					return
				}
				if (e.ObjectType == 'word') {
					uni.navigateTo({
						url: `/pages/word-details/word-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}`
					})
					return
				}
			},
			// 展卡评论跳转详情页
			fnCardComm(e) {
				console.log(e.ObjectType);
				if (e.ObjectType == 'trend') {
					uni.navigateTo({
						url: `/pages/trend-details/trend-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'album') {
					uni.navigateTo({
						url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'topic') {
					uni.navigateTo({
						url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'topicreply') {
					uni.navigateTo({
						url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}&comm=true`
					})
					return
				}
				if (e.ObjectType == 'video') {
					uni.navigateTo({
						url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=userinfo&current=${this.current}&comm=true`
					})
					return
				}
			},
			/// 展卡跳转荟吧页
			fnCardHuiba(e) {
				uni.navigateTo({
					url: `/pages/huiba-details/huiba-details?id=${e.user_id}`
				})
			},
			/// 展卡点赞
			fnCardTop(e) {
				let filItem = {};
				// 发布
				if (this.current == 0) filItem = this.userPublishListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				// 赞过
				if (this.current == 1) filItem = this.userTopListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				let params = {
					objecttype: filItem.ObjectType,
					objectid: filItem.dynamic_id
				}
				// 用户是否点过赞
				if (filItem.UserTop) {

				} else {
					dynamicPraise(params).then(addRes => {
						if (addRes.data.Data == false) return
						filItem.TopCount++;
						filItem.UserTop = true
					})
				}
			},
			/// 展卡收藏
			fnCardSave(e) {
				let filItem = {};
				// 发布
				if (this.current == 0) filItem = this.userPublishListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				// 赞过
				if (this.current == 1) filItem = this.userTopListData.filter(item => item.dynamic_id == e.dynamic_id)[0];
				let params = {
					objectid: filItem.dynamic_id,
					objecttype: filItem.ObjectType
				}
				// 用户是否已收藏
				if (filItem.UserSave) {

				} else {
					dynamicCollection(params).then(addRes => {
						if (addRes.data.Data == false) return
						filItem.SaveCount++;
						filItem.UserSave = true
					})
				}
			},
			//
		}
	}
</script>

<style>
	.info-cover {
		display: block;
		width: 100%;
		height: 260rpx;
	}

	.info-avatar {
		position: absolute;
		left: 0;
		top: -56rpx;
	}
</style>

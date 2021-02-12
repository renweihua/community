<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni :bottom="112" :down="{use:false}" :up="{use:false}">
			<!-- 用户名背景 -->
			<view class="bgwhite bbs2r">
				<!-- #ifdef APP-PLUS -->
				<view class="posif posi-tlr0 status-bar"></view>
				<!-- #endif -->
				<image class="my-cover" @tap="fnMainBgPic" :src="userInfoData.MainBgPic ? userInfoData.MainBgPic + '_850x300.jpg/format/webp' : '/static/default_image.png'"
				 mode="aspectFill"></image>
				<view class="posir flex hl90r mlr28r" @tap="fnUserInfo">
					<view class="my-avatar">
						<user-avatar :src="userInfoData.user_info.user_avatar ? userInfoData.user_info.user_avatar : '/static/default_avatar.png'"
						 :tag="userInfoData.AuthenticateCode" size="lg"></user-avatar>
					</view>
					<text class="my-nickname">{{userInfoData.user_info.nick_name}}</text>
				</view>
			</view>
			<!-- 粉丝关注汉币统计 -->
			<view class="flexr-jsa fcenter bgwhite ptb18r mb18r">
				<view class="flexc-jsc flex-fitem" @tap="fnOpenWin('fans-list')">
					<view class="f36r fbold c555">{{userInfoData.user_info.fans_count || 0}}</view>
					<view class="f24r cgray">粉丝</view>
				</view>
				<view class="flexc-jsc flex-fitem bls2r brs2r" @tap="fnOpenWin('atte-list')">
					<view class="f36r fbold c555">{{userInfoData.user_info.follows_count || 0}}</view>
					<view class="f24r cgray">关注</view>
				</view>
				<view class="flexc-jsc flex-fitem">
					<view class="f36r fbold c555">{{userInfoData.Hanbi || 0}}</view>
					<view class="f24r cgray">汉币</view>
				</view>
			</view>
			<!-- 项列表组 -->
			<view class="mb18r bgwhite">
				<view class="flex flex-aic" @tap="fnOpenWin('signin')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_sign.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
						<text class="f36r c111">签到</text>
						<text class="f32r fright flex-fitem mr18r" :class="[signinStatusData ? 'cgray':'ctheme']">{{signinStatusData ? '已签到':'未签到'}}</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
				<view class="flex flex-aic" @tap="fnOpenWin('my-news')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_news.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
						<text class="f36r c111 flex-gitem">我的消息</text>
						<text class="f32r fright flex-fitem cgray mr18r" v-if="newsTotalData"><text class="ctheme mr18r">{{newsTotalData}}</text>条未读</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
				<view class="flex flex-aic">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_chat.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r pr28r">
						<text class="f36r c111 flex-gitem">我的聊天</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
			</view>
			<view class="mb18r bgwhite">
				<view class="flex flex-aic" @tap="fnOpenWin('my-follow')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_follow.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
						<text class="f36r c111 flex-gitem">我的关注</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
				<view class="flex flex-aic" @tap="fnOpenWin('my-collection')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_collection.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
						<text class="f36r c111 flex-gitem">我的收藏</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
				<view class="flex flex-aic" @tap="fnOpenWin('ranking-list')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_ranking.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r pr28r">
						<text class="f36r c111 flex-gitem">排行榜</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
			</view>
			<view class="mb18r bgwhite">
				<view class="flex flex-aic" @tap="fnOpenWin('hanbi-center')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_hanbi.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
						<text class="f36r c111 flex-gitem">汉币中心</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
				<view class="flex flex-aic" @tap="fnOpenWin('setting')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_setting.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r pr28r">
						<text class="f36r c111 flex-gitem">设置</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
			</view>
		</mescroll-uni>
	</view>
</template>

<script>
	import {
		fnUploadUpyunPic
	} from "@/utils/UniUtil.js"
	import {
		modifyUserMainBgPic,
		getUserInfo
	} from "@/api/UserServer.js"
	import {
		getSigninInfo,
	} from "@/api/HanbiServer.js"
	import {
		getMessageNoReadCount,
	} from "@/api/MessageServer.js"

	export default {
		props: {
			// 连续触发刷新
			refresh: {
				type: Boolean,
				default: false
			}
		},

		watch: {
			refresh(val) {
				if (val) this.fnRefreshUserInfo();
			}
		},

		computed: {
			// 未读消息数量
			newsTotalData() {
				return this.$store.getters['getNewsTotalData']
			},
			// 签到状态
			signinStatusData() {
				return this.$store.getters['getSigninStatusData']
			},
			// 用户信息
			userInfoData() {
				let user_info = this.$store.getters['user/getUserInfoData'];
				console.log(user_info);
				return user_info;
			},
		},

		methods: {
			/// 跳转打开新窗口
			fnOpenWin(type) {
				uni.navigateTo({
					url: `/pages/${type}/${type}?id=${this.userInfoData.ID}`
				})
			},
			/// 修改用户背景封面图
			fnMainBgPic() {
				uni.showActionSheet({
					itemList: ['更换背景'],
					success: res => {
						if (res.tapIndex == 0) {
							fnUploadUpyunPic(1).then(res => {
								if (res) return res
							}).then(uploadRes => {
								if (typeof uploadRes == 'undefined') return
								modifyUserMainBgPic(uploadRes.url)
								let userInfo = Object.assign({}, this.$store.getters['user/getUserInfoData']);
								userInfo.MainBgPic = 'https://pic.hanfugou.com' + uploadRes.url;
								this.$store.commit('user/setAccountInfoMainBgPicData', userInfo.MainBgPic)
								this.$store.commit('user/setUserInfoData', userInfo)
								this.$store.commit('user/setTempUserInfoData', userInfo)
							}).catch(() => {
								uni.showToast({
									title: '上传背景失败',
									icon: 'none'
								})
							})
						}
					}
				})
			},
			/// 跳转用户信息页
			fnUserInfo() {
				uni.navigateTo({
					url: `/pages/user-info/user-info?id=${this.userInfoData.ID}`
				})
			},

			/// 刷新用户信息消息
			fnRefreshUserInfo() {
				// 获得登录用户信息
				getUserInfo(this.userInfoData.ID).then(userinfoRes => {
					// 保存登录用户信息
					this.$store.commit('user/setUserInfoData', userinfoRes.data.Data);
					// 获取未读消息数
					return getMessageNoReadCount()
				}).then(mesRes => {
					// 保存未读消息数
					this.$store.commit('setNewsCountData', mesRes.data.Data)
					// 获取签到信息
					return getSigninInfo()
				}).then(signinRes => {
					// 保存签到信息
					this.$store.commit('setSigninInfoData', signinRes.data.Data)
				})
			}
			//
		}
	}
</script>

<style>
	.my-cover {
		display: block;
		width: 100%;
		height: 260rpx;
	}

	.my-avatar {
		position: absolute;
		left: 0;
		top: -56rpx;
	}

	.my-nickname {
		color: #111111;
		font-size: 42rpx;
		margin-left: 150rpx;
	}
</style>

<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni :bottom="112" :down="{ use: false }" :up="{ use: false }">
			<!-- 用户名背景 -->
			<view class="bgwhite bbs2r">
				<!-- #ifdef APP-PLUS -->
				<view class="posif posi-tlr0 status-bar"></view>
				<!-- #endif -->
				<image
					class="my-cover"
					@tap="fnMainBgPic"
					:src="userInfoData && userInfoData.user_info ? userInfoData.user_info.background_cover : '/static/default_image.png'"
					mode="aspectFill"
				></image>
				<view class="posir flex hl90r mlr28r" @tap="fnUserInfo">
					<view class="my-avatar">
						<user-avatar :src="userInfoData && userInfoData.user_info ? userInfoData.user_info.user_avatar : '/static/default_avatar.png'" tag="" size="lg"></user-avatar>
					</view>
					<text class="my-nickname">{{ userInfoData.user_info.nick_name }}</text>
				</view>
			</view>
			<!-- 粉丝关注汉币统计 -->
			<view class="flexr-jsa fcenter bgwhite ptb18r mb18r">
				<view class="flexc-jsc flex-fitem" @tap="fnOpenWin('fans-list')">
					<view class="f36r fbold c555">{{ userInfoData.user_info.fans_count || 0 }}</view>
					<view class="f24r cgray">粉丝</view>
				</view>
				<view class="flexc-jsc flex-fitem bls2r brs2r" @tap="fnOpenWin('atte-list')">
					<view class="f36r fbold c555">{{ userInfoData.user_info.follows_count || 0 }}</view>
					<view class="f24r cgray">关注</view>
				</view>
				<view class="flexc-jsc flex-fitem">
					<view class="f36r fbold c555">{{ userInfoData.Hanbi || 0 }}</view>
					<view class="f24r cgray">积分</view>
				</view>
			</view>
			<!-- 项列表组 -->
			<view class="mb18r bgwhite">
				<view class="flex flex-aic" @tap="fnOpenWin('my', 'login-record')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_chat.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
						<text class="f36r c111 flex-gitem">登录日志</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
				<view class="flex flex-aic" @tap="fnOpenWin('signin')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_sign.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
						<text class="f36r c111">签到</text>
						<text class="f32r fright flex-fitem mr18r" :class="[signinStatusData ? 'cgray' : 'ctheme']">{{ signinStatusData ? '已签到' : '未签到' }}</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
				<view class="flex flex-aic" @tap="fnOpenWin('my-news')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_news.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
						<text class="f36r c111 flex-gitem">我的消息</text>
						<text class="f32r fright flex-fitem cgray mr18r" v-if="newsTotalData">
							<text class="ctheme mr18r">{{ newsTotalData }}</text>
							条未读
						</text>
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
				<view v-if="false" class="flex flex-aic" @tap="fnOpenWin('ranking-list')">
					<image class="hw64r plr28r" src="/static/icon-nav-my/icon_ranking.png" mode="aspectFit"></image>
					<view class="flex-fitem flex flex-aic hl90r pr28r">
						<text class="f36r c111 flex-gitem">排行榜</text>
						<i-icon type="you" size="42" color="#8F8F94"></i-icon>
					</view>
				</view>
			</view>
			<view class="mb18r bgwhite">
				<view v-if="false" class="flex flex-aic" @tap="fnOpenWin('hanbi-center')">
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
import { modifyUserMainBgPic, getLoginUserInfo } from '@/api/UserServer.js';
import { getMessageNoReadCount } from '@/api/MessageServer.js';
import { upload } from '@/api/CommonServer.js'
import { selectImage } from '@/utils/UniUtil.js';

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
			return this.$store.getters['getNewsTotalData'];
		},
		// 签到状态
		signinStatusData() {
			return this.userInfoData.user_info.is_sign;
		},
		// 用户信息
		userInfoData() {
			let login_user = this.$store.getters['user/getLoginUserInfoData'];
			if(!login_user.user_info){
				this.fnRefreshUserInfo();
				login_user = this.$store.getters['user/getLoginUserInfoData'];
			}
			return login_user;
		}
	},
	methods: {
		/// 跳转打开新窗口
		fnOpenWin(dir, file) {
			file = file == undefined ? dir : file;
			uni.navigateTo({
				url: `/pages/${dir}/${file}`
			});
		},
		chooseImage: async function() {
			uni.chooseImage({
			    sourceType: '拍照或相册',
			    sizeType: '压缩或原图',
			    count: 1,
			    success: (res) => {
					console.log(res);
					this.back_cover_file = res.tempFiles;
			    },
			    fail: (err) => {
			        // #ifdef APP-PLUS
			        if (err['code'] && err.code !== 0 && this.sourceTypeIndex === 2) {
			            this.checkPermission(err.code);
			        }
			        // #endif
			    }
			});
		},
		/// 修改用户背景封面图
		fnMainBgPic() {
			uni.showActionSheet({
				itemList: ['更换背景'],
				success: res => {
					if (res.tapIndex == 0) {
						selectImage()
							.then(file => {
								if (res){
									upload(file).then(background_cover => {
										// 更换背景图
										modifyUserMainBgPic(background_cover).then(res => {
											uni.showToast({
												title: res.msg,
												icon: status == 1 ? 'success' : 'none'
											});
											if(!res.status){
												return;
											}
											this.$store.getters['user/getLoginUserInfoData'];
											let userInfo = Object.assign({}, this.$store.getters['user/getLoginUserInfoData']);
											userInfo.user_info.background_cover = background_cover;
											this.$store.commit('user/setLoginUserInfoData', userInfo);
										});
									})
								}else{
									return false;
								}
							})
							.catch(() => {
								uni.showToast({
									title: '上传背景失败',
									icon: 'none'
								});
							});
					}
				}
			});
		},
		/// 跳转用户信息页
		fnUserInfo() {
			uni.navigateTo({
				url: `/pages/user-info-modify/user-info-modify`
			});
			// uni.navigateTo({
			// 	url: `/pages/user-info/user-info`
			// });
		},
		/// 刷新用户信息消息
		fnRefreshUserInfo() {
			// 获得登录用户信息
			getLoginUserInfo()
				.then(userinfoRes => {
					// 保存登录用户信息
					this.$store.commit('user/setLoginUserInfoData', userinfoRes.data);

					// 获取未读消息数
					return getMessageNoReadCount();
				})
				.then(mesRes => {
					// 保存未读消息数
					this.$store.commit('setNewsCountData', mesRes.data);
				});
		}
	}
};
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

<template>
	<view>
		<!-- 顶部封面 -->
		<image class="w100v mb64r" src="/static/default_header.png" mode="widthFix"></image>
		<!-- 表单 -->
		<view class="mlr64r">
			<view class="flexr-jsc flex-aic bbs2r h112r">
				<i-icon type="shouji" size="56" color="#FF6699"></i-icon>
				<input class="flex-fitem mlr18r" type="text" v-model="user_name" placeholder="请输入用户名、邮箱、手机号" :maxlength="20" />
			</view>
			<view class="flexr-jsc flex-aic bbs2r h112r">
				<i-icon type="mima" size="56" color="#FF6699"></i-icon>
				<input class="flex-fitem mlr18r" type="text" :password="isPasswd" v-model="password" placeholder="请输入密码" :maxlength="26" />
				<i-icon :type="isPasswd ? 'mimabukejian' : 'mimakejian'" size="56" color="#8f8f94" @click="isPasswd = !isPasswd"></i-icon>
			</view>
			<button class="btn-sub mt64r mb28r" hover-class="btn-hover" @tap="fnLogin" :disabled="isLogin" :loading="isLogin">登录</button>
			<view class="flexr-jsb f28r cgray">
				<view class="funderline" @tap="fnPage('forget')">忘记密码</view>
				<view>没有账号？<text class="ctheme funderline ml8r" @tap="fnPage('register')">注册</text></view>
			</view>
		</view>
		<!-- 社交账号 -->
		<view class="social">
			<view class="flex flex-aic mb28r">
				<view class="flex-fitem line-gr-ctheme"></view>
				<view class="f32r mlr18r ctheme">社交账号登录</view>
				<view class="flex-fitem line-gl-ctheme"></view>
			</view>
			<view class="flexr-jsa">
				<image class="hw96r br50v" src="/static/icon_weixin.png" mode="aspectFit" @tap="fnWechat"></image>
				<image class="hw96r br50v" src="/static/icon_qq.png" mode="aspectFit" @tap="fnQQ"></image>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		login,
		getLoginUserInfo,
		getNeteaseIMToken
	} from "@/api/UserServer.js"
	import {
		getMessageNoReadCount,
	} from "@/api/MessageServer.js"

	export default {
		data() {
			return {
				// 密码可见状态
				isPasswd: true,
				// 登录状态
				isLogin: false,
				// 用户名、邮箱、手机号
				user_name: '13077820326',
				// 密码
				password: '123456'
			};
		},
		onReady() {
			// 置空导航标题
			uni.setNavigationBarTitle({
				title: ''
			});
		},

		methods: {
			// 登录提交
			async fnLogin() {
				// 进行登录
				this.isLogin = true;
				try {
					// 登录保存用户账户信息token
					let {data, msg, status} = await login({
						'user_name': this.user_name,
						'password': this.password,
					});
					if(!status){
						uni.showToast({
							title: msg,
							icon: 'none'
						});
						return;
					}
					uni.showToast({
						title: msg,
						icon: 'success'
					});
					this.$store.commit('user/setAccountInfoData', data);
					// 存储Token
					uni.setStorageSync('TOKEN', data.access_token);
					// 保存登录用户信息
					let user_info = await getLoginUserInfo();
					this.$store.commit('user/setLoginUserInfoData', user_info.data);
					
					// 获取未读消息数
					let mesRes = await getMessageNoReadCount()
					this.$store.commit('setNewsCountData', mesRes.data);

					// 开始调整主页
					this.isLogin = false;

					// 登录之后，要么返回上一页，要么返回主页
					setTimeout(() => {
						if(uni.navigateBack()){
							
						}else{
							uni.reLaunch({
								url: '/pages/index/index?current=0'
							})
						}
					}, 1500);
				} catch (e) {
					this.isLogin = false;
				}
			},
			// 跳转页面
			fnPage(type) {
				if (this.isLogin) return
				uni.navigateTo({
					url: `/pages/${type}/${type}`
				})
			},
			// 调起微信登录
			fnWechat() {
				if (this.isLogin) return
				console.log('微信登录');
				uni.showLoading({
					title: '微信登录'
				})
				setTimeout(() => {
					uni.hideLoading()
				}, 1200);
			},
			//调起QQ登录
			fnQQ() {
				if (this.isLogin) return;
				
				uni.login({
					provider: 'qq',	//微信:wx   QQ:qq
					success: function (loginRes) {
						console.log(loginRes.authResult);
						// 获取用户信息
						uni.getUserInfo({
							provider: 'qq',	//微信:wx   QQ:qq
							success: function (infoRes) {
								console.log('用户昵称为：' + infoRes.userInfo.nickName);
							}
						});
					}
				});
				
				console.log('QQ登录');
				uni.showLoading({
					title: 'QQ登录'
				})
				setTimeout(() => {
					uni.hideLoading()
				}, 1200);
			}
		}

	}
</script>

<style>
	page {
		background: #FFFFFF;
	}

	/*社交账号区域*/
	.social {
		position: absolute;
		left: 15%;
		right: 15%;
		bottom: 5%;
	}

	/* 线条 */
	.line-gr-ctheme {
		height: 4rpx;
		min-width: 56rpx;
		background: linear-gradient(to right, rgba(255, 102, 153, 0), #ff6699);
	}

	.line-gl-ctheme {
		height: 4rpx;
		min-width: 56rpx;
		background: linear-gradient(to left, rgba(255, 102, 153, 0), #ff6699);
	}
</style>

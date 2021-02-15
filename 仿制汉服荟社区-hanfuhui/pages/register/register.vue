<template>
	<view>
		<!-- 顶部封面 -->
		<image class="w100v mb64r" src="/static/default_header.png" mode="widthFix"></image>
		<!-- 注册表单 -->
		<view class="mlr64r" v-if="!entryBind">
			<view class="flexr-jsc flex-aic bbs2r h112r">
				<i-icon type="shouji" size="48" color="#FF6699"></i-icon>
				<input class="flex-fitem mlr18r" type="text" v-model="user_name" placeholder="请输入用户名" :maxlength="20" />
			</view>
			<view class="flexr-jsc flex-aic bbs2r h112r">
				<i-icon type="mima" size="48" color="#FF6699"></i-icon>
				<input class="flex-fitem mlr18r" type="text" :password="isPasswd" v-model="password" placeholder="请输入密码" :maxlength="26" />
				<i-icon :type="isPasswd ? 'mimabukejian' : 'mimakejian'" size="48" color="#8f8f94" @click="isPasswd = !isPasswd"></i-icon>
			</view>
			<view class="flexr-jsc flex-aic bbs2r h112r">
				<i-icon type="mima" size="48" color="#FF6699"></i-icon>
				<input class="flex-fitem mlr18r" type="text" v-model="confirm_password" placeholder="请输入确认密码" :maxlength="26" />
			</view>
			<button class="btn-sub mt64r" hover-class="btn-hover" @tap="fnRegister" :disabled="isRegister" :loading="isRegister">注册</button>
		</view>

		<!-- 绑定用户表单 -->
		<view class="mlr64r anima-out-in3" v-else>
			<image class="mautoblock br50v hw200r" :src="calAvater" mode="aspectFill" @tap="fnAvatar"></image>
			<input class="mt64r mb18r hl80r bbs2r fcenter" type="text" v-model="bindUser.nick_name" placeholder="请输入昵称"
			 :maxlength="10" />
			<view class="flex hl80r">
				<view class="flex-fitem fcenter" @tap="fnGender(0)">
					<i-icon :type="bindUser.user_sex == 0 ? 'quan-dui':'quan'" size="48" :color="bindUser.user_sex == 0 ? '#FF6699':'#8f8f94'"></i-icon>
					<text class="f32r ml8r" :class="[bindUser.user_sex == 0 ? 'ctheme':'cgray']">帅汉子</text>
				</view>
				<view class="flex-fitem fcenter" @tap="fnGender(1)">
					<i-icon :type="bindUser.user_sex == 1 ? 'quan-dui':'quan'" size="48" :color="bindUser.user_sex == 1 ? '#FF6699':'#8f8f94'"></i-icon>
					<text class="f32r ml8r" :class="[bindUser.user_sex == 1 ? 'ctheme':'cgray']">萌妹子</text>
				</view>
			</view>
			<button class="btn-sub mt64r" hover-class="btn-hover" @tap="fnBindUser" :disabled="isBind" :loading="isBind">完成注册</button>
		</view>
	</view>
</template>

<script>
	import {
		fnUploadUpyunPic
	} from "@/utils/UniUtil.js"
	import {
		getUserAppToken,
		register,
		modifyUserInfo,
		getLoginUserInfo,
		getNeteaseIMToken
	} from "@/api/UserServer.js"
	import {
		getSigninInfo,
	} from "@/api/HanbiServer.js"
	import {
		getMessageNoReadCount,
	} from "@/api/MessageServer.js"

	export default {
		data() {
			return {
				// 密码可见状态
				isPasswd: true,
				// 注册状态
				isRegister: false,
				// 进入绑定
				entryBind: false,
				// 绑定状态
				isBind: false,
				// 绑定用户
				bindUser: {
					nick_name: '',
					user_avatar: '/pc/2015/12/3/21/b7a0c03d449e4110863b9f804bdf8c38.jpg',
					user_sex: '女'
				},
				// 手机号
				user_name: '',
				// 密码
				password: '',
				confirm_password: '',
			};
		},
		computed: {
			// 计算是否显示默认头像
			calAvater() {
				if (!this.bindUser.user_avatar) return '/static/default_avatar.png';
				return this.bindUser.user_avatar;
			}
		},
		methods: {
			/**
			 *  注册提交
			 */
			async fnRegister() {
				// 用户名
				if (this.user_name == '' || this.user_name < 2) {
					uni.showToast({
						title: '用户名至少2位字符！',
						icon: 'none'
					})
					return
				}
				// 密码
				if (this.password == '' || this.password < 6) {
					uni.showToast({
						title: '请输入不少于6位数密码！',
						icon: 'none'
					})
					return
				}
				if (this.password != this.confirm_password) {
					uni.showToast({
						title: '两次输入密码不一致！',
						icon: 'none'
					})
					return
				}
				// 进行注册
				this.isRegister = true;
				try {
					let {
						data,
						msg,
						status
					} = await register({
						user_name: this.user_name,
						password: this.password,
						password_confirmation: this.confirm_password,
					});
					if (!status) {
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
					this.bindUser.nick_name = data.nick_name;
					this.bindUser.user_avatar = data.user_avatar;
					this.bindUser.user_sex = data.user_sex;

					this.isRegister = false;
					this.entryBind = true;
				} catch (e) {
					this.isRegister = false;
					this.entryBind = false;
				}
			},
			/**
			 * 绑定用户
			 */
			async fnBindUser() {
				// 检查用户
				if (this.bindUser.nick_name == '' || this.bindUser.nick_name.length <= 2) {
					uni.showToast({
						title: '请输入用户昵称',
						icon: 'none'
					})
					return
				}
				// 进行绑定
				this.isBind = true;
				try {
					// 编辑个人资料
					let {
						data,
						msg,
						status
					} = await modifyUserInfo(this.bindUser);
					if (!status) {
						uni.showToast({
							title: msg,
							icon: 'none'
						});
						return;
					}
					// 保存登录用户信息
					let user_info = await getLoginUserInfo();
					console.log(user_info);
					this.$store.commit('user/setLoginUserInfoData', user_info.data);
					// // 获取未读消息数
					// let mesRes = await getMessageNoReadCount()
					// this.$store.commit('setNewsCountData', mesRes.data.Data)
					// // 获取签到信息
					// let signinRes = await getSigninInfo()
					// this.$store.commit('setSigninInfoData', signinRes.data.Data)

					// 开始跳转主页
					this.isBind = false;
					uni.reLaunch({
						url: '/pages/index/index?current=0'
					})
				} catch (e) {
					this.isBind = false;
				}
			},
			/**
			 * 上传头像
			 */
			fnAvatar() {
				if (this.isBind) return
				fnUploadUpyunPic(1).then(res => {
					if (res) this.bindUser.user_avatar = res.url
				})
			},
			// 性别选择
			fnGender(user_sex) {
				if (this.isBind) return
				this.bindUser.user_sex = user_sex;
			}
		}

	}
</script>

<style>
	page {
		background: #FFFFFF;
	}
</style>

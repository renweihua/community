<template>
	<view>
		<!-- 头像 -->
		<image class="edit-avatar" :src="calInfoAvatar" mode="aspectFill" @tap="fnAvatar"></image>
		<view class="mlr64r">
			<!-- 昵称 -->
			<view class="flexr-jsc flex-aic bbs2r h112r">
				<view class="f36r fbold c111">昵称</view>
				<input class="flex-fitem ml28r f32r c555" type="text" v-model="info.nick_name" placeholder="请输入昵称" :maxlength="10" />
			</view>
			<!-- 性别 -->
			<view class="flexr-jsc flex-aic bbs2r h112r">
				<view class="f36r fbold c111">性别</view>
				<view class="flex-fitem ml28r flex hl80r">
					<view class="pr28r mr28r" @tap="fnGender(1)">
						<i-icon :type="info.user_sex == 1 ? 'quan-dui' : 'quan'" size="42" :color="info.user_sex == 1 ? '#FF6699' : '#555555'"></i-icon>
						<text class="f32r ml8r" :class="[info.user_sex == 1 ? 'ctheme' : 'c555']">帅汉子</text>
					</view>
					<view class="pr28r" @tap="fnGender(2)">
						<i-icon :type="info.user_sex == 2 ? 'quan-dui' : 'quan'" size="42" :color="info.user_sex == 2 ? '#FF6699' : '#555555'"></i-icon>
						<text class="f32r ml8r" :class="[info.user_sex == 2 ? 'ctheme' : 'c555']">萌妹子</text>
					</view>
				</view>
			</view>
			<!-- 简介 -->
			<view class="flexr-jsc flex-aic bbs2r edit-hm112r" v-if="isShowArea">
				<view class="f36r fbold c111">简介</view>
				<textarea
					class="flex-fitem mlr28r ptb8r f32r c555 edit-hmo250r z5"
					placeholder="请输入简介"
					placeholder-style="font-size: 14px;color:#8F8F94"
					auto-height
					v-model="info.user_introduction"
				></textarea>
			</view>
		</view>
		<!-- 提交按钮 -->
		<button class="btn-sub mt64r mlr64r" hover-class="btn-hover" :disabled="isModify" :loading="isModify" @tap="fnModify">提交修改</button>
	</view>
</template>

<script>
import { modifyUserInfo } from '@/api/UserServer.js';
import { upload } from '@/api/CommonServer.js'
import { selectImage } from '@/utils/UniUtil.js';

export default {
	components: {},
	data() {
		return {
			// 用户信息
			info: {
				user_introduction: '',
				user_sex: '',
				user_avatar: '',
				nick_name: ''
			},
			// 用户修改副本
			tempInfo: {},
			// 提交修改
			isModify: false,
			// 计算显隐原生输入框
			isShowArea: true
		};
	},
	computed: {
		// 计算头像信息显示
		calInfoAvatar() {
			if (this.info.user_avatar == '') return '/static/default_avatar.png';
			return this.info.user_avatar;
		}
	},
	onLoad(option) {
		// 获取登录账户用户信息
		let login_user = this.$store.getters['user/getLoginUserInfoData'].user_info;
		this.info = {
			user_introduction: typeof login_user.user_introduction == 'string' ? login_user.user_introduction : '该同袍还不知道怎么描述寄己 (╯▽╰)╭',
			user_sex: login_user.user_sex,
			user_avatar: login_user.user_avatar,
			nick_name: login_user.nick_name
		};
		// 保存修改副本
		this.tempInfo = Object.assign({}, this.info);
	},
	methods: {
		/// 提交修改
		fnModify() {
			this.isModify = true;
			let paramArray = [];
			let { cityid, nick_name, user_sex, user_avatar, user_introduction } = this.info;
			// 用户信息
			let userInfo = Object.assign({}, this.$store.getters['user/getLoginUserInfoData']);
			// 修改昵称
			if (nick_name != this.tempInfo.nick_name) {
				paramArray.push({
					option: '2',
					value: nick_name
				});
			}
			// 修改性别
			if (user_sex != this.tempInfo.user_sex) {
				paramArray.push({
					option: '3',
					value: user_sex
				});
			}
			// 修改头像
			if (user_avatar != this.tempInfo.user_avatar) {
				paramArray.push({
					option: '1',
					value: user_avatar
				});
			}
			// 修改简介
			if (user_introduction != this.tempInfo.user_introduction) {
				paramArray.push({
					option: '5',
					value: user_introduction
				});
			}
			// 空修改
			if (paramArray.length == 0) {
				uni.showToast({
					title: '还没修改任何一项呢',
					icon: 'none'
				});
				this.isModify = false;
				return;
			}
			// 提价修改
			try {
				// 遍历发送修改值
				paramArray.map(item => {
					modifyUserInfo(this.info).then(res => {
						uni.showToast({
							title: res.msg,
							icon: res.status ? 'success' : 'none'
						});
						
						if(res.status){
							userInfo.user_info.nick_name = this.info.nick_name;
							userInfo.user_info.user_sex = this.info.user_sex;
							userInfo.user_info.user_avatar = this.info.user_avatar;
							userInfo.user_info.user_introduction = this.info.user_introduction;
						}else{
							return;
						}
					});
				});
				// 保存用户信息
				this.$store.commit('user/setLoginUserInfoData', userInfo);
				this.isModify = false;
				setTimeout(() => {
					uni.navigateBack();
				}, 1800);
			} catch (e) {
				this.isModify = false;
			}
		},
		//上传头像
		fnAvatar() {
			if (this.isModify) return;
			selectImage()
				.then(file => {
					upload(file).then(file_url => {
						this.info.user_avatar = file_url;
					});
				});
		},
		// 性别选择
		fnGender(user_sex) {
			if (this.isModify) return;
			this.info.user_sex = user_sex;
		}
	}
};
</script>

<style>
page {
	background: #ffffff;
}

.edit-avatar {
	width: 228rpx;
	height: 228rpx;
	border-radius: 50%;
	overflow: hidden;
	display: block;
	box-shadow: 0 8rpx 16rpx -8rpx #ff6699;
	margin: 32px auto;
}

.edit-hm112r {
	min-height: 112rpx;
}

.edit-hmo250r {
	max-height: 250rpx;
	overflow: hidden;
}
</style>

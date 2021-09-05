<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni :down="{use:false}" :up="{use:false}">
			<view class="mb18r bgwhite plr28r">
				<view class="flex flex-aic hl90r bbs2r" @tap="fnOpenWin('account-security')">
					<text class="f36r c111 flex-gitem">账户与安全</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
				<view class="flex flex-aic hl90r bbs2r" @tap="fnOpenWin('black-list')">
					<text class="f36r c111 flex-gitem">黑名单</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
				<view class="flex flex-aic hl90r">
					<text class="f36r c111 flex-gitem" @tap="fnOpenWin('privacy-manage')">隐私管理</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
			</view>
			<view class="mb18r bgwhite plr28r">
				<view class="flex flex-aic hl90r bbs2r" @tap="fnOpenWin('punish-list')">
					<text class="f36r c111 flex-gitem">处罚公示</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
				<view class="flex flex-aic hl90r bbs2r">
					<text class="f36r c111 flex-gitem" @tap="fnCustomer">小荟客服</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
				<view class="flex flex-aic hl90r">
					<text class="f36r c111 flex-gitem" @tap="fnOpenWin('about-us')">关于我们</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
			</view>
			<view class="mb18r bgwhite plr28r" v-if="_isH5">
				<view class="flex flex-aic hl90r bbs2r" @tap="openURL('https://bbs-h5.cnpscy.com')">
					<text class="f36r c111 flex-gitem">小丑路人社区H5端</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
				<view class="flex flex-aic hl90r bbs2r" @tap="openURL('https://bbs.cnpscy.com')">
					<text class="f36r c111 flex-gitem">小丑路人社区PC端</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
				<view class="flex flex-aic hl90r" @tap="openURL('https://www.cnpscy.com')">
					<text class="f36r c111 flex-gitem">小丑路人博客</text>
					<i-icon type="you" size="42" color="#8F8F94"></i-icon>
				</view>
			</view>

				
			<view class="hl90r mlr28r mt64r f36r fcenter cwhite bgtheme br8r" @tap="fnLogout">退出登录</view>
		</mescroll-uni>
	</view>
</template>

<script>
	import {
		logout,
	} from "@/api/UserServer.js"

	export default {
		data() {
			return {
				// 登录用户ID
				id: 0,
				// 
				_isH5: false
			}
		},
		onLoad(options) {
			if (options && options.id) {
				this.id = parseInt(options.id);
			}
			// #ifdef H5
			this._isH5 = true;
			// #endif
		},
		methods: {
			/// 跳转打开新窗口
			fnOpenWin(type) {
				uni.navigateTo({
					url: `/pages/${type}/${type}?id=${this.id}`
				})
			},
			openURL(href) {
				// #ifdef APP-PLUS
				plus.runtime.openURL(href);
				// #endif
				// #ifdef H5
				window.open(href)
				// #endif
				// #ifdef MP
				uni.setClipboardData({
					data: href
				});
				uni.showModal({
					content: '已自动复制网址，请在手机浏览器里粘贴该网址',
					showCancel: false
				});
				// #endif
			},
			/// 跳转小荟客服聊天页
			fnCustomer() {
				console.log('联系客服');
			},
			/// 退出登录
			fnLogout() {
				logout().then(res => {
					uni.showLoading({
						title: '退出登录',
						mask: true
					})
					setTimeout(() => {
						uni.removeStorageSync('TOKEN');
						uni.hideLoading()
						// 跳转登录
						uni.reLaunch({
							url: '/pages/login/login'
						})
					}, 1500);
				});
			}
		}
	}
</script>

<style>
</style>

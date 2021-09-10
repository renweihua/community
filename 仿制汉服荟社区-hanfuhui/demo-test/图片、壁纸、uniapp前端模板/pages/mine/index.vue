<template>
	<view>
		<u-navbar title="我的" :is-back="false" titleColor="#fff" :background="background"></u-navbar>
		<view class="u-flex user-box u-p-30">
			<view class="u-m-r-10" @click="$common.navigateTo('/pages/login/login')">
				<u-avatar src="http://img.wxcha.com/file/201902/25/c4c6d56dc2.jpg" size="140"></u-avatar>
			</view>
			<view class="u-flex-1">
				<view class="u-font-18 u-p-b-20">Ipic</view>
				<view class="u-font-14 u-tips-color">ID：100001</view>
			</view>
			<view class="u-m-l-10 u-p-10" @click="scan()">
				<u-icon name="scan" color="#969799" size="28" @click="scan"></u-icon>
			</view>
			<view class="u-m-l-10">
				<u-icon name="arrow-right" color="#969799" size="28"></u-icon>
			</view>
		</view>

		<view class="u-m-t-20">
			<u-cell-group>
				<u-cell-item icon="bell" :iconStyle="iconStyle" title="消息" @click="toUrl('/pages/mine/message')"></u-cell-item>
				<u-cell-item icon="heart" :iconStyle="iconStyle" title="收藏" @click="toUrl('/pages/mine/heart')"></u-cell-item>
				<u-cell-item icon="order" :iconStyle="iconStyle" title="反馈" @click="toUrl('/pages/mine/feedback')"></u-cell-item>
				<!-- open-type="contact" 微信小程序的客服组件 -->
				<button open-type="contact" class="class-none">
					<u-cell-item :iconStyle="iconStyle" icon="server-fill" title="客服"></u-cell-item>
				</button>
			</u-cell-group>
		</view>

		<view class="u-m-t-20">
			<u-cell-group>
				<u-cell-item :iconStyle="iconStyle" icon="setting" title="设置" @click="toUrl('/pages/mine/setting')"></u-cell-item>
			</u-cell-group>
		</view>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				background: this.TopColor,
				iconStyle:{color:'#FC0099'}
			}
		},
		onLoad() {

		},
		methods: {

			toUrl(url) {
				this.$common.navigateTo(url)
			},
			// 扫码不支持H5
			scan() {
				// #ifdef H5
				this.$common.errorToShow('H5不支持扫码');
				// #endif
				// #ifdef APP-PLUS || MP
				uni.scanCode({
					success: function(res) {
						console.log('条码类型：' + res.scanType);
						console.log('条码内容：' + res.result);
					}
				});
				// #endif
			},
		}
	}
</script>

<style lang="scss">
	page {
		background-color: #ededed;
	}

	.pupbox {
		height: 320rpx;
		background-image: linear-gradient(#2BC3C8, #84E7B9);
		
		.box-icon{
			padding: 60rpx 30rpx 10rpx;
		}
	}

	.camera {
		width: 54px;
		height: 44px;

		&:active {
			background-color: #ededed;
		}
	}

	.user-box {
		background-color: #fff;
	}

	.class-none {
		border: none;
		margin: 0;
		padding: 0;
		background: #FFFFFF;
		outline: none;
	}

	.class-none::after {
		outline: none;
		border: none;
	}
</style>

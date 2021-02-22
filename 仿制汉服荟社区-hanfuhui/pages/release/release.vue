<template>
	<view class="posia posi-all0 posi-lr0 z999">
		<view class="posif posi-all0 posi-lr0 anima-punch-in3 bgwhite-a98"></view>
		<view class="release-popup">
			<view class="list-flex3">
				<view class="item flex-33v">
					<image class="item-icon" src="/static/icon-nav-release/send_daily_icon.png" mode="aspectFit" @tap="push(0)"></image>
					<view class="item-name">发日常</view>
				</view>
				<view class="item flex-33v">
					<image class="item-icon" src="/static/icon-nav-release/send_photography_icon.png" mode="aspectFit" @tap="push(1)"></image>
					<view class="item-name">发文章</view>
				</view>
				<view class="item flex-33v">
					<image class="item-icon" src="/static/icon-nav-release/send_video_icon.png" mode="aspectFit" @tap="push(2)"></image>
					<view class="item-name">发视频</view>
				</view>
			</view>
			<view class="list-flex3">
				<view class="item flex-50v">
					<image class="item-icon" src="/static/icon-nav-release/send_topic_icon.png" mode="aspectFit"></image>
					<view class="item-name">发话题</view>
				</view>
				<view class="item flex-50v">
					<image class="item-icon" src="/static/icon-nav-release/send_photography_icon.png" mode="aspectFit" @tap="push(3)"></image>
					<view class="item-name">发摄影</view>
				</view>
			</view>
			<image class="close" src="/static/icon-nav-release/icon_close.png" mode="aspectFit" @tap="$emit('close',false)"></image>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			/**
			 * 选中值
			 */
			status: {
				type: Object,
				default: {
					home: false,
					find: false,
					shop: false,
					my: false,
					release: false,
					start: true
				}
			},
		},
		onReady() {
			console.log('release');
		},
		data() {
			return {}
		},
		methods: {
			/// 跳转规则页
			push(dynamic_type) {
				this.status.release = false;
				if(dynamic_type == 2){ // 视频
					uni.navigateTo({
						url: '/pages/dynamic/push-video?dynamic_type='+dynamic_type
					});
				}else{
					uni.navigateTo({
						url: '/pages/dynamic/push?dynamic_type='+dynamic_type
					});
				}
			}
		}
	}
</script>

<style>
	.release-popup {
		position: fixed;
		right: 0;
		left: 0;
		bottom: 24rpx;
	}

	.item {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	.item-icon {
		width: 112rpx;
		height: 112rpx;
		border-radius: 50%;
	}

	.item-name {
		font-size: 28rpx;
		color: #8f8f94;
		margin-top: 12rpx;
	}

	.list-flex3 {
		display: flex;
		width: 80%;
		margin: 0 auto;
		padding-bottom: 56rpx;
	}

	.list-flex3 .item:nth-child(1) {
		animation: fly-in .4s;
		animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
	}

	.list-flex3 .item:nth-child(2) {
		animation: fly-in .6s;
		animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
	}

	.list-flex3 .item:nth-child(3) {
		animation: fly-in .8s;
		animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
	}

	.list-flex2 {
		display: flex;
		width: 60%;
		margin: 0 auto;
		padding-bottom: 112rpx;
	}

	.list-flex2 .item:nth-child(1) {
		animation: fly-in .4s;
		animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
	}

	.list-flex2 .item:nth-child(2) {
		animation: fly-in .6s;
		animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
	}

	.close {
		display: block;
		margin: 0 auto;
		width: 56rpx;
		height: 56rpx;
		animation: fly-in .6s;
		animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
	}
</style>

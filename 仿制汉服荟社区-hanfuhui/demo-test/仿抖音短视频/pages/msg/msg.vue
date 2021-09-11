<template>
	<view class="page" :style="{ height: height,width:width }">
		<view class="nav">
			<navigator>
				<image src=""></image>
				<text>粉丝</text>
			</navigator>
			<navigator>
				<image src=""></image>
				<text>点赞</text>
			</navigator>
			<navigator>
				<image src=""></image>
				<text>评论</text>
			</navigator>
			<navigator>
				<image src=""></image>
				<text>关注</text>
			</navigator>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				height:'',
				width:'',
			};
		},
		onLoad() {
			var res = uni.getSystemInfoSync();
			this.sysheight = res.windowHeight
			this.height = `${this.sysheight}px` 
			let width = res.windowWidth
			this.width = `${width}px` 
			console.log(res);
		},
		methods: {
			
		}
	}
</script>

<style>
.page {
	background-color: #161922;
}
.nav{
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	padding: 50rpx;
}
.nav navigator{
	display: flex;
	flex-direction: column;
}
.nav navigator text{
	color: #FFFFFF;
	text-align: center;
}
.nav navigator image{
	width: 50rpx;
	height: 50rpx;
}
</style>

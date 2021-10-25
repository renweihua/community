<template>
	<view class="page" :style="{ height: height,width:width }">
		
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
		this.sysheight = res.windowHeight;
		this.height = `${this.sysheight}px`;
		let width = res.windowWidth;
		this.width = `${width}px`;
		console.log(res);
	},
	methods: {
		
	}
};
</script>

<style>
.page {
	background-color: #161922;
}
</style>

<template>
	<view>
		<u-waterfall v-model="list" ref="uWaterfall">
			<template v-slot:left="{leftList}">
				<view class="demo-warter" v-for="(item, index) in leftList" :key="index" @click="detail(item.image)">
					<!-- 警告：微信小程序中需要hx2.8.11版本才支持在template中结合其他组件，比如下方的lazy-load组件 -->
					<u-lazy-load threshold="50" :duration="duration" border-radius="10" :image="item.image" :index="index"></u-lazy-load>
					<view class="demo-shop">
						{{item.name}}
					</view>
				</view>
			</template>
			<template v-slot:right="{rightList}">
				<view class="demo-warter" v-for="(item, index) in rightList" :key="index" @click="detail(item.image)">
					<u-lazy-load threshold="50" :duration="duration" border-radius="10" :image="item.image" :index="index"></u-lazy-load>
					<view class="demo-shop u-line-2">
						{{item.name}}
					</view>
				</view>
			</template>
		</u-waterfall>
	</view>
</template>

<script>
	export default {
		name:'list',
		props:{
			list:{
				type: Array,
				default: function() {
					return [];
				}
			}
		},
		data() {
			return {
				duration:500
			}
		},
		
		methods: {
			detail(img){
				this.$common.navigateTo('/pages/index/detail?img='+img)
			},
		}
	}
</script>

<style lang="scss">
	.demo-warter {
			border-radius: 8px;
			margin: 5px;
			background-color: #ffffff;
			padding: 8px;
			position: relative;
		}
		
		.demo-image {
			width: 100%;
			border-radius: 4px;
		}
		
		.demo-title {
			font-size: 30rpx;
			margin-top: 5px;
			color: $u-main-color;
		}
		
		.demo-price {
			font-size: 30rpx;
			color: $u-type-error;
			margin-top: 5px;
		}
		
		.demo-shop {
			font-size: 22rpx;
			color: $u-tips-color;
			margin-top: 5px;
		}
</style>

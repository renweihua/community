<template>
	<view>
		<u-navbar back-text="搜索" :backTextStyle="backTextColor" backIconColor="#fff" :background="background"></u-navbar>
		<view class="home">
			<u-search placeholder="请输入关键词搜索" v-model="keyword" :actionStyle="actionStyle"></u-search>
			<view>
				<view class="u-m-t-20">
					<view class="u-flex u-row-between">
						<view class="title">历史记录</view>
						<view><u-icon name="trash" color="#FC0099" size="32" @click="delsearch"></u-icon></view>
					</view>
				</view>
				<view class="u-p-t-20">
					<u-tag type="info" shape="circle" text="二次元" v-for="(item,index) in 10" :key="index" class="tag"></u-tag>
				</view>
			</view>
			<view>
				<view class="u-m-t-30">
					<view class="u-flex u-row-between">
						<view class="title">热门搜索</view>
						<view><u-icon name="eye" color="#FC0099" size="32"></u-icon></view>
					</view>
				</view>
				<view class="u-p-t-20">
					<u-tag type="info" shape="circle" text="二次元" v-for="(item,index) in 10" :key="index" class="tag"></u-tag>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				background: this.TopColor,
				keyword:'',
				backTextColor: {
					'color': '#ffffff'
				},
				actionStyle:{
					'color':'#FC0099',
				}
			}
		},
		onLoad() {
			
		},
		methods:{
			delsearch(){
				this.$common.successToShow('清除成功');
			}
		}
	}
</script>

<style lang="scss" scoped>
	.title{
		font-size: 32rpx;
		font-weight: bold;
		color: #FC0099;
	}
	.tag{
		margin-right: 20rpx;
		margin-bottom: 20rpx;
	}
</style>

<template>
	<view>
	<u-navbar title="消息" backIconColor="#fff" titleColor="#fff" :background="background"></u-navbar>
	<view class="home">
		<view v-for="(item,index) in 10" :key="index" class="u-p-b-20">
			<view class="time">2021-06-28</view>
			<view class="box">
				<view class="box-title">剑未佩妥，出门已是江湖</view>
				<view class="box-content u-tips-color">瓶身描绘的牡丹一如你初妆，冉冉檀香透过窗心事我了然，宣纸上走笔至此搁一半瓶身描绘的牡丹一如你初妆，瓶身描绘的牡丹一如你初妆，冉冉檀香透过窗心事我了然</view>
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
				messageList:[]
			}
		},
		onLoad() {
		},
		methods:{
			
		}
	}
</script>

<style lang="scss" scoped>
	page{
		background-color: #ededed;
	}
	.time{
		text-align: center;
		padding: 30rpx 0 20rpx 0;
	}
	.box{
		padding: 30rpx;
		background-color: #fff;
		border-radius: 6rpx;
		.box-title{
			text-align: left;
			font-size: 32rpx;
			font-weight: 700;
		}
		.box-content{
			margin-top: 10rpx;
			font-size: 28rpx;
		}
	}
</style>

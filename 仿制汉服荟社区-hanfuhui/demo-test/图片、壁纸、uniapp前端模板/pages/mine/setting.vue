<template>
	<view>
	<u-navbar title="设置" backIconColor="#fff" titleColor="#fff" :background="background"></u-navbar>
	<view class="">
		<u-cell-group>
				<u-cell-item title="头像" valueByImg="http://img.wxcha.com/file/201902/25/c4c6d56dc2.jpg"></u-cell-item>
				<u-cell-item title="昵称" value="Ipic"></u-cell-item>
				<u-cell-item title="性别" value="男"></u-cell-item>
				<u-cell-item title="生日" value="2021-07-01"></u-cell-item>
				<u-cell-item title="格言" value="剑未佩妥,出门已是江湖"></u-cell-item>
			</u-cell-group>
			<view class="u-m-t-20">
				<u-cell-group>
					<u-cell-item title="手机号" value="(未绑定)"></u-cell-item>
				</u-cell-group>
			</view>
	</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				background:this.TopColor,
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
</style>

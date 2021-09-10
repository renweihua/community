<template>
	<view>
	<u-navbar back-text="登录" :customBack="back" backIconColor="#fff" :backTextStyle="{'color':'#fff'}" :background="background"></u-navbar>
	<view class="content">
		<view class="title">欢迎登录App</view>
		<u-input type="number" class="u-border-bottom u-m-b-20" v-model="mobile" placeholder="请输入手机号" />
		<u-input type="password" class="u-border-bottom" v-model="password" placeholder="请输入密码" />
		<button @click="submit" :style="[inputStyle]" class="getCaptcha">登录</button>
	</view>
	<view class="buttom">
		<view class="loginType">
			<view class="wechat item">
				<view class="icon"><u-icon size="70" name="weixin-fill" color="rgb(83,194,64)"></u-icon></view>
				微信
			</view>
			<view class="QQ item">
				<view class="icon"><u-icon size="70" name="qq-fill" color="rgb(17,183,233)"></u-icon></view>
				QQ
			</view>
		</view>
		<view class="hint">
			登录代表同意
			<text class="link">用户协议、隐私政策，</text>
			并授权账号信息
		</view>
	</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				background: this.TopColor,
				mobile:'',
				password:''
			}
		},
		onLoad() {
			
		},
		computed: {
			inputStyle() {
				let style = {};
				if(this.mobile) {
					style.color = "#fff";
					style.backgroundColor = '#FC0099';
				}
				return style;
			}
		},
		methods:{
			back(){
				this.$common.switchTabTo('/pages/index/index');
			},
			submit(){
				if(!this.mobile){
					return false;
				}
				if(!this.$u.test.mobile(this.mobile)) {
					return this.$common.errorToShow('手机号码错误');
				}
				if(!this.password){
					return this.$common.errorToShow('请输入账号密码');
				}
				this.$common.successToShow('登录成功')
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		margin: 80rpx 60rpx 30rpx 60rpx;
	
		.title {
			text-align: left;
			font-size: 60rpx;
			font-weight: 500;
			margin-bottom: 100rpx;
		}
	
		.getCaptcha {
			background-color: #F8D7C7;
			color: #FFFFFF;
			border: none;
			font-size: 30rpx;
			margin: 60rpx 0;
			
			&::after {
				border: none;
			}
		}
		.alternative {
			color: $u-tips-color;
			display: flex;
			justify-content: space-between;
			margin-top: 30rpx;
		}
	}
	.buttom {
		.loginType {
			display: flex;
			padding: 300rpx 150rpx 150rpx 150rpx;
			justify-content:space-between;
			
			.item {
				display: flex;
				flex-direction: column;
				align-items: center;
				color: $u-content-color;
				font-size: 28rpx;
			}
		}
		
		.hint {
			padding: 20rpx 40rpx;
			font-size: 20rpx;
			color: $u-tips-color;
			text-align: center;
			.link {
				color: $u-type-warning;
			}
		}
	}
</style>

<template>
	<view>
		<u-navbar title="反馈" backIconColor="#fff" titleColor="#fff" :background="background"></u-navbar>
		<view class="home">
			<view class="u-p-t-20 u-p-b-20">
				<u-notice-bar mode="horizontal" :volumeIcon="false" :list="list"></u-notice-bar>
			</view>
			<u-gap bgColor="#f7f7f7" height="2"></u-gap>
			<u-form :model="form" ref="uForm">
				<u-form-item>
					<u-input type="textarea" v-model="form.content" placeholder="请输入您的意见或者建议" />
				</u-form-item>
				<u-form-item>
					<u-input v-model="form.link" placeholder="请输入您的联系方式" />
				</u-form-item>
			</u-form>
			<u-button @click="submit" class="button">提交</u-button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list: [
					'欢迎反馈意见或建议',
					'您的反馈就是我们的动力',
					'加油，奥里给！！！'
				],
				background: this.TopColor,
				closeAble: true,
				show: true,
				form: {
					content: '',
					link: ''
				}
			}
		},
		onLoad() {

		},
		methods: {
			// 提交
			submit() {
				if (!this.form.content) {
					return this.$common.errorToShow('请输入反馈内容');
				}
				this.$common.successToShow('提交成功');
			},
			close() {
				this.show = !this.show
			}
		}
	}
</script>

<style lang="scss" scoped>
	.button {
		background-image: linear-gradient(45deg, #FC0099, #FAA175);
		margin-top: 60rpx;
		color: #FFFFFF;
	}

	.desctip {
		letter-spacing: 2rpx;
	}
</style>

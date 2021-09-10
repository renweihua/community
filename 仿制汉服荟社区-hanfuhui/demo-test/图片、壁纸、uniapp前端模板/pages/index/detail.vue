<template>
	<view>
		<u-navbar back-text="返回" backIconColor="#fff" :backTextStyle="backTextColor" :background="background">
		</u-navbar>
		<view class="u-p-30">
			<u-image :src="image" @click="click" :height="height" borderRadius="12"></u-image>
		</view>
		<view class="u-flex u-row-center u-p-t-30">
			<u-icon :name="showHeart?'heart-fill':'heart'" @click="showHeart = !showHeart" label="收藏" size="40" color="#FC0099"></u-icon>
			<u-icon name="rmb-circle" label="打赏" size="40" color="#FC0099" class="u-p-l-60" @click="rmbClick"></u-icon>
			<u-icon name="zhuanfa" label="分享" size="40" color="#FC0099" class="u-p-l-60"></u-icon>
		</view>
		<!-- rmb -->
		<u-popup v-model="showRmb" length="18%" mode="bottom">
			<view>
				<u-grid :col="3" :border="false">
					<u-grid-item v-for="(item,index) in 6" :key="index">
						<u-icon name="rmb-circle" color="#FF0F00" labelColor="#FF0F00" :size="50" :label="'打赏￥'+(index+1)+'0'"></u-icon>
					</u-grid-item>	
				</u-grid>
			</view>
		</u-popup>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				background: this.TopColor,
				backTextColor: {
					'color': '#ffffff'
				},
				messageList: [],
				image: '',
				height: 1000,
				showRmb: false,
				showHeart:false
			}
		},
		onLoad(e) {
			this.height = uni.getSystemInfoSync().windowHeight * 1.5;
			if (e.img) {
				this.image = e.img;
			}
		},
		methods: {
			click() {
				this.$common.previewImage(0, [this.image]);
			},
			rmbClick() {
				this.showRmb = !this.showRmb
			}
		}
	}
</script>

<style lang="scss">
</style>

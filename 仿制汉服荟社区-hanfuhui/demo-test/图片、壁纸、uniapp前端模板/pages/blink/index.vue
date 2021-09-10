<template>
	<view>
		<u-navbar back-text="看图" :customBack="back" backIconName=" " :backTextStyle="backTextColor"
			backIconColor="#39CCCC" :background="background">
			<view class="search-wrap" @click="toSearch">
				<u-search height="56" :showAction="false"></u-search>
			</view>
		</u-navbar>
		<view class="swiper">
			<u-swiper :list="list" mode=" " @click="swiperClick" :current="current" @change="change" :autoplay="false"
				borderRadius="12" imgMode="aspectFill" :height="height"></u-swiper>
		</view>
		<!-- <view class="u-p-l-30 u-p-r-30">
			<u-button @click="appShare"><u-icon name="zhuanfa" size="36" class="u-m-r-10"></u-icon>分享</u-button>
		</view> -->
		<u-mask :show="show" @click="show = false">
			<view class="warp">			
				<u-icon name="arrow-left-double" color="#fff" size="40" label="右滑浏览更多内容..." labelSize="24" labelColor="#fff"></u-icon>
			</view>
		</u-mask>
		<view class="u-flex u-row-center">
			<u-icon :name="showHeart?'heart-fill':'heart'" @click="showHeart = !showHeart" label="收藏" size="40" color="#FC0099"></u-icon>
			<u-icon name="rmb-circle" label="打赏" size="40" color="#FC0099" class="u-p-l-60" @click="showRmb = !showRmb"></u-icon>
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
				showRmb: false,
				showHeart:false,
				// 显示遮罩
				show: true,
				list: [{
					image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[483].jpg',
				}],
				height: '',
				background: this.TopColor,
				backTextColor: {
					'color': '#ffffff'
				},
				current: 0
			}
		},
		onLoad() {
			this.height = uni.getSystemInfoSync().windowHeight * 1.6;
			this.apiblibk()
		},
		methods: {
			apiblibk() {
				this.$u.get('https://api.vvhan.com/api/acgimg', {
					type: 'json'
				}).then(res => {
					this.list.push({
						image: res.imgurl
					})
				})
			},
			back() { // 首页
				uni.navigateBack({
					delta: 2
				})
			},
			swiperClick(e) {
				let url = this.list[e].image;
				//console.log(url)
				this.$common.previewImage(0, [url]);
			},
			// 简单写一下滑动加载图片
			change(e) {
				if (e > 0) {
					let leng = this.list.length - 1;
					//console.log(leng)
					//console.log(e)
					if (e == leng) {
						this.apiblibk()
					}
				}
			},
			toSearch() {
				this.$common.navigateTo('/pages/index/search')
			},
			appShare() {
				console.log('分享')
			}
		}
	}
</script>

<style lang="scss">
	.swiper {
		padding: 30rpx;
	}

	.search-wrap {
		margin: 0 30rpx 0 10rpx;
		flex: 1;
	}

	.warp {
		display: flex;
		float: right;
		align-items: center;
		justify-content: center;
		height: 100%;
		margin-right: 100rpx;
	}
</style>

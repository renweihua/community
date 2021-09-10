<template>
	<view>
		<u-navbar back-text="首页" :customBack="back" backIconName=" " :backTextStyle="backTextColor"
			backIconColor="#39CCCC" :background="background">
			<view class="search-wrap" @click="toSearch">
				<u-search height="56" :showAction="false"></u-search>
			</view>
		</u-navbar>
		<!-- swiper -->
		<view class="home u-p-b-0">
			<u-swiper :list="swiperlist"></u-swiper>
			<view class="u-p-t-20">
				<u-grid :col="4" :border="false">
					<u-grid-item v-for="(item,index) in categoryList" :key="index" @click="toList(item.url)">
						<u-image :src="item.image" width="100" height="100"></u-image>
						<view class="grid-text">{{item.name}}</view>
					</u-grid-item>
				</u-grid>
			</view>
			<!-- 大道至简 所以我把下面的注释了 -->
			<!-- news -->
			<view class="u-p-t-20">
				<view class="u-flex u-row-between">
					<view class="title">热门推荐</view>
					<u-icon name="arrow-right" size="24" color="#39CCCC" labelPos="left" marginRight="10" label="全部"
						labelSize="24" labelColor="#39CCCC"></u-icon>
				</view>
				<view v-for="(item,index) in 2" :key="index" class="u-flex u-row-between u-m-b-20 u-m-t-10">
					<u-image width="48.5%" height="165" borderRadius="12"
						src="http://img.wxcha.com/file/201902/25/9625e1758b.jpg">
					</u-image>
					<u-image width="48.5%" height="165" borderRadius="12"
						src="http://img.wxcha.com/file/201902/25/9625e1758b.jpg">
					</u-image>
				</view>
			</view>
		</view>
		<!-- list 瀑布流-->
		<view class="f7">
			<list :list="list"></list>
		</view>
		<!-- 回到顶部按钮 -->
		<view>
			<u-back-top :scrollTop="scrollTop" :mode="modeTop" :bottom="bottomTop" :right="rightTop" :top="top"
				:icon="iconTop" :custom-style="customStyle" :icon-style="iconStyleTop" :tips="tips">
			</u-back-top>
		</view>
	</view>
</template>

<script>
	import list from '@/components/list';
	export default {
		components: {
			list
		},
		data() {
			return {
				// top
				scrollTop: 0,
				modeTop: 'circle',
				bottomTop: 200,
				rightTop: 40,
				top: 400,
				iconTop: 'arrow-up',
				iconStyleTop: {
					color: '#111',
					fontSize: '36rpx'
				},
				customStyle: {
					backgroundColor: '#fff',
					color: '#111'
				},
				tips: '顶部',
				page: 1,
				background: this.TopColor,
				backTextColor: {
					'color': '#ffffff'
				},
				categoryList: [{
					name: '最新',
					image: '/static/new.png',
					url: '/pages/index/list'
				}, {
					name: '热门',
					image: '/static/hot.png',
					url: '/pages/index/list'
				}, {
					name: '发现',
					image: '/static/blink.png',
					url: '/pages/index/list'
				}, {
					name: '分类',
					image: '/static/category.png',
					url: '/pages/index/category'
				}],
				swiperlist: [{
					image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[483].jpg',
				}, {
					image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[480].jpg',
				}, {
					image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[484].jpg',
				}, ],
				list: [{
						name: '北国风光，千里冰封',
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[326].jpg',
					},
					{
						name: '望长城内外，惟余莽莽',
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[58].jpg',
					}, {
						name: '望长城内外，惟余莽莽',
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[340].jpg',
					}, {
						name: '望长城内外，惟余莽莽',
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[527].jpg',
					}
				]
			}
		},
		onLoad() {
			this.apiList()
		},
		methods: {
			back() { // 首页
				uni.navigateBack({
					delta: 2
				})
			},
			apiList() {
				for (let i = 0; i < 10; i++) {
					let item = {
						name: '望长城内外',
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[' + this.page +
							i + '].jpg'
					}
					this.list.push(item);
				}
			},
			toList(url) {
				this.$common.navigateTo(url)
			},
			toSearch() {
				this.$common.navigateTo('/pages/index/search')
			},
			onReachBottom() {
				setTimeout(() => {
					this.page++;
					this.apiList();
				}, 1000);
			},
			onPageScroll(e) {
				this.scrollTop = e.scrollTop;
			}
		}
	}
</script>

<style lang="scss">
	.search-wrap {
		margin: 0 30rpx 0 10rpx;
		flex: 1;
	}

	.grid-text {
		font-size: 20rpx;
		margin-top: 4rpx;
		color: $u-type-info;
	}

	.title {
		font-weight: bold;
		font-size: 30rpx;
		padding-bottom: 20rpx;
	}

	.f7 {
		background-color: #f7f7f7;
	}
</style>

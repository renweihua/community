<template>
	<view>
		<u-navbar back-text="返回" :backTextStyle="backTextColor" backIconColor="#fff" titleColor="#fff" :background="background"></u-navbar>
		<list :list="list"></list>
		<!-- 回到顶部按钮 -->
		<view>
			<u-back-top :scrollTop="scrollTop" :mode="modeTop" :bottom="bottomTop" :right="rightTop" :top="top" :icon="iconTop"
			 :custom-style="customStyle" :icon-style="iconStyleTop" :tips="tips">
			</u-back-top>
		</view>
	</view>
</template>

<script>
	import list from '@/components/list'
	export default {
		components:{list},
		data() {
			return {
				backTextColor: {
					'color': '#ffffff'
				},
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
				page:1,
				background: this.TopColor,
				list: [{
						name: '北国风光，千里冰封',
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[326].jpg',
					},
					{
						name: '望长城内外，惟余莽莽',	
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[58].jpg',
					},{
						name: '望长城内外，惟余莽莽',	
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[340].jpg',
					},{
						name: '望长城内外，惟余莽莽',	
						image: 'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com[527].jpg',
					}
				]
			}
		},
		onLoad() {
			this.apiList()
		},
		methods:{
			apiList(){
				for (let i = 0; i < 10; i++) {	
					let item = {name:'望长城内外',image:'https://cdn.jsdelivr.net/gh/uxiaohan/GitImgTypecho/Acg/api.vvhan.com['+this.page+i+'].jpg'}
					this.list.push(item);
				}
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
	page{
		background-color: #f7f7f7;
	}
</style>

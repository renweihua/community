<template>
	<view>
	<u-navbar title="收藏" backIconColor="#fff" titleColor="#fff" :background="background"></u-navbar>
	<list :list="list"></list>
	</view>
</template>

<script>
	import list from '@/components/list'
	export default {
		components:{list},
		data() {
			return {
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
			
		},
		methods:{
			
		}
	}
</script>

<style lang="scss">
	page{
		background-color: #f7f7f7;
	}
</style>
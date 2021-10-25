<script>
	import Vue from 'vue'
	export default {
		onLaunch: function() {
			// 定义全局头部栏颜色
			Vue.prototype.TopColor = {
				'background-image': 'linear-gradient(45deg, #FC0099, #FAA175)'
			}
			//console.log('App Launch')
		},
		onShow: function() {
			//console.log('App Show')
		},
		onHide: function() {
			//console.log('App Hide')
		}
	}
</script>

<style lang="scss">
	@import "uview-ui/index.scss";
</style>

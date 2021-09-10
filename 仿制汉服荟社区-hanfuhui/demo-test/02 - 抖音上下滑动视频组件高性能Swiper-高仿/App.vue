<script>
	export default {
		
		onLaunch: function() {
			console.log('App Launch')
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		},
		
	}
</script>

<style lang="scss">
	uni-tabbar {
		/* #ifndef APP-NVUE */
        .uni-tabbar { // tab 背景
            background-color: black; // tab 背景色
			height: 120rpx;
		}
		/* #endif */
    }
</style>
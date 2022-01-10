<script>
	import {
		getStarCover
	} from "@/api/CommonServer.js";
	
	// #ifdef APP-PLUS
	import APPUpdate from '@/uni_modules/zhouWei-APPUpdate/js_sdk/appUpdate';
	// #endif
	
	export default {
		onLaunch() {			
			// #ifdef APP-PLUS
			
			uni.getSystemInfo({
				success: function (res) {
					// console.log(res);
					// 存储手机信息
					uni.setStorageSync('equipment-platform', res.platform);
					uni.setStorageSync('equipment-model', res.model);
					uni.setStorageSync('equipment-deviceId', res.deviceId);
				}
			});
			// 检测APP版本是否升级
			APPUpdate();
			
			// #endif
			
			// 检查token
			let token = uni.getStorageSync('TOKEN');
			if (!!token) {
				// #ifdef H5
				if (location.pathname != '/' || location.hash != '#/') {
					uni.redirectTo({
						url: '/pages/index/index'
					})
				}
				// #endif
				
				// 启动封面
				getStarCover().then(coverRes => {
					// 保存启动封面
					this.$store.commit('common/setStarCoverData', coverRes.data)
					return true;
				}).then(result => {
					// #ifndef APP-PLUS
					// 置空导航标题
					uni.setNavigationBarTitle({
						title: ''
					});
					// 充白导航背景色
					uni.setNavigationBarColor({
						frontColor: '#ffffff',
						backgroundColor: '#ffffff'
					});
					// #endif
					
					return true;
				}).then(res => {
					// #ifndef APP-PLUS 
					// 导航标题
					uni.setNavigationBarTitle({
						title: '主页'
					});
					// 导航背景色
					uni.setNavigationBarColor({
						frontColor: '#ffffff',
						backgroundColor: '#ff6699',
						animation: {
							duration: 4000,
							timingFunc: 'linear'
						}
					})
					// #endif

				}).catch(err => {
					console.log('APP - catch');
					console.log(err, false);
					// 登录问题
					// if (err.data !== 401) {
					// 	uni.navigateTo({
					// 		url: '/pages/login/login'
					// 	})
					// }
				})
			}
		},
		onShow() {
			console.log('App Show')
		},
		onHide() {
			console.log('App Hide')
		}
	}
</script>

<style>
	/* 每个页面公共css */
	@import "/style/common.css";
	@import "/style/relation.css";
	/* HTML富文本解析器样式 */
	@import "/components/gaoyia-parse/parse.css";

	/* 页面底色 */
	page {
		background: #F8F8F8;
	}
</style>

<script>
	import {
		getRsaText,
		getStarCover
	} from "@/api/CommonServer.js"
	import {
		refreshUserToken,
		getLoginUserInfo,
	} from "@/api/UserServer.js"
	import {
		getMessageNoReadCount,
	} from "@/api/MessageServer.js"

	export default {
		onLaunch() {
			console.log('App Launch')
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
					console.log(coverRes.data);
					// 保存启动封面
					this.$store.commit('common/setStarCoverData', coverRes.data)
					return true;
					// 取得rsa加密文本
					return getRsaText(`${token},${Math.floor(new Date().getTime() / 1000)}`)
				}).then(rsaRes => {
					return true;
					// 刷新token值 
					return refreshUserToken(rsaRes.data)
				}).then(reslut => {
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

					// 保存账户信息和新token值
					this.$store.commit('user/setAccountInfoData', []);
					uni.setStorageSync('TOKEN', accountRes.data.Data.AccessToken);
					// 获得登录用户信息
					return getLoginUserInfo();
				}).then(userinfoRes => {
					console.log(userinfoRes);
					// 保存登录用户信息
					this.$store.commit('user/setLoginUserInfoData', userinfoRes.data);
					
					// 获取未读消息数
					return getMessageNoReadCount()
				}).then(mesRes => {
					// 保存未读消息数
					this.$store.commit('setNewsCountData', mesRes.data)
					
					return true;
				}).then(res => {
					console.log('app.vue', res);

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
					console.log(err, false);
					// 登录问题
					if (err.data !== 401) {
						uni.redirectTo({
							url: '/pages/login/login'
						})
					}
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

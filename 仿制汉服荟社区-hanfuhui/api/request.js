/**
 * ===========
 * 统一请求发送
 * ===========
 */
export default function request(route, method = 'get', data = {}) {
	return new Promise((resolve, reject) => {
		let login_token = uni.getStorageSync('TOKEN') || '';
		uni.request({
			// url: 'http://api5.laosha.net' + route,
			// url: 'http://easy-mock.liuup.com/mock/5df764250a2f9f42cfec1a50/api5.hanfugou.com' + route,
			url: 'http://community.cnpscy.com/api' + route,
			method,
			data,
			header: {
				'_version': 3,
				'Authorization': !login_token ? '' : ('bearer ' + login_token),
				'_fromclient': 'android',
				'content-type': 'application/x-www-form-urlencoded',
			},
			success: res => {
				// token失效
				if (res.statusCode == 401 || res.data.status == -1) {
					reject({
						data: 401,
						status: res.statusCode
					})
					// console.log(res);
					// uni.redirectTo({
					// 	url: '/pages/login/login'
					// })
					return;
				}
				// 服务器错误
				if (res.statusCode == 404) {
					reject({
						data: {},
						status: res.statusCode
					})
					uni.showToast({
						title: 'API接口不存在！',
						icon: 'none'
					})
					return;
				}
				// 服务器错误
				if (res.statusCode == 500) {
					reject({
						data: {},
						status: res.statusCode
					})
					uni.showToast({
						title: '服务器错误',
						icon: 'none'
					})
					return;
				}
				// 含有错误
				if (res.data.status != 1) {
					reject({
						data: res.data,
						status: res.statusCode
					})
					uni.showToast({
						title: res.data.msg,
						icon: 'none'
					})
					return;
				}
				
				// 正常
				resolve(res.data);
			},
			fail: err => {
				// console.log(err)
				reject(err)
				uni.showToast({
					title: '网络连接异常',
					icon: 'none'
				})
			}
		})
	})
}

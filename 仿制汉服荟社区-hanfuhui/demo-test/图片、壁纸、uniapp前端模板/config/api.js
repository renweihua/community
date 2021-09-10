import * as common from './common.js' //引入common
import * as db from './db.js' //引入db

export const domain = '127.0.0.1'

export const UploadUrl = 'api/common/upload'

// 需要登陆的，都写到这里，否则就是不需要登陆的接口
const methodsLogin = [
	
];
//POST方法
const post = async (method, data) => {
	return new Promise((resole, reject) => {
		
		let header = {
			'Content-Type': 'application/json', //自定义请求头信息
		};
		// 判断token是否存在
		if (methodsLogin.indexOf(method) >= 0) {
			// 获取用户token
			let token = db.token();
			if(!token){
				common.jumpToLogin();
				return;
			}
			header.token = token;	
		}
		uni.request({
			url: domain + method,
			header: header,
			data: data,
			method: 'POST',
			success: (response) => {
				const result = response.data
				// 登录信息过期或者未登录
				if (result.code === 401) {
					db.del("userinfo");
					uni.showToast({
						title: result.msg,
						icon: 'none',
						duration: 1000,
						complete: function() {
							setTimeout(function() {
								uni.hideToast();
								common.jumpToLogin()
							}, 1000)
						}
					});
				}
				resole(result);
			},
			complete: () => {
				setTimeout(function() {
					uni.hideLoading();
				}, 250);
			},
			fail: (error) => {
				resole({});
				if (error && error.response) {
					showError(error.response);
				}
			},
		});
	})
}
//GET方法
const get = async (url, data = {}) => {
	return new Promise((resole, reject) => {
		let header = {
			'Content-Type': 'application/json', //自定义请求头信息
		}
		// 判断token是否存在
		if (methodsLogin.indexOf(url) >= 0) {
			// 获取用户token
			let token = db.token();
			if(!token){
				common.jumpToLogin();
				return;
			}
			header.token = token;
		}
		uni.request({
			url: domain + url,
			data: data,
			header: header,
			method: 'GET',
			success: (response) => {
				uni.hideLoading();
				const result = response.data
				if (result.code === 401) {
					db.del("userinfo");
					uni.showToast({
						title: result.msg,
						icon: 'none',
						duration: 1000,
						complete: function() {
							setTimeout(function() {
								uni.hideToast();
								common.jumpToLogin()
							}, 1000)
						}
					});
				}
				resole(result);
			},
			fail: (error) => {
				resole({});
				if (error && error.response) {
					showError(error.response);
				}
			},
			complete: () => {
				setTimeout(function() {
					uni.hideLoading();
				}, 250);
			}
		});
	})
}
//统一处理请求错误
const showError = error => {
	let errorMsg = ''
	switch (error.status) {
		case 400:
			errorMsg = '请求参数错误'
			break
		case 401:
			errorMsg = '未授权，请登录'
			break
		case 408:
			errorMsg = '请求超时'
			break
		case 500:
			errorMsg = '服务器内部错误'
			break
		case 501:
			errorMsg = '服务未实现'
			break
		case 502:
			errorMsg = '网关错误'
			break
		case 503:
			errorMsg = '服务不可用'
			break
		case 504:
			errorMsg = '网关超时'
			break
		case 505:
			errorMsg = 'HTTP版本不受支持'
			break
		default:
			errorMsg = error.msg
			break
	}

	uni.showToast({
		title: errorMsg,
		icon: 'none',
		duration: 1000,
		complete: function() {
			setTimeout(function() {
				uni.hideToast();
			}, 1000);
		}
	});
}


// 底部栏
export const tabbar = async (data) => await get('/api/v1/tabbar', data);
/**
 * ===========
 * 统一请求发送
 * ===========
 */
export default function request(route, method = 'get', data = {}) {
	console.log('http://bbs.cnpscy.com/api/bbs' + route);
  return new Promise((resolve, reject) => {
    uni.request({
      url: 'http://bbs.cnpscy.com/api/bbs' + route,
      method,
      data,
      header: {
        'hanfuhui_version': 3,
        'hanfuhui_token': uni.getStorageSync('TOKEN') || '',
        'hanfuhui_fromclient': 'android',
        'content-type': 'application/x-www-form-urlencoded',
      },
      success: res => {
        // console.log('请求结果', res);
        // token失效
        if (res.status == 401 || res.statusCode == 401 || res.data.ErrorMessage == '登录过期') {
          reject({
            data: 401,
            status: res.statusCode
          })
          uni.redirectTo({
            url: '/pages/login/login'
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
        // if (res.data.status != 1) {
        //   reject({
        //     data: res.data,
        //     status: res.statusCode
        //   })
        //   uni.showToast({
        //     title: res.data.ErrorMessage,
        //     icon: 'none'
        //   })
        //   return;
        // }
        // 正常
        resolve({
          data: res.data,
          status: res.statusCode
        })
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

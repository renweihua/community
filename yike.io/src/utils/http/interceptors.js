import { Message } from 'element-ui'

export default http => {
  // 请求拦截
  http.interceptors.request.use(
    config => {
      // config.withCredentials = true // 需要跨域打开此配置
      return config
    },
    error => {
      return Promise.reject(error)
    }
  )
  // https://github.com/mzabriskie/axios#interceptors
  http.interceptors.response.use(
    response => {
      return response.data
    },
    /**
     * This is a central point to handle all
     * error messages generated by HTTP
     * requests
     */
    error => {
      console.log('interceptors');
      console.log(error);
      if (!error['response']) {
        return Promise.reject(error)
      }

      switch (error.response.status) {
        case -1:
        case 0:
        case 422:
          let data = error.response.data.errors
          let content = ''

          Object.keys(data).map(function (key) {
            let value = data[key]

            content = value[0]
          })

          Message.error(content)
          break
        case 403:
          Message.error(error.response.data.message || '您没有此操作权限！')
          break
        case 401:
          if (window.location.pathname !== '/auth/login') {
            window.location.href = '/auth/login'
          }
          break
        case 500:
        case 501:
        case 503:
        default:
          Message.error('服务器出了点小问题，程序员小哥哥要被扣工资了~！')
      }
      return Promise.reject(error.response)
    }
  )
}

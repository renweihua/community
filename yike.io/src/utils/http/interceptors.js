import {Message} from 'element-ui';
import store from '../../vuex'

export default http => {
    // 请求拦截
    http.interceptors.request.use(
        config => {
            // config.withCredentials = true // 需要跨域打开此配置
            return config
        },
        error => {
            return Promise.reject(error);
        }
    )
    // https://github.com/mzabriskie/axios#interceptors
    http.interceptors.response.use(
        response => {
            let data = response.data;
            if (data.status == 0) {
                // 是否拦截
                if (data.no_interceptor) {
                    return data;
                }else{
                    if (data.msg) {
                        Message.error(data.msg);   
                    }
                    return Promise.reject(data.msg);
                }
            } else {
                if (data.status == -1) {
                    store.dispatch('setToken', null);
                    store.dispatch('setUser', {});
                }
                // 如果服务端返回新的Token，那么自动存储于data，避免更改现有返回结果
                if (response.headers.authorization) {
                    // data.authorization = response.headers.authorization;
                    store.dispatch('setToken', response.headers.authorization);
                }
                return data;
            }
        },
        /**
         * This is a central point to handle all
         * error messages generated by HTTP
         * requests
         */
        error => {
            if (!error['response']) {
                return Promise.reject(error);
            }

            switch (error.response.status) {
                case -1:
                case 0:
                case 422:
                    let data = error.response.data.errors;
                    let content = '';

                    Object.keys(data).map(function (key) {
                        let value = data[key]

                        content = value[0]
                    })

                    Message.error(content);
                    break
                case 403:
                    Message.error(error.response.data.msg || '您没有此操作权限！');
                    break
                case 400:
                    Message.error(error.response.data.msg);
                    break
                case 401:
                    Message.error(error.response.data.msg || 'UNAUTHORIZED！');
                    let path = window.location.pathname;
                    // 401，首页也无需跳转
                    if (path !== '/' && path !== '/auth/login') {
                        window.location.href = '/auth/login';
                    }
                    break;
                case 500:
                case 501:
                case 503:
                default:
                    Message.error('服务器出了点小问题，程序员小哥哥要被扣工资了~！');
            }
            return Promise.reject(error.response);
        }
    )
}

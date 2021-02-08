import axios from 'axios';
import {
    MessageBox,
    Message
} from 'element-ui';
import store from '@/store';
import {
    getToken
} from '@/utils/auth';

console.log(process);
// console.log(process.env);
// console.log(process.env.VUE_APP_BASE_API);

// process失效了，默认为当前URL为请求地址
process.env.VUE_APP_BASE_API = window.location.origin + window.location.pathname;
// console.log(process.env.VUE_APP_BASE_API);

let timeout = 15000;

// create an axios instance
const service = axios.create({
    baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
    // withCredentials: true, // send cookies when cross-domain requests
    timeout: timeout, // request timeout
    headers: {
        // 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
        "Content-Type": "application/json;charset=utf-8",
    }
});

// request interceptor
service.interceptors.request.use(
    config => {
        // do something before request is sent

        if (store.getters.token) {
            // let each request carry token
            // ['X-Token'] is a custom headers key
            // please modify it according to the actual situation
            config.headers['Authorization'] = getToken();
        }

        if (config.headers['Content-Type'] === 'application/x-www-form-urlencoded;charset=UTF-8') {
            if (config.data) {
                // config.data = JSON.stringify(config.data)
            }
        }
        return config;
    },
    error => {
        // do something with request error
        console.log(error); // for debug
        return Promise.reject(error);
    }
)

// response interceptor
service.interceptors.response.use(
    /**
     * If you want to get http information such as headers or status
     * Please return  response => response
     */

    /**
     * Determine the request status by custom code
     * Here is just an example
     * You can also judge the status by HTTP Status Code
     */
    response => {
        const res = response.data;

        // if the custom code is not 20000, it is judged as an error.
        if (res.status !== 1) {
            Message({
                message: res.msg || 'Error',
                type: 'error',
                duration: 5 * 1000,
            });

            // 50008: Illegal token; 50012: Other clients logged in; 50014: Token expired;
            if (res.status === -1) {
                // to re-login
                MessageBox.confirm('You have been logged out, you can cancel to stay on this page, or log in again',
                    'Confirm logout', {
                        confirmButtonText: 'Re-Login',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                    }).then(() => {
                    store.dispatch('user/resetToken').then(() => {
                        location.reload();
                    })
                })
            }
            return Promise.reject(new Error(res.msg || 'Error'));
        } else {
            return res;
        }
    },
    error => {
        console.log('err' + error); // for debug
        let msg = error.msg;
        if (error.response == undefined){
            msg = '超时 ' + timeout + ' ms，请刷新！';
        }else{
            switch (error.response.status) {
                case 404:
                    msg = error.response.statusText;
                    break;
                case 401: // 认证失败
                    msg = error.response.data.msg;
                    break;
                case 500: // 认证失败
                    msg = error.response.statusText;
                    break;
            }
        }
        Message({
            message: msg,
            type: 'error',
            duration: 5 * 1000
        });
        return Promise.reject(error);
    }
)

export default service

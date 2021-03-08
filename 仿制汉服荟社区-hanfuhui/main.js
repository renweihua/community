import Vue from 'vue'
import App from './App'

// 挂载全局状态管理
import store from './store'
Vue.prototype.$store = store;

import socket from '@/api/socket.js';
// socket
Vue.prototype.$socket = socket.init();

// iconfont 官方自定义收藏图标库组件
import IIcon from '@/components/i-icon/i-icon'
Vue.component('i-icon', IIcon)
// 用户头像组件
import UserAvatar from '@/components/user-avatar/user-avatar'
Vue.component('user-avatar', UserAvatar)
// 下拉刷新组件
import MescrollUni from "@/components/mescroll-uni/mescroll-uni.vue";
Vue.component('mescroll-uni', MescrollUni)

Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
  store,
  ...App
})
app.$mount()

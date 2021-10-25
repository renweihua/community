import Vue from 'vue'
import App from './App'

import * as Common from './config/common.js'

Vue.config.productionTip = false

Vue.prototype.$common = Common;

App.mpType = 'app'

// 引入全局uView
import uView from 'uview-ui';
Vue.use(uView);

const app = new Vue({
    ...App
})
app.$mount()

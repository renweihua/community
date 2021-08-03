import { routes as auth } from '$modules/auth'
import { routes as home } from '$modules/home'
import { routes as nodes } from '$modules/nodes'
import { routes as notifications } from '$modules/notifications'
import { routes as pages } from '$modules/pages'
import { routes as threads } from '$modules/threads'
import { routes as users } from '$modules/users'
import Vue from 'vue'
import Router from 'vue-router'
import beforeEach from './beforeEach'


// 解决ElementUI导航栏中的vue-router在3.0版本以上重复点菜单报错问题
const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}


Vue.use(Router)

const AppRoute = {
  path: '/',
  component: () => import('../app'),
  children: [...auth, ...home, ...threads, ...notifications, ...nodes, ...pages, ...users]
}

const routes = [AppRoute]

const router = new Router({
  routes,
  linkActiveClass: 'active',
  linkExactActiveClass: 'active',
  mode: 'history'
})

router.beforeEach(beforeEach)

export default router

import vuex from '../vuex'

const needAuth = route => route.meta.requiresAuth === true

const beforeEach = (to, from, next) => {
  /**
   * Otherwise  if authentication is required login.
   */
  vuex
    .dispatch('checkUserToken')
    .then(() => {
      // 需求：登录用户绑定第三方账户
      // 已登录，进入登录页面，自动返回`首页`
      // console.log(to.path);
      if (vuex.getters.isLogged && to.path == '/auth/login') {
        return next({ name: 'home' });
      }
      return next();
    })
    .catch(() => {
      if (needAuth(to)) {
        // No token, or it is invalid
        return next({ name: 'auth.login' }); // redirect to login
      }
      next();
    })
}

export default beforeEach

export default [
  {
    path: 'auth/login',
    name: 'auth.login',
    component: () => import('./login')
  },
  {
    path: 'auth/register',
    name: 'auth.register',
    component: () => import('./register')
  },
  {
    path: 'oauth/oauth-redirect/:platform',
    name: 'oauth.oauth_redirect',
    meta: {
      navbar: false,
      container: false
    },
    component: () => import('./oauth')
  },
  {
    path: 'oauth/:platform/callback',
    name: 'oauth.oauth_callback',
    meta: {
      navbar: false,
      container: false
    },
    component: () => import('./oauth')
  },
  {
    path: '/user/forget-password',
    name: 'auth.forget-password',
    component: () => import('./forget-password')
  },
  {
    path: '/user/reset-password',
    name: 'auth.reset-password',
    component: () => import('./reset-password')
  }
]

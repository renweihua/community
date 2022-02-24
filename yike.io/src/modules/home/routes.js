export default [
  {
    path: '/',
    name: 'home',
    component: () => import('./home')
  },
  {
    path: '/records',
    name: 'records',
    component: () => import('./records')
  }
]

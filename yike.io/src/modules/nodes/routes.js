export default [
  {
    path: '/nodes',
    name: 'nodes.show',
    component: () => import('./list')
  },
  {
    path: '/nodes/:topic_id',
    name: 'nodes.node',
    component: () => import('./show'),
    meta: {
      container: false
    }
  }
]

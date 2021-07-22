export default [
  {
    path: 'threads/create',
    name: 'threads.create',
    component: () => import('./form')
  },
  {
    path: 'threads/:dynamic_id/edit',
    name: 'threads.edit',
    component: () => import('./form')
  },
  {
    path: 'threads/:dynamic_id',
    name: 'threads.show',
    component: () => import('./show')
  }
]

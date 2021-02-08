import request from '@/utils/request'

export function getRolesSelect() {
    return request({
        url: '/admin_roles/getSelectLists',
        method: 'get'
    })
}

export function getList(params) {
    return request({
        url: 'admin_roles',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/admin_roles/create',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/admin_roles/update`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/admin_roles/delete`,
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: `/admin_roles/changeFiledStatus`,
        method: 'put',
        data
    })
}

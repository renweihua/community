import request from '@/utils/request'

export function getList(params) {
    return request({
        url: '/friendlinks',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/friendlinks/create',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: '/friendlinks/update',
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: '/friendlinks/delete',
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: '/friendlinks/changeFiledStatus',
        method: 'put',
        data
    })
}

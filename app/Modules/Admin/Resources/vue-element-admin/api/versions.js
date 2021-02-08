import request from '@/utils/request'

export function getList(params) {
    return request({
        url: '/versions',
        method: 'get',
        params
    });
}

export function create(data) {
    return request({
        url: '/versions/create',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/versions/update',
        method: 'put',
        data
    });
}

export function setDel(data) {
    return request({
        url: '/versions/delete',
        method: 'delete',
        data
    });
}

export function changeFiledStatus(data) {
    return request({
        url: '/versions/changeFiledStatus',
        method: 'put',
        data
    })
}

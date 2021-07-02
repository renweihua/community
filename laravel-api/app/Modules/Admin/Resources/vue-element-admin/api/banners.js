import request from '@/utils/request'

export function getList(params) {
    return request({
        url: '/banners',
        method: 'get',
        params
    });
}

export function create(data) {
    return request({
        url: '/banners/create',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/banners/update',
        method: 'put',
        data
    });
}

export function setDel(data) {
    return request({
        url: '/banners/delete',
        method: 'delete',
        data
    });
}

export function changeFiledStatus(data) {
    return request({
        url: '/banners/changeFiledStatus',
        method: 'put',
        data
    })
}

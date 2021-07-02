import request from '@/utils/request'

export function getArticleLabelSelect(params) {
    return request({
        url: '/article_labels/getSelectLists',
        method: 'get',
        params
    });
}

export function getList(params) {
    return request({
        url: '/article_labels',
        method: 'get',
        params
    });
}

export function create(data) {
    return request({
        url: '/article_labels/create',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/article_labels/update',
        method: 'put',
        data
    });
}

export function setDel(data) {
    return request({
        url: '/article_labels/delete',
        method: 'delete',
        data
    });
}

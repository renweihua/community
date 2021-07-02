import request from '@/utils/request'

export function getCategorySelect() {
    return request({
        url: '/article_categories/getSelectLists',
        method: 'get'
    })
}

export function getList(params) {
    return request({
        url: 'article_categories',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/article_categories/create',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/article_categories/update`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/article_categories/delete`,
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: `/article_categories/changeFiledStatus`,
        method: 'put',
        data
    })
}

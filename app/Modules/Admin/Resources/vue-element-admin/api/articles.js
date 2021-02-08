import request from '@/utils/request'

export function getCategoriesSelect() {
    return request({
        url: '/article_categories/getSelectLists',
        method: 'get'
    })
}

export function getList(params) {
    return request({
        url: 'articles',
        method: 'get',
        params
    })
}

export function detail(id) {
    return request({
        url: '/articles/detail',
        method: 'get',
        params: { article_id:id }
    })
}

export function create(data) {
    return request({
        url: '/articles/create',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/articles/update`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/articles/delete`,
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: '/articles/changeFiledStatus',
        method: 'put',
        data
    })
}

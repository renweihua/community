import request from '@/utils/request'

export function getMenusSelect() {
    return request({
        url: '/menus/getSelectLists',
        method: 'get'
    })
}
export function getList(params) {
    return request({
        url: 'menus',
        method: 'get',
        params
    })
}

export function getTplTypeAndViews() {
    return request({
        url: 'menus/getTplTypeAndViews',
        method: 'get'
    })
}


export function create(data) {
    return request({
        url: '/menus/create',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/menus/update`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/menus/delete`,
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: `/menus/changeFiledStatus`,
        method: 'put',
        data
    })
}

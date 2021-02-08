import request from '@/utils/request'

export function getAdminsSelect(params) {
    return request({
        url: '/admins/getSelectLists',
        method: 'get',
        params
    });
}

export function getList(params) {
    return request({
        url: 'admins',
        method: 'get',
        params
    })
}

// export function detail(data) {
//     return request({
//         url: '/admins/detail',
//         method: 'post',
//         data
//     })
// }

export function create(data) {
    return request({
        url: '/admins/create',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/admins/update`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/admins/delete`,
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: `/admins/changeFiledStatus`,
        method: 'put',
        data
    })
}

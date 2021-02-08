import request from '@/utils/request'

export function getList(params) {
    return request({
        url: 'adminlogs',
        method: 'get',
        params
    })
}

export function setDel(data) {
    return request({
        url: `/adminlogs/delete`,
        method: 'delete',
        data
    })
}

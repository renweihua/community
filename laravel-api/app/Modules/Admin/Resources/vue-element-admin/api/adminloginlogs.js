import request from '@/utils/request'

export function getList(params) {
    return request({
        url: 'adminloginlogs',
        method: 'get',
        params
    })
}

export function setDel(data) {
    return request({
        url: `/adminloginlogs/delete`,
        method: 'delete',
        data
    })
}

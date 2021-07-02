import request from '@/utils/request'

// 文件列表
export function getFileGroup(params) {
    return request({
        url: '/getGroupList',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/fileGroup/create',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/fileGroup/update',
        method: 'put',
        data
    });
}

export function delFileGroup(data) {
    return request({
        url: `/fileGroup/delete`,
        method: 'delete',
        data
    })
}



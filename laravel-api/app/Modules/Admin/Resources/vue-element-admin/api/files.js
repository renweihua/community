import request from '@/utils/request'

// 文件列表
export function getFileList(params) {
    return request({
        url: '/getFileList',
        method: 'get',
        params
    })
}

export function delFile(data) {
    return request({
        url: `/files/delete`,
        method: 'delete',
        data
    })
}

export function removeFileGroup(data) {
    return request({
        url: '/files/removeFileGroup',
        method: 'put',
        data
    });
}

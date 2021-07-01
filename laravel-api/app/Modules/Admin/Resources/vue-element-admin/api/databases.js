import request from '@/utils/request'

export function getList(params) {
    return request({
        url: '/database/tables',
        method: 'get',
        params
    });
}

export function backupsTables(data) {
    return request({
        url: '/database/backupsTables',
        method: 'post',
        data
    });
}

// 备份记录列表
export function getBackupsList(params) {
    return request({
        url: '/database/backups',
        method: 'get',
        params
    });
}

export function deleteBackup(data) {
    return request({
        url: '/database/deleteBackup',
        method: 'delete',
        data
    })
}

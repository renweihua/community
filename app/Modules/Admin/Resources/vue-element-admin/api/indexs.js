import request from '@/utils/request'

// 首页 - 基础统计数据
export function statistics() {
    return request({
        url: '/indexs',
        method: 'get'
    })
}

// 首页 - 请求日志统计表数据
export function logsStatistics() {
    return request({
        url: '/logsStatistics',
        method: 'get'
    })
}

// 编辑登录管理员信息
export function updateAdmin(data) {
    return request({
        url: '/updateAdmin',
        method: 'put',
        data,
    })
}

// 版本历史记录
export function versionLogs() {
    return request({
        url: '/versionLogs',
        method: 'get',
    })
}

// 服务器状态
export function getServerStatus() {
    return request({
        url: '/getServerStatus',
        method: 'get',
    })
}

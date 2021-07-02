import request from '@/api/request.js';
/**
 * ===========
 * 我的
 * ===========
 */

/**
 * 登录日志
 */
export async function getLoginLogs(params = {
	search_month: '',
	page: 1,
	limit: 20
}) {
	return await request('/user/login_logs', 'get', params)
}

import request from '@/api/request.js';
/**
 * ===========
 * 推荐动态服务接口
 * ===========
 */

/**
 * 获取动态列表
 */
export async function getDynamics(params) {
	return await request('/dynamics', 'get', params)
}


/**
 * 获取动态详情信息
 *
 * @param {Number} dynamic_id 动态Id
 */
export async function getDynamicInfo(dynamic_id) {
	return await request('/dynamic/detail', 'get', {
		dynamic_id
	})
}

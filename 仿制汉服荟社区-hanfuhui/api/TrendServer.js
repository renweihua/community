import request from '@/api/request.js';
/**
 * ===========
 * 推荐动态服务接口
 * ===========
 */


/**
 * 获取广场动态列表
 * @param {Object} params 参数 {page:1,maxid:-1,limit:20}
 */
export async function getSquareList(params = {
	page: 1,
	maxid: -1,
	limit: 20
}) {
	return await request('/trend/GetTrendListForSquare', 'get', params)
}

/**
 * 获取发现-动态列表
 * @param {Object} params 参数 {page:1,limit:20}
 */
export async function getDiscoverList(params = {
	page: 1,
	limit: 20
}) {
	return await request('/discover', 'get', params)
}

/**
 * 获取关注动态列表
 * @param {Object} params 参数 {page:1,maxid:-1,limit:20}
 */
export async function getAtteList(params = {
	page: 1,
	maxid: -1,
	limit: 20
}) {
	return await request('/trend/GetTrendListForAtte', 'get', params)
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

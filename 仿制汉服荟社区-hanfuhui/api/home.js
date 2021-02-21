import request from '@/api/request.js';
/**
 * ===========
 * 首页接口
 * ===========
 */


/**
 * 首页：发现-动态列表
 * @param {Object} params 参数 {page:1,limit:20}
 */
export async function getDiscoverList(params = {
	page: 1,
	limit: 20
}) {
	return await request('/discover', 'get', params)
}

/**
 * 首页：获取关注会员的动态列表
 */
export async function getFollowDynamics(params = {
	maxid: -1,
  page: 1,
  limit: 20
}) {
  return await request('/follow', 'get', params)
}

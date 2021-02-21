import request from '@/api/request.js';
/**
 * ===========
 * 首页接口
 * ===========
 */

/**
 * 获取关注会员的动态列表
 */
export async function getFollowDynamics(params = {
	maxid: -1,
  page: 1,
  limit: 20
}) {
  return await request('/follow', 'get', params)
}

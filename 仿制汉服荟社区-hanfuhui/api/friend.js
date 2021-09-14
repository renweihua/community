import request from '@/api/request.js';

/**
 * 好友列表
 */
export async function friends(params) {
	return await request('/user/friends', 'get', params)
}

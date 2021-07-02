import request from '@/api/request.js';

/**
 * 发布动态
 */
export async function pushDynamic(params) {
  return await request('/dynamic/push', 'POST', params)
}

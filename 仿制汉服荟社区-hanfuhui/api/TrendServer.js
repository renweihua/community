import request from '@/api/request.js';
/**
 * ===========
 * 推荐动态服务接口
 * ===========
 */


/**
 * 获取广场动态列表
 * @param {Object} params 参数 {page:1,maxid:-1,count:20}
 */
export async function getSquareList(params = {
  page: 1,
  maxid: -1,
  count: 20
}) {
  return await request('/trend/GetTrendListForSquare', 'get', params)
}

/**
 * 获取推荐动态列表
 * @param {Object} params 参数 {page:1,maxid:-1,count:20}
 */
export async function getMainList(params = {
  page: 1,
  maxid: -1,
  count: 20
}) {
  return await request('/trend/GetTrendListForMain', 'get', params)
}

/**
 * 获取关注动态列表
 * @param {Object} params 参数 {page:1,maxid:-1,count:20}
 */
export async function getAtteList(params = {
  page: 1,
  maxid: -1,
  count: 20
}) {
  return await request('/trend/GetTrendListForAtte', 'get', params)
}

/**
 * 获取动态详情信息
 * @param {Number} id 参数 2616317
 */
export async function getTrendInfo(id = 2616317) {
  return await request('/trend/GetTrend', 'get', {
    id
  })
}

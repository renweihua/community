import request from '@/api/request.js';
/**
 * ===========
 * 组织活动服务接口
 * ===========
 */


/**
 * 获取组织省市列表 
 */
export async function getOrgProvinceList() {
  return await request('/org/GetOrgActivityByProvince')
}

/**
 * 获取组织活动列表
 * @param {Object} params 参数 {state: 0,province: 0,page: 1,count: 20,role: 5}
 */
export async function getOrgList(params = {
  state: 0,
  province: 0,
  page: 1,
  count: 20,
  role: 5
}) {
  return await request('/Org/GetOrgActivityList', 'get', params)
}

/**
 * 获取组织活动信息
 * @param {Number} id 参数 组织列表中ID
 */
export async function getOrgInfo(id = 112) {
  return await request(`/Org/GetOrgActivity?id=${id}`)
}

/**
 * 获取用户报名组织活动列表
 * @param {Object} params 参数 {page: 1,count: 10,role: 5}
 */
export async function getOrgUserList(params = {
  page: 1,
  count: 10,
  role: 5
}) {
  return await request('/Org/GetActivityOrdersList', 'get', params)
}

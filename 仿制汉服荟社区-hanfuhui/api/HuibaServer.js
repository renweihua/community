import request from '@/api/request.js';
/**
 * ===========
 * 荟吧服务接口
 * ===========
 */


/**
 * 获取荟吧列表
 */
export async function getHuibaList() {
  return await request('/topics', 'get')
}

/**
 * 获取荟吧信息
 * @param {Number} id 参数 荟吧列表中ID
 */
export async function getHuibaInfo(id = 7566) {
  return await request(`/Huiba/GetHuibaPublic?id=${id}`)
}

/**
 * 获取荟吧置顶公告
 * @param {Number} id 参数 荟吧列表中ID
 */
export async function getHuibaTop(id = 7566) {
  return await request(`/Huiba/GetHuibaTopListForHuiba?huibaid=${id}`)
}

/**
 * 获取荟吧内的动态列表
 * 
 * @param {Object} params 参数
 * ishot:按最热 true、按最新 false
 * max_id:最大值 以第一页ID或ObjectID
 * {
  topic_id: 1,
  ishot: false,
  max_id: -1,
  page: 1,
  limit: 20,
}
 */
export async function getHuibaTrend(params) {
  return await request('/topic/dynamics', 'get', params)
}

/**
 * 获取用户关注荟吧列表
 * @param {Object} params 参数  {
userid:985319,
page:1,
limit:20,
}
 */
export async function getHuibaUserFollow(params = {
  userid: 985319,
  page: 1,
  limit: 20,
}) {
  return await request('/huiba/GetHuibaFollowHuiba', 'get', params)
}

/**
 * 用户关注荟吧添加
 * @param {Object} id 参数 荟吧ID
 */
export async function addHuibaFollows(ids = 6499) {
  return await request('/huiba/InsertHuibaFollows', 'post', {
    ids
  })
}

/**
 * 用户关注荟吧取消
 * @param {Object} id 参数 荟吧ID
 */
export async function delHuibaFollows(id = 6499) {
  return await request('/huiba/DeleteHuibaFollow', 'post', {
    id
  })
}

/**
 * 获取荟吧类型列表
 */
export async function getHuibaType() {
  return await request('/huiba/GetHuibaTypeList')
}

/**
 * 获取荟吧类型内荟吧列表
 * @param {Object} params 参数  {
typeid:4,
page:1,
limit:20,
}
 */
export async function getHuibaTypeList(params = {
  typeid: 4,
  page: 1,
  limit: 20,
}) {
  return await request('/Huiba/GetHuibaListForType', 'get', params)
}

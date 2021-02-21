import request from '@/api/request.js';
/**
 * ===========
 * 汉币服务接口
 * ===========
 */


/**
 * 获取今日签到信息
 */
export async function getSigninInfo() {
  return await request('/user/getSignByToday')
}

/**
 * 立即签到
 */
export async function signIn() {
  return await request('/user/signIn', 'post')
}

/**
 * 指定月份的签到状态
 * 
 * @param {String} month 参数 月份
 */
export async function getSignInDateList(month) {
  return await request('/user/getSignsByMonth', 'get', {month})
}

/**
 * 获取汉币签到记录列表
 * @param {Object} params 参数 {page,count}
 */
export async function getSignInList(params = {
  page: 1,
  limit: 20
}) {
  return await request('/Hanbi/GetSignInList', 'get', params)
}

/**
 * 获取汉币商品列表
 * @param {Object} params 参数 {tag,haveorders,role,page,count}
 * tag 标签为签到商品 去掉获取全部
 */
export async function getHanbiShopList(params = {
  tag: 'signin',
  haveorders: true,
  role: 5,
  page: 1,
  limit: 10
}) {
  return await request('/Hanbi/GetHanbiShopList', 'get', params)
}

/**
 * 兑换汉币商品
 * @param {Object} params 参数 {shopid,useraddressid,remark}
 * useraddressid 使用地址ID，没有地址传0
 * remark 备注
 */
export async function addHanbiOrders(params = {
shopid:32,
useraddressid:0,
remark:'能买10次',
}) {
  return await request('/Hanbi/InsertHanbiOrders', 'post', params)
}

/**
 * 获取汉币用户兑换记录列表
 * @param {Object} params 参数 {page,count}
 */
export async function getHanbiUserOrdersList(params = {
  page: 1,
  limit: 10
}) {
  return await request('/Hanbi/GetHanbiOrdersListForUser', 'get', params)
}

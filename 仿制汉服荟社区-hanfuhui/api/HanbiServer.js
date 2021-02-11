import request from '@/api/request.js';
/**
 * ===========
 * 汉币服务接口
 * ===========
 */


/**
 * 获取汉币签到信息
 */
export async function getSigninInfo() {
  return await request('/Hanbi/GetSigninInfo')
}

/**
 * 获取汉币签到添加
 */
export async function addSignin() {
  return await request('/Hanbi/InsertSignin', 'post')
}

/**
 * 获取签到时间列表
 * @param {String} date 参数 签到时间 
 * 最小月选择到Remark为开始签到月
 */
export async function getSignInDateList(date = '2019/5/01') {
  return await request('/Hanbi/GetSignInListForDate', 'get', {date})
}

/**
 * 获取汉币签到记录列表 
 * @param {Object} params 参数 {page,count}
 */
export async function getSignInList(params = {
  page: 1,
  count: 20
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
  count: 10
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
  count: 10
}) {
  return await request('/Hanbi/GetHanbiOrdersListForUser', 'get', params)
}

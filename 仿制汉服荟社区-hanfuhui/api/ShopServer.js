import request from '@/api/request.js';
/**
 * ===========
 * 商城服务接口
 * ===========
 */


/**
 * 获取商城专题列表
 * @param {Object} params 参数 {page:1,limit:20}
 * role 权限等级默认5、权限不足2
 */
export async function getSpecialList(params = {
  page: 1,
  limit: 20
}) {
  params.role = 5;
  return await request('/Product/GetSpecialList', 'get', params)
}

/**
 * 获取商品专题信息
 * @param {Number} id 参数 专题列表中专题ID
 */
export async function getSpecialInfo(id = 123) {
  return await request(`/Product/GetSpecial?id=${id}&role=5`)
}

/**
 * 获取商品热卖列表
 * @param {String} count 参数 条数
 */
export async function getProductHottestList(count = 9) {
  return await request(`/Product/GetProductListPublicForHot?count=${count}`)
}

/**
 * 获取商品精选推荐
 * @param {Object} params 参数 {good: true,page:1,limit:20}
 * good 是否精选推荐套装
 */
export async function getProductList(params = {
  good: true,
  page: 1,
  limit: 20
}) {
  return await request('/Product/GetProductListPublic', 'get', params)
}

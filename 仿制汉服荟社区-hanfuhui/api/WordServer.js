import request from '@/api/request.js';
/**
 * ===========
 * 文章服务接口
 * ===========
 */


/**
 * 获取文章列表
 * @param {Object} params 参数 {page:1,limit:20}
 */
export async function getWordList(params = {
  page: 1,
  limit: 20
}) {
  return await request('/Word/GetWordListForGood', 'get', params)
}
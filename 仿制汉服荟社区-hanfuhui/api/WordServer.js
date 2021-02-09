import request from '@/api/request.js';
/**
 * ===========
 * 文章服务接口
 * ===========
 */


/**
 * 获取文章列表
 * @param {Object} params 参数 {page:1,count:20}
 */
export async function getWordList(params = {
  page: 1,
  count: 20
}) {
  return await request('/Word/GetWordListForGood', 'get', params)
}

/**
 * 获取文章基本信息
 * @param {Number} id 参数 文章列表中ID
 */
export async function getWordInfo(id = 3960) {
  return await request(`/word/GetWord?id=${id}`)
}

/**
 * 获取文章内容信息
 * @param {Number} id 参数 文章列表中ID
 */
export async function getWordContentHTML(wordid = 3960) {
  return await request(`/word/GetWordContent?wordid=${wordid}`)
}

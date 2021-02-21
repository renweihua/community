import request from '@/api/request.js';
/**
 * ===========
 * 摄影服务接口
 * ===========
 */


/**
 * 摄影榜封面图
 * @param {String} objecttype 参数 album
 */
export async function getRankFace(objecttype = 'album') {
  return await request(`/Ranking/GetRankFace?objecttype=${objecttype}`)
}

/**
 * 获取摄影榜摄影列表
 * @param {Object} params 参数 {ranktype:1,page:1,limit:20}
 * ranktype 1周榜、2月榜
 */
export async function getRankList(params = {
  ranktype: 1,
  page: 1,
  limit: 20
}) {
  return await request('/Ranking/GetRankForAlbum', 'get', params)
}
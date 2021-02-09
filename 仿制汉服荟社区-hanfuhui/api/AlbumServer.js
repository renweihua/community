import request from '@/api/request.js';
/**
 * ===========
 * 摄影服务接口
 * ===========
 */


/**
 * 获取摄影列表
 * @param {Object} params 参数 {page:1,count:20}
 */
export async function getAlbumList(params = {
  page: 1,
  count: 20
}) {
  return await request('/album/GetAlbumListForGood', 'get', params)
}

/**
 * 获取摄影信息
 * @param {Number} id 参数 摄影列表中ID
 */
export async function getAlbumInfo(id = 85857) {
  return await request(`/Album/GetAlbum?id=${id}`)
}

/**
 * 摄影榜封面图
 * @param {String} objecttype 参数 album
 */
export async function getRankFace(objecttype = 'album') {
  return await request(`/Ranking/GetRankFace?objecttype=${objecttype}`)
}

/**
 * 获取摄影榜摄影列表
 * @param {Object} params 参数 {ranktype:1,page:1,count:20}
 * ranktype 1周榜、2月榜
 */
export async function getRankList(params = {
  ranktype: 1,
  page: 1,
  count: 20
}) {
  return await request('/Ranking/GetRankForAlbum', 'get', params)
}
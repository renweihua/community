import request from '@/api/request.js';
/**
 * ===========
 * 视频服务接口
 * ===========
 */


/**
 * 获取推荐视频列表
 * @param {Object} params 参数 {page:1,count:20}
 */
export async function getVideoList(params = {
  page: 1,
  count: 20
}) {
  return await request('/video/GetVideoListForGood', 'get', params)
}

/**
 * 获取视频信息
 * @param {Number} id 参数 视频列表中ID
 */
export async function getVideoInfo(id = 32787) {
  return await request(`/video/GetVideo?id=${id}`)
}

/**
 * 解析视频播放地址
 * @param {String} videourl 参数 视频url地址rsa加密串 
 */
export async function getVideoUrl(videourl = 'bxbzJG9zU=') {
  return await request(`/video/GetUpt?videourl=${videourl}`)
}

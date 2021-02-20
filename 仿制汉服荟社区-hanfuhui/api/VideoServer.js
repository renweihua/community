import request from '@/api/request.js';
/**
 * ===========
 * 视频服务接口
 * ===========
 */

/**
 * 解析视频播放地址
 * @param {String} videourl 参数 视频url地址rsa加密串
 */
export async function getVideoUrl(videourl = 'bxbzJG9zU=') {
  return await request(`/video/GetUpt?videourl=${videourl}`)
}

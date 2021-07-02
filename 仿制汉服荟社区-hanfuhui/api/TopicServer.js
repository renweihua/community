import request from '@/api/request.js';
/**
 * ===========
 * 话题服务接口
 * ===========
 */


/**
 * 获取话题列表
 * @param {Object} params 参数 {page:1,limit:20}
 */
export async function getTopicList(params = {
  page: 1,
  limit: 20
}) {
  return await request('/Topic/GetTopicListForHot', 'get', params)
}

/**
 * 获取话题信息
 * @param {Number} id 参数 话题列表中ID
 */
export async function getTopicInfo(id = 6201) {
  return await request(`/Topic/GetTopic?id=${id}`)
}

/**
 * 话题回复列表
 * @param {Object} params 参数 {topicid:142934,hot:false,page:1,limit:20}
 * hot: 按热度 true，按最新 false
 */
export async function getTopicReplyList(params = {
  topicid: 142934,
  hot: false,
  page: 1,
  limit: 20
}) {
  return await request('/Topic/GetTopicReplyListForTopic', 'get', params)
}

/**
 * 获取话题讨论信息
 * @param {Number} id 参数 话题列表中ID
 */
export async function getTopicReplyInfo(id = 6201) {
  return await request(`/Topic/GetTopicReply?id=${id}`)
}

/**
 * 话题回复评论
 * @param {Object} params 参数
 * images: 是通过upyun上传得到对象，多张组成数组
 * {
  content: '不存在的',
  fromclient: 'android',
  topicid: 6201,
  images: [{
    "height": 1243,
    "width": 828,
    "size": 235850,
    "imgsrc": "/android/2019/6/30/3fd23c3a841748bdbf1202591271ce22.jpg"
  }]
}
 */
export async function addTopicReply(params = {
  content: '不存在的',
  fromclient: 'android',
  topicid: 6201,
  images: [{
    "height": 1243,
    "width": 828,
    "size": 235850,
    "imgsrc": "/android/2019/6/30/3fd23c3a841748bdbf1202591271ce22.jpg"
  }]
}) {
  return await request('/topic/InsertTopicReply', 'post', params)
}

/**
 * 获取用户关注话题消息列表
 * @param {Object} params 参数 {page:1,limit:20}
 */
export async function getTopicUserFollow(params = {
  page: 1,
  limit: 20
}) {
  return await request('/topic/GetTopicListForFollow', 'get', params)
}

/**
 * 话题关注添加
 * @param {Number} id 参数 142934
 */
export async function addTopicFollow(id = 142934) {
  return await request('/Topic/InsertTopicFollow', 'post', {
    id
  })
}

/**
 * 话题关注取消
 * @param {Number} id 参数 142934
 */
export async function delTopicFollow(id = 142934) {
  return await request('/Topic/DeleteTopicFollow', 'post', {
    id
  })
}

/**
 * 获取用户话题黑名单列表
 * @param {Object} params 参数 {page:1,limit:20}
 */
export async function getTopicBlackList(params = {
  page: 1,
  limit: 20
}) {
  return await request('/Topic/GetBlackTopicList', 'get', params)
}

/**
 * 用户话题黑名单添加
 * @param {Number} id 参数 142934
 */
export async function addTopicBlack(id = 142934) {
  return await request('/Topic/InsertTopicBlack', 'post', {
    id
  })
}

/**
 * 用户话题黑名单移出
 * @param {Number} id 参数 142934
 */
export async function delTopicBlack(ids = 142934) {
  return await request('/Topic/DeleteTopicBlack', 'post', {
    ids
  })
}

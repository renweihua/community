import request from '@/api/request.js';
/**
 * ===========
 * 消息服务接口
 * ===========
 */


/**
 * 获取未读消息数
 */
export async function getMessageNoReadCount() {
  return await request('/notify/unread')
}

/**
 * 获取用户公告消息列表
 */
export async function getMessageNoticeList() {
  return await request('/message/GetNoticeListAndClear')
}

/**
 * 获取用户点赞、评论、提醒消息列表
 * @param {Object} params 参数 {page,count,type }
 * 类型 top 点赞、comment 评论、remind 提醒、
 */
export async function getMessageListByType(params = {
  page: 1,
  limit: 20,
  type: 'comment'
}) {
  return await request('/message/GetMessageList', 'get', params)
}

import request from '@/api/request.js';
/**
 * ===========
 * 动态互动服务接口
 * ===========
 */

/**
 * 获取动态点赞用户列表
 * @param {Object} params 参数 {objectid:2485094,objecttype:'trend',page:1,count:20}
 * objecttype： album 摄影、trend 趋势动态、topicreply 话题、video 视频
 * objectid： 动态列表中ID或者ObjectID
 */
export async function getTopList(params = {
  objectid: 2485094,
  objecttype: 'trend',
  page: 1,
  count: 20
}) {
  return await request('/Interact/GetTopList', 'get', params)
}

/**
 * 动态点赞添加
 * @param {Object} params 参数 {objecttype:'trend',objectid:2485094}
 * objecttype： album 摄影、trend 趋势动态、topicreply 话题、video 视频
 * objectid： 动态列表中ID或者ObjectID
 */
export async function addTop(params = {
  objecttype: 'trend',
  objectid: 2485094
}) {
  return await request('/Interact/InsertTop', 'post', params)
}

/**
 * 动态点赞取消
 * @param {Object} params 参数 {objecttype:'trend',objectid:2485094}
 * objecttype： album 摄影、trend 趋势动态、topicreply 话题、video 视频
 * objectid： 动态列表中ID或者ObjectID
 */
export async function delTop(params = {
  objecttype: 'trend',
  objectid: 2485094
}) {
  return await request('/Interact/DeleteTop', 'post', params)
}

/**
 * 获取动态评论信息列表
 * @param {Object} params 参数 {objectid:2485094,objecttype:trend,page:1,count:20}
 * objecttype： album 摄影、trend 趋势动态、topicreply 话题、video 视频
 * objectid： 动态列表中ID或者ObjectID
 */
export async function getCommentList(params = {
  objectid: 2485094,
  objecttype: 'trend',
  page: 1,
  count: 20
}) {
  return await request('/Interact/GetCommentListByTop', 'get', params)
}

/**
 * 动态评论发表/回复信息添加
 * @param {Object} params 参数 {objecttype:'trend',objectid:2492160,parentid:8783984,content:'居然成这种片[大笑]',fromclient:'android'}
 * objecttype： album 摄影、trend 趋势动态、topicreply 话题、video 视频
 * objectid： 动态列表中ID或者ObjectID
 * parentid：针对评论进行回复需要评论列表中ID，普通发表就不加
 */
export async function addComment(params = {
  objecttype: 'trend',
  objectid: 2492160,
  parentid: 8783984,
  content: '居然成这种片[大笑]',
  fromclient: 'android'
}) {
  return await request('/Interact/InsertComment', 'post', params)
}

/**
 * 动态评论发表/回复信息删除
 * @param {Number} ids 参数 objectid 8784023
 * objectid： 动态列表中ID或者ObjectID
 */
export async function delComment(ids = 8784023) {
  return await request('/Interact/DeleteComment', 'post', {
    ids
  })
}

/**
 * 动态评论信息点赞添加
 * @param {Number} ids 参数 objectid 8783858
 * objectid： 动态列表中ID或者ObjectID
 */
export async function addCommentTop(id = 8783858) {
  return await request('/Interact/InsertCommentTop', 'post', {
    id
  })
}

/**
 * 动态评论信息点赞取消
 * @param {Number} ids 参数 objectid 8783858
 * objectid： 动态列表中ID或者ObjectID
 */
export async function delCommentTop(id = 8783858) {
  return await request('/Interact/DeleteCommentTop', 'post', {
    id
  })
}

/**
 * 获取用户收藏信息列表
 * @param {Object} params 参数 {objecttype:trend,page:1,count:20}
 * objecttype：word 文章、video 视频、album 摄影、全部就留空或不传
 * objectid： 动态列表中ID或者ObjectID
 */
export async function getUserSaveList(params = {
  objecttype: 'trend',
  page: 1,
  count: 20
}) {
  return await request('/Interact/GetSaveList', 'get', params)
}

/**
 * 动态用户收藏添加
 * @param {Number} ids 参数 objectid 8783858
 * objecttype：word 文章、video 视频、album 摄影、全部就留空或不传
 * objectid： 动态列表中ID或者ObjectID
 */
export async function addSave(params = {
  objectid: 30952,
  objecttype: 'video'
}) {
  return await request('/Interact/InsertSave', 'post', params)
}

/**
 * 动态用户收藏取消
 * @param {Number} ids 参数 objectid 8783858
 * objecttype： word 文章、video 视频、album 摄影、全部就留空或不传
 * objectid： 动态列表中ID或者ObjectID
 */
export async function delSave(params = {
  objectid: 30952,
  objecttype: 'video'
}) {
  return await request('/Interact/DeleteSave', 'post', params)
}

/**
 * 获取动态评论项多评论详细列表
 * @param {Object} params 参数 {parentid:9350944,page:1,count:20} 
 * objectid： 评论动态列表中ID或者ObjectID
 */
export async function getCommentListByID(params = {
  parentid: 9350944,
  page: 1,
  count: 20
}) {
  return await request('/Interact/GetCommentListForParent', 'get', params)
}

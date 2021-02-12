import request from '@/api/request.js';
/**
 * ===========
 * 动态互动服务接口
 * ===========
 */

/**
 * 获取动态的点赞用户列表
 *
 * @param {Object} params 参数 {dynamic_id:0,page:1,limit:10}
 *
 * dynamic_id： 动态Id
 */
export async function getDynamicPraises(params = {
	page: 1,
	limit: 10
}) {
	return await request('/dynamic/getPraises', 'get', params);
}

/**
 * 动态点赞接口
 *
 * dynamic_id： 动态的Id
 */
export async function dynamicPraise(dynamic_id) {
	return await request('/dynamic/praise', 'post', {
		dynamic_id: dynamic_id
	})
}

/**
 * 动态：获取评论列表
 * @param {Object} params 参数 {dynamic_id:0,page:1,limit:20}
 *
 * dynamic_id： 动态Id
 */
export async function getCommentList(params = {
	dynamic_id: 0,
	page: 1,
	limit: 20
}) {
	return await request('/dynamic/comments', 'get', params)
}

/**
 * 动态评论发表/回复信息添加
 * @param {Object} params 参数 {,dynamic_id:0,reply_id:0,content:'居然成这种片[大笑]'}
 * dynamic_id： 动态Id
 * reply_id：针对评论进行回复需要评论列表中ID，普通发表就不加
 */
export async function addComment(params = {
	dynamic_id: 0,
	reply_id: 0,
	content: '居然成这种片[大笑]',
}) {
	return await request('/dynamic/comment', 'post', params)
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
 * @param {Object} params 参数 {objecttype:trend,page:1,limit:20}
 * objecttype：word 文章、video 视频、album 摄影、全部就留空或不传
 * objectid： 动态列表中ID或者ObjectID
 */
export async function getUserSaveList(params = {
	objecttype: 'trend',
	page: 1,
	limit: 20
}) {
	return await request('/Interact/GetSaveList', 'get', params)
}

/**
 * 动态：收藏
 *
 * dynamic_id： 动态Id
 */
export async function dynamicCollection(dynamic_id) {
	return await request('/dynamic/collection', 'post', {
		dynamic_id: dynamic_id
	})
}

/**
 * 获取动态评论项多评论详细列表
 * @param {Object} params 参数 {comment_id:0,page:1,limit:20}
 * comment_id： 评论的Id
 */
export async function getMoreComments(params = {
	comment_id: 0,
	page: 1,
	limit: 20
}) {
	return await request('/dynamic/loadMoreComments', 'get', params)
}

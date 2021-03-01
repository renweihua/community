import request from '@/api/request.js';
/**
 * ===========
 * 用户服务接口
 * ===========
 */


/**
 * 获取登录会员：关注用户的列表
 * @param {Object} params 参数 {page:1,limit:20}
 */
export async function getFollowsList(params = {
	page: 1,
	limit: 20
}) {
	return await request('/user/follows', 'get', params)
}

/**
 * 关注指定会员
 *
 * @param {Number} user_id 参数
 * user_id：用户ID
 */
export async function followUser(user_id) {
	return await request('/user/follow', 'post', {
		user_id
	})
}

/**
 * 获取用户拉黑信息列表
 * @param {Object} params 参数 {page:1,limit:20}
 */
export async function getUserBlackList(params = {
	page: 1,
	limit: 20
}) {
	return await request('/User/GetUserBlackList', 'get', params)
}

/**
 * 用户黑名单添加
 * @param {Number} blackuserid 参数 65910
 * blackuserid：用户ID
 */
export async function addUserBlack(blackuserid = 65910) {
	return await request('/user/InsertUserBlack', 'post', {
		blackuserid
	})
}

/**
 * 用户黑名单移除
 * @param {Number} blackuserid 参数 65910
 * blackuserid：用户ID
 */
export async function delUserBlack(blackuserid = 65910) {
	return await request('/user/DeleteUserBlack', 'post', {
		blackuserid
	})
}

/**
 * 检查用户是否在黑名单中
 * 
 * @param {Number} user_id 参数 
 * user_id：用户ID
 */
export async function getUserExistsBlack(user_id) {
	return await request('/user/getUserBlackExists', 'get', {
		user_id
	})
}

/**
 * 获取指定会员的信息
 */
export async function getUserInfo(user_id) {
	return await request('/user/detail', 'get', {
		user_id
	})
}

/**
 * 获取用户发布信息列表
 * @param {Object} params 参数 {userid,objecttype,page:1,limit:20}
 * objecttype 全部可不传或传空
 */
export async function getUserPublishList(params = {
	userid: 26081,
	objecttype: '',
	page: 1,
	limit: 20
}) {
	return await request('/trend/GetTrendListForUserID', 'get', params)
}

/**
 * 获取用户点赞过信息列表
 * @param {Object} params 参数 {userid,page:1,limit:20}
 */
export async function getUserTopList(params = {
	userid: 26081,
	page: 1,
	limit: 20
}) {
	return await request('/Interact/GetTrendListForTop', 'get', params)
}

/**
 * 编辑个人资料
 */
export async function modifyUserInfo(params) {
	return await request('/user/update', 'put', params)
}

/**
 * 更新用户中心背景图
 * 
 * @param {String}  = '/android/2019/6/31/34050.jpg' 参数 上传图片后的到的url
 */
export async function modifyUserMainBgPic(background_cover) {
	return await request('/user/updateBackgroundCover', 'put', {
		background_cover
	})
}

/**
 * 用户手机号登录
 * @param {String} usersecret 参数 login_account,login_pwd 进行加密得到
 * Rsa加密
 */
export async function login(params) {
	return await request('/auth/login', 'post', params)
}


/**
 * 获取登录会员的信息
 */
export async function getLoginUserInfo() {
	return await request('/auth/me', 'post')
}

/**
 * 退出登录
 */
export async function logout() {
	return await request('/auth/logout', 'post')
}


/**
 * 获取用户登录APPToken
 * @param {String} signature 参数 signature进行加密得到
 * 375fe0b80e7c40e9b462865a55a36156,时间戳秒级 进行 Rsa加密
 */
export async function getUserAppToken(signature = '8ba6280==') {
	return await request('/account/GetAppToken', 'get', {
		signature
	})
}

/**
 * 发送手机短信验证码
 * @param {Objucet} params 参数 {apptoken,isnew,phone}
 * Rsa加密
 */
export async function getPhoneCode(params = {
	apptoken: 'ee736',
	isnew: false,
	phone: 18866478914
}) {
	return await request('/account/SendPhoneCode', 'post', params)
}

/**
 * 用户修改密码
 * @param {Objucet} params 参数 {code,phonesecret}
 * phonesecret: phone,password 进行 Rsa加密
 */
export async function modifyPassword(params = {
	code: 8680,
	phonesecret: 'DP364=='
}) {
	return await request('/account/UpdatePassword', 'post', params)
}

/**
 * 注册
 */
export async function register(params) {
	return await request('/auth/register', 'post', params)
}

/**
 * 用户刷新Token
 * @param {String} secret 参数 ras加密
 * secret：token,ServerTime 字符进行加密 Math.floor(new Date().getTime() / 1000)
 */
export async function refreshUserToken(secret = 'lo8cc=') {
	return await request('/account/RefreshToken', 'post', {
		secret
	})
}

/**
 * 用户更新绑定手机号
 * @param {Object} params 参数 {phonesecret,code}
 * phonesecret：new_phone,password 进行加密得到
 * code短信验证码
 */
export async function modifyBindPhone(params = {
	phonesecret: 'sdfv==',
	code: 1323,
}) {
	return await request('/account/BindUserPhone', 'post', params)
}

/**
 * 登录会员的粉丝列表
 * @param {Object} params 参数 {page,count}
 * secret：token,ServerTime 字符进行加密 Math.floor(new Date().getTime() / 1000)
 */
export async function getUserFansList(params = {
	page: 1,
	limit: 20
}) {
	return await request('/user/fans', 'get', params)
}

/**
 * 用户粉丝移除
 * @param {Number} fansuserids 参数 粉丝用户ID
 */
export async function delFans(fansuserids = 1371760) {
	return await request('/User/DeleteFans', 'post', {
		fansuserids
	})
}

/**
 * 隐藏主页赞过
 * @param {Boolean} state 参数 true/false
 */
export async function modifyHideTop(state = false) {
	return await request('/User/UpdateHideTop', 'post', {
		state
	})
}

/**
 * 获取网易IM云信TOKEN值
 */
export async function getNeteaseIMToken() {
	return await request('/IM/GetNeteaseToken')
}

/**
 * 获取用户聊天设置状态
 */
export async function getChatState() {
	return await request('/user/GetShowIM')
}

/**
 * 修改聊天设置状态
 * @param {Number} state 参数 1
 * 1 接收所有人聊天  2 仅接受关注的人与认证账号聊天
 */
export async function modifyChatState(state = 1) {
	return await request('/User/UpdateShowIM', 'post', {
		state
	})
}

import request from '@/api/request.js';
/**
 * ===========
 * 用户服务接口
 * ===========
 */


/**
 * 获取用户关注用户列表
 * @param {Object} params 参数 {userid:985319,page:1,limit:20}
 */
export async function getUserAtteUserList(params = {
  userid: 985319,
  page: 1,
  limit: 20
}) {
  return await request('/User/GetUserListForAtte', 'get', params)
}

/**
 * 用户关注添加
 * @param {Number} atteuserids 参数 137
 * atteuserids：用户ID
 */
export async function addUserAtte(atteuserids = 137) {
  return await request('/User/InsertAttentions', 'post', {
    atteuserids
  })
}

/**
 * 用户关注取消
 * @param {Number} atteuserids 参数 137
 * atteuserids：用户ID
 */
export async function delUserAtte(atteuserids = 137) {
  return await request('/User/DeleteAttentions', 'post', {
    atteuserids
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
 * @param {Number} userid 参数 26081
 * userid：用户ID
 */
export async function getUserExistsBlack(userid = 26081) {
  return await request('/User/GetUserBlackExists', 'get', {
    userid
  })
}

/**
 * 获取登录会员的信息
 */
export async function getUserInfo() {
  return await request('/auth/me', 'post')
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
 * 更新用户信息
 * @param {Object} params 参数 {option,value}
 * option 操作顺序 1头像 2昵称 3性别 4地区 5签名
 */
export async function modifyUserInfo(params = {
  'option': '4',
  'value': '121'
}) {
  return await request('/User/UpdateUser', 'post', params)
}

/**
 * 更新用户中心背景图
 * @param {String} mainbg 参数 上传图片后的到的url
 */
export async function modifyUserMainBgPic(mainbg = '/android/2019/6/31/34050.jpg') {
  return await request('/user/UpdateUserMainBg', 'post', {
    mainbg
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
 * 退出登录
 */
export async function logout() {
  return await request('/auth/login', 'post')
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
 * 用户通过手机号注册
 * @param {Object} params 参数 {phonesecret,code}
 * phonesecret：login_account,login_pwd 进行加密得到
 * code短信验证码
 */
export async function registerByPhone(params = {
  code: 5653,
  phonesecret: 'ET=='
}) {
  return await request('/account/RegisterForPhonePwd', 'post', params)
}

/**
 * 用户注册后绑定用户信息
 * @param {Object} params 参数 {tempkey,nickname,headurl,gender}
 * tempkey：缓存键在手机验证注册后得到
 * nickname：昵称唯一，检测通过为成功注册
 * headurl 默认图、又拍云上传图
 */
export async function registerBindUser(params = {
  tempkey: '1084f9ac30ef4154a2f920f7af9193b6',
  nickname: '连连银月',
  headurl: '/pc/2015/12/3/21/b7a0c03d449e4110863b9f804bdf8c38.jpg',
  gender: '女'
}) {
  return await request('/account/RegisterForPhoneUser', 'post', params)
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
 * 用户的粉丝列表
 * @param {Object} params 参数 {userid,page,count}
 * secret：token,ServerTime 字符进行加密 Math.floor(new Date().getTime() / 1000)
 */
export async function getUserFansList(params = {
  userid: 985319,
  page: 1,
  limit: 20
}) {
  return await request('/User/GetUserListForFans', 'get', params)
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

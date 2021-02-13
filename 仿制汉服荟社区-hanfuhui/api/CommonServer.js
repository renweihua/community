import request from '@/api/request.js';
/**
 * ===========
 * 常见公共服务接口
 * ===========
 */

/**
 * 批量上传文件
 * 
 * @param {Object} upload_files
 */
export async function batchUploads(upload_files) {
	let files = [];
	let login_token = uni.getStorageSync('TOKEN') || '';
	upload_files.forEach((value, key) => {
		files.push({
			'name': 'files[]',
			'file': value,
		})
	})
	return new Promise((resolve, reject) => {
		uni.uploadFile({
			url: 'http://community.cnpscy.com/api/upload_files', //仅为示例，非真实的接口地址
			header: {
				'Authorization': !login_token ? '' : ('bearer ' + login_token),
			},
			files: files,
			success: (res) => {
				res = JSON.parse(res.data);
				uni.showToast({
					title: res.msg,
					icon: 'none',
				});
				resolve(res.data);
			},
			fail() {
				uni.showToast({
					title: res.msg,
					icon: 'error',
				});
				reject(err)
			}
		});
	});
}




/**
 * 加密需要解析的字符串 -视频、密码
 * @param {String} text 需要加密的字符串
 */
export async function getRsaText(text = '你好') {
	let [err, res] = await uni.request({
		// url: `https://www.hanfuhui.com/Home/Rsa?text=${text}`,
		url: `http://easy-mock.liuup.com/mock/5df764250a2f9f42cfec1a50/api5.hanfugou.com/Rsa?text=${text}`,
		method: 'GET',
		header: {
			'hanfuhui_fromclient': 'PC',
			'hanfuhui_token': uni.getStorageSync('TOKEN') || '',
		}
	})
	return err ? "" : res
}

/**
 * 主页banner与热门话题
 */
export async function getBannerTopicList() {
	return await request('/system/GetDefault')
}

/**
 * APP启动封面图
 */
export async function getStarCover() {
	return await request('/start_diagrams')
}

/**
 * APP启动检查更新
 * @param {Object} params 参数 {num:65,client:'android' }
 * num 版本号
 */
export async function getAppNewVersion(params = {
	num: 65,
	client: 'android'
}) {
	return await request('/system/GetAppVersionForNew', 'get', params)
}

/**
 * 获取又拍云图片上传授权
 * @param {Object} params 参数 {filesize:62381,filetype:'jpg' }
 */
export async function getUpyunAuth(params = {
	filesize: 62381,
	filetype: 'jpg'
}) {
	return await request('/Upload/GetUpyunAuthention', 'get', params)
}

/**
 * 动态信息举报
 * @param {Number} ids 参数 {objecttype:'comment',objectid:8783590,touserid:1375494,reason:'怠惰举报，没事开个玩笑。'}
 * objecttype： album 摄影、trend 趋势动态、topicreply 话题、video 视频
 * objectid： 列表中ID或者ObjectID
 * touserid：举报用户ID
 * reason：举报内容
 */
export async function addReport(params = {
	objecttype: 'comment',
	objectid: 8783590,
	touserid: 1375494,
	reason: '怠惰举报，没事开个玩笑。'
}) {
	return await request('/system/InsertReport', 'post', params)
}

/**
 * 获取省市列表
 * @param {Number} parentid 参数 0 省列表 、 通过省ID的市列表
 */
export async function getCityList(parentid = 0) {
	return await request('/Base/GetCityList', 'get', {
		parentid
	})
}

/**
 * 获取软件一些协议规则
 * @param {Number} key 参数
 * hui_rule 社区公约、 hanbirule 汉币规则 orgjoin 组织入驻协议、shopjoin 商家入驻协议
 */
export async function getSysParamValue(key = 'hanbirule') {
	return await request('/System/GetSysParamValue', 'get', {
		key
	})
}

/**
 * 获取排行榜列表
 * @param {Object} params 参数 {page,count,type}
 * type album 摄影、hanbi 汉币、popularity 人气、signin 签到、
 */
export async function getRankList(params = {
	page: '1',
	limit: '20',
	type: 'album'
}) {
	return await request('/user/GetUserListForRank', 'get', params)
}

/**
 * 获取处罚公示列表
 * @param {Object} params 参数 {page,count}
 */
export async function getUserViolationList(params = {
	page: '1',
	limit: '20'
}) {
	return await request('/User/GetUserViolationList', 'get', params)
}

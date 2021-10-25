/**
 * 统一跳转
 */
function navigateTo(url) {
	uni.navigateTo({
		url: url,
		animationType: 'pop-in',
		animationDuration: 300
	})
}

/**
 * 登录页
 */
function jumpToLogin(){
	return navigateTo('/pages/login/login')
}

/**
 * @param {Object} url
 */
function switchTabTo(url) {
	uni.switchTab({
		url: url,
		animationType: 'pop-in',
		animationDuration: 300
	})
}

//操作成功后，的提示信息
function successToShow(msg = '保存成功', callback = function() {}) {
	uni.showToast({
		title: msg,
		icon: 'success',
		duration: 1000
	})
	setTimeout(function() {
		callback()
	}, 500)
}

//操作失败的提示信息
function errorToShow(msg = '操作失败', callback = function() {}) {
	uni.showToast({
		title: msg,
		icon: 'none',
		duration: 1500
	})
	setTimeout(function() {
		callback()
	}, 1500)
}

/**
 * 图片预览
 */
function previewImage(index, url) {
	uni.previewImage({
		current: index,
		urls: url,
		loop: true
	});
}


export {
	navigateTo,
	switchTabTo,
	successToShow,
	errorToShow,
	previewImage,
	jumpToLogin
}

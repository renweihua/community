//取值
function get(key,sync = true) {
    try {
		if(sync){
			return uni.getStorageSync(key);
		}else{
			let data = '';
			uni.getStorage({
				key:key,
				success: function (res) {
					data = res.data;
				}
			});
			return data;
		}
    } catch (e) {
        return false;
    }
}

//赋值
function set(key, value, sync = true) {
    try {
        if (sync) {
            return uni.setStorageSync(key, value);
        } else {
            uni.setStorage({
                key: key,
                data: value
            });
        }
    } catch (e) {

    }
}

//移除
function del(key, sync = true){
    try {
        if (sync) {
            return uni.removeStorageSync(key);
        } else {
            uni.removeStorage({
                key: key
            });
        }
    } catch (e) {
        return false;
    }
}

//清空
function clear(sync = true){
    try {
        if (sync) {
            return uni.clearStorageSync();
        } else {
            uni.clearStorage();
        }
    } catch (e) {
        return false;
    }
}

//获取用户token，如果缓存有，直接返回，如果没有，就先微信登陆，然后服务器登陆，最后返回token
function token() {
    var userinfo = get('userinfo');
    return userinfo.token || false;
}

// 获取用户信息
function userInfo() {
	var userinfo = get('userinfo');
	return userinfo || false;
}

// 底部栏
function tabbar()
{
	return get('tabbar');
}

export {
    get,
    set,
    del,
    clear,
    token,
	userInfo,
	tabbar
}
/**
 * 跳转动态详情
 * 
 * @param {Object} dynamic
 * @param {Object} _this
 */
export function getDynamicDetailPage(dynamic, _this){
	console.log(dynamic);
	switch(dynamic.dynamic_type){
		case 0: // 动态
			uni.navigateTo({
				url: `/pages/trend-details/trend-details?dynamic_id=${dynamic.dynamic_id}&fromPage=home&current=${_this.current}`
			})
			break;
		case 1: // 图文
			uni.navigateTo({
				url: `/pages/trend-details/trend-details?dynamic_id=${dynamic.dynamic_id}&fromPage=home&current=${_this.current}`
			})
			break;
		case 2: // 视频
			uni.navigateTo({
				url: `/pages/video-details/video-details?dynamic_id=${dynamic.dynamic_id}&fromPage=home&current=${_this.current}`
			})
			break;
		case 3: // 相册/摄影
			uni.navigateTo({
				url: `/pages/album-details/album-details?dynamic_id=${e.dynamic_id}&fromPage=home&current=${this.current}`
			})
			break;
	}
}

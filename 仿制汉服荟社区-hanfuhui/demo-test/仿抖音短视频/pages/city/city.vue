<template>
	<view class="page" :style="{ height: height }"><video-list :dataList="videoList"></video-list></view>
</template>

<script>
import videoList from '@/components/list/list.vue';
export default {
	components: {
		videoList
	},
	data() {
		return {
			videoList: []
		};
	},
	onLoad() {
		var res = uni.getSystemInfoSync();
		this.sysheight = res.windowHeight;
		this.height = `${this.sysheight}px`;
		let width = res.windowWidth;
		this.width = `${width}px`;
		console.log(res);
		this.getVideoList();
	},
	onPullDownRefresh() {
		console.log('refresh');
		this.getVideoList(true);
		uni.stopPullDownRefresh();
	},
	methods: {
		getVideoList(refresh) {
			uni.request({
				url: 'http://api.52170.xin/video?page_size=20',
				dataType: 'json',
				success: res => {
					console.log(res);
					if (refresh) this.videoList = [];
					for (let item of res.data.data.list) {
						this.videoList.push({
							video_id: item.video_id,
							nickname: item.nickname,
							video_describe: item.video_describe,
							cover_url: item.cover_url,
							video_url: item.video_url,
							dianzan: item.dianzan,
							pinglun: item.pinglun,
							zhuanfa: item.zhuanfa,
							flag: false,
							check: false
						});
					}
				}
			});
		}
	}
};
</script>
<style>
.page {
	background-color: #161922;
}
</style>

<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni :down="{ use: true }" @up="upCallback">
			<view class="plr18r">
				<block v-for="(item, index) in signinListData" :key="index">
					<view class="flexr-jsb hl90r bbs2r">
						<!-- <view class="f28r c555">
							{{ calDatetime(item.created_time) }}
							<text class="bgtheme cwhite br8r plr8r ml8r" v-if="!item.Hanbi">补</text>
						</view>
						<view class="f32r cgray">第{{ item.TodayRanking }}个签到，汉币 {{ item.Hanbi }} 个</view> -->
						
						
						<view class="f28r c555">
							{{ item.created_ip }}
						</view>
						<view class="f32r cgray">{{ calDatetime(item.created_time) }}</view>
					</view>
				</block>
			</view>
		</mescroll-uni>
	</view>
</template>

<script>
import { getSignInList } from '@/api/HanbiServer.js';

export default {
	computed: {
		// 签到日期记录数据
		signinListData() {
			return this.$store.getters['getSigninListData'];
		}
	},
	data() {
		return {
			search_month: ''
		};
	},
	onLoad() {
		let date = new Date();
		this.search_month = date.getFullYear() + '-' + (date.getMonth() + 1);
		console.log(this.search_month);
	},
	methods: {
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			getSignInList({
				search_month: this.search_month,
				page: mescroll.num,
				limit: mescroll.size
			})
				.then(res => {
					let lists = res.data;
					
					if (mescroll.num == 1) {
						this.$store.commit('setSigninListData', lists.data);
					} else {
						this.$store.commit('setSigninListData', this.signinListData.concat(lists.data));
					}
					
					/**
					 * 如果当前月份记录查询完成，那么继续查询上一个月份的
					 */
					let search_month = this.search_month;
					this.search_month = lists.month_table;
					
					if(lists.data.length <= 0 && search_month == this.search_month){
						// 数据加载完毕
						mescroll.endSuccess(0, false);
					}else{
						mescroll.endSuccess(lists.per_page, true);
					}
				})
				.catch(() => {
					mescroll.endErr();
				});
		},
		/// 计算格式时间 2019-02-02
		calDatetime(str) {
			let _data = new Date(str * 1000);
			let year = _data.getFullYear(); //年
			let month = _data.getMonth() + 1; //月
			let day = _data.getDate(); //日
			// 个位补零
			month = month < 10 ? '0' + month : month;
			day = day < 10 ? '0' + day : day;
			return `${year}-${month}-${day}`;
		}
	}
};
</script>

<style>
page {
	background: #ffffff;
}
</style>

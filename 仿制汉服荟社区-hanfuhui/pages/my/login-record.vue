<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni :down="{ use: true }" @up="upCallback">
			<view class="plr18r">
				<block v-for="(item, index) in lists" :key="index">
					<view class="flexr-jsb hl90r bbs2r">
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
import { getLoginLogs } from '@/api/my.js';
import { fnFormatLocalDate, getYearMonth } from '@/utils/CommonUtil.js';

export default {
	data() {
		return {
			lists:[],
			search_month: ''
		};
	},
	onLoad() {
		this.search_month = getYearMonth();
	},
	methods: {
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			getLoginLogs({
				search_month: this.search_month,
				page: mescroll.num,
				limit: mescroll.size
			})
				.then(res => {
					let lists = res.data;
					
					this.lists = this.lists.concat(lists.data);
					
					/**
					 * 如果当前月份记录查询完成，那么继续查询上一个月份的
					 */
					this.search_month = lists.month_table;
					// 如果月份不一致，那么page需要重置
					if(search_month != this.search_month){
						mescroll.setPageNum(1);
					}
					console.log(mescroll)
					
					if (lists.data.length <= 0 && search_month == this.search_month) {
						// 数据加载完毕
						mescroll.endSuccess(0, false);
					} else {
						mescroll.endSuccess(lists.per_page, true);
					}
				})
				.catch(() => {
					mescroll.endErr();
				});
		},
		/// 计算格式时间
		calDatetime(str) {
			return fnFormatLocalDate(str * 1000);
			// let _data = new Date(str * 1000);
			// let year = _data.getFullYear(); //年
			// let month = _data.getMonth() + 1; //月
			// let day = _data.getDate(); //日
			// // 个位补零
			// month = month < 10 ? '0' + month : month;
			// day = day < 10 ? '0' + day : day;
			// return `${year}-${month}-${day}`;
		}
	}
};
</script>

<style>
page {
	background: #ffffff;
}
</style>

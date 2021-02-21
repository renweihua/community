<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni @down="downCallback" @up="upCallback">
			<block v-for="(item,index) in remindList" :key="index">
				<view class="plr18r ptb18r bgwhite mb18r">
					<view v-if="item.sender" class="flex plr18r ptb18r bbs2r">
						<user-avatar @click="fnUserInfo(item.sender)" :src="item.sender.user_avatar" tag="" size="md"></user-avatar>
						<view class="flexc-jsa ml28r">
							<view>
								<text class="f28r fbold mr18r">{{ item.sender.nick_name }}</text>
								<i-icon v-if="[0, 1].indexOf(item.sender.user_sex) > -1" :type="item.sender.user_sex_text == '男' ? 'nan':'nv' "
								 size="28" :color="item.sender.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
							</view>
							<view class="f24r cgray">{{calDateTime(item.created_time)}}</view>
						</view>
					</view>
					<view v-else class="mautoblock fcenter ptb8r plr18r f24r cgray br8r mb18r">
						{{calDateTime(item.created_time)}}
					</view>


					<view v-if="item.sender" class="fword f28r c555 mt18r">{{item.explain}}</view>
					<view v-else class="plr18r ptb18r fword f28r c111 bgwhite br8r">
						{{item.explain}}
					</view>
				</view>
			</block>
		</mescroll-uni>
	</view>
</template>

<script>
	import {
		fnFormatLocalDate,
		getYearMonth
	} from "@/utils/CommonUtil.js"
	import {
		getSystemByNotify,
	} from "@/api/MessageServer.js"

	export default {
		data() {
			return {
				// 提醒数据列表
				remindList: [],
				search_month: ''
			}
		},
		onLoad() {
			this.search_month = getYearMonth();
		},
		methods: {
			/// 下拉刷新的回调
			downCallback(mescroll) {
				// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
				mescroll.resetUpScroll()
			},
			/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
			upCallback(mescroll) {
				let search_month = this.search_month;
				getSystemByNotify({
					search_month: search_month,
					page: mescroll.num,
					limit: mescroll.size,
				}).then(res => {
					let lists = res.data;

					// 更新未读消息数量
					if (lists.set_read_nums) {
						let newsCount = this.$store.getters['getNewsCountData'];
						newsCount.system_unreads = newsCount.system_unreads - lists.set_read_nums;
						this.$store.commit('setNewsCountData', newsCount);
					}

					this.remindList = this.remindList.concat(lists.data);

					/**
					 * 如果当前月份记录查询完成，那么继续查询上一个月份的
					 */
					this.search_month = lists.month_table;
					// 如果月份不一致，那么page需要重置
					if (search_month != this.search_month) {
						mescroll.setPageNum(1);
					}

					if (lists.data.length <= 0 && search_month == this.search_month) {
						// 数据加载完毕
						mescroll.endSuccess(0, false);
					} else {
						mescroll.endSuccess(lists.per_page, true);
					}
				}).catch(() => {
					mescroll.endErr();
				})
			},
			/// 计算时间格式 2019-09-09 19:19
			calDateTime(str) {
				return fnFormatLocalDate(str * 1000)
			},
			/// 跳转用户信息页
			fnUserInfo(e) {
				uni.navigateTo({
					url: `/pages/user-info/user-info?user_id=${e.user_id}`
				})
			},
		}
	}
</script>

<style>
</style>

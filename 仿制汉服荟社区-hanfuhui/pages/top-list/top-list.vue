<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni @down="downCallback" @up="upCallback">
			<block v-for="(item,index) in topListData" :key="index">
				<view class="flex plr18r ptb28r bbs2r" @tap="fnUserInfo(item.user_info)">
					<user-avatar :src="item.user_info.user_avatar" size="md"></user-avatar>
					<view class="flexc-jsa ml28r">
						<view>
							<text class="f28r fbold mr18r">{{item.user_info.nick_name}}</text>
							<i-icon v-if="[0, 1].indexOf(item.user_info.user_sex) > -1" :type="item.user_info.user_sex_text == '男' ? 'nan':'nv' "
							 size="28" :color="item.user_info.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
						</view>
						<view class="f24r cgray">{{fnDate(item.created_time)}}</view>
					</view>
				</view>
			</block>
		</mescroll-uni>
	</view>
</template>

<script>
	import {
		fnFormatDate
	} from "@/utils/CommonUtil.js"
	import {
		getDynamicPraises
	} from "@/api/InteractServer.js"

	export default {
		data() {
			return {
				dynamic_id: 0,
			}
		},
		computed: {
			// 动态点赞列表数据
			topListData() {
				return this.$store.getters['interact/getDynamicPraisesData']
			},
		},
		onLoad(options) {
			if (options && options.dynamic_id) {
				this.dynamic_id = parseInt(options.dynamic_id);
			}
		},
		methods: {
			/// 下拉刷新的回调
			downCallback(mescroll) {
				// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
				mescroll.resetUpScroll();
			},
			/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
			upCallback(mescroll) {
				getDynamicPraises({
					dynamic_id: this.dynamic_id,
					page: mescroll.num,
					limit: mescroll.size
				}).then(res => {
					let lists = res.data;
					
					if (mescroll.num == 1) {
						this.$store.commit('interact/setTopListData', lists.data)
					} else {
						this.$store.commit('interact/setTopListData', this.topListData.concat(lists.data))
					}
					mescroll.endSuccess(lists.data.length, mescroll.num < lists.count_page);
				}).catch(() => {
					mescroll.endErr();
				})
			},
			/// 跳转用户信息页
			fnUserInfo(e) {
				uni.navigateTo({
					url: `/pages/user-info/user-info?user_id=${e.ID}`
				})
			},
			/// 格式化时间
			fnDate(str) {
				return fnFormatDate(str)
			}
		}
	}
</script>

<style>
	page {
		background: #FFFFFF;
	}
</style>

<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni @down="downCallback" @up="upCallback">
			<block v-for="(item, index) in userFansListData" :key="index">
				<uni-swipe-action>
					<uni-swipe-action-item :options="options" @click="fnSwip(fans)">
						<view class="flex flex-gitem plr18r ptb18r bgwhite bbs2r">
							<user-avatar @click="fnUserInfo(item.user_info.user_id)" :src="item.user_info.user_avatar ? item.user_info.user_avatar : '/static/default_avatar.png'"
							 tag="" size="md"></user-avatar>
							<view class="flexc-jsa ml18r mr28r flex-gitem w128r">
								<view>
									<text class="f28r fbold mr18r">{{item.user_info.nick_name}}</text>
									<i-icon v-if="[0, 1].indexOf(item.user_info.user_sex) > -1" :type="item.user_info.user_sex_text == '男' ? 'nan':'nv' "
									 size="28" :color="item.user_info.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
								</view>
								<view class="f24r cgray ellipsis" v-if="item.user_info.city_info">{{calAddress(item.user_info.city_info)}}</view>
							</view>
							<view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnUserFollow(item.user_info)">{{ item.cross_correlation?'已关注':'关注'}}</view>
						</view>
					</uni-swipe-action-item>
				</uni-swipe-action>
			</block>
		</mescroll-uni>
	</view>
</template>

<script>
	import {
		getUserFansList,
		delFans,
		followUser,
	} from "@/api/UserServer.js"

	// 侧滑操作组件
	import uniSwipeAction from "@/components/uni-swipe-action/uni-swipe-action.vue"
	import uniSwipeActionItem from "@/components/uni-swipe-action-item/uni-swipe-action-item.vue"
	export default {
		components: {
			uniSwipeAction,
			uniSwipeActionItem
		},
		data() {
			return {
				options: [{
					text: '移除',
					style: {
						backgroundColor: '#FF6699'
					}
				}]
			}
		},
		computed: {
			// 用户
			userFansListData() {
				return this.$store.getters['user/getUserFansListData']
			}
		},
		onLoad(options) {},
		methods: {
			/// 下拉刷新的回调
			downCallback(mescroll) {
				// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
				mescroll.resetUpScroll()
			},
			/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
			upCallback(mescroll) {
				getUserFansList({
					page: mescroll.num,
					limit: mescroll.size,
				}).then(res => {
					let lists = res.data;

					if (mescroll.num == 1) {
						this.$store.commit('user/setUserFansListData', lists.data)
					} else {
						this.$store.commit('user/setUserFansListData', this.userFansListData.concat(lists.data))
					}

					mescroll.endSuccess(lists.data.length, mescroll.num < lists.count_page);
				}).catch(() => {
					mescroll.endErr();
				})
			},
			/// 地址逗号换空格
			calAddress(addr) {
				return typeof addr == 'string' ? addr.split(',').join(' ') : ''
			},
			/// 跳转用户信息页
			fnUserInfo(user_id) {
				uni.navigateTo({
					url: `/pages/user-info/user-info?user_id=${user_id}`
				})
			},
			/// 用户关注
			fnUserFollow(e) {
				let login_user = this.$store.getters['user/getLoginUserInfoData'];
				// 用户被关注
				if (e.cross_correlation) {
					uni.showModal({
						content: '确定要取消关注TA吗？',
						success: res => {
							if (res.confirm) {
                                followUser(e.user_id).then(follow => {
									uni.showToast({
										title: follow.msg,
										icon: 'none'
									});
									if (!follow.status) return
									this.userFansListData.filter(item => item.user_id == e.user_id).map(item => item.cross_correlation = false)
									// 登录用户关注数减
									if(!login_user.user_info) return;
									login_user.user_info.follows_count--
									this.$store.commit('user/setLoginUserInfoData', login_user)
								})
							}
						}
					})
					return
				} else {
					followUser(e.user_id).then(follow => {
						uni.showToast({
							title: follow.msg,
							icon: follow.status == 1 ? 'success' : 'none'
						});
						if (!follow.status) return
						this.userFansListData.filter(item => item.user_id == e.user_id).map(item => item.cross_correlation = true)
						// 登录用户关注数加
						if(!login_user.user_info) return;
						login_user.user_info.follows_count++;
						this.$store.commit('user/setLoginUserInfoData', login_user);
					})
				}
			},
			/// 滑动选择
			fnSwip(e) {
				uni.showModal({
					content: `确定要移除[${e.NickName}]吗？`,
					success: res => {
						if (res.confirm) {
							delFans(e.ID).then(delRes => {
								if (delRes.data.Data == false) return
								this.$store.commit('user/setUserFansListData', this.userFansListData.filter(item => item.ID !=
									e.ID))
								// 登录用户关注数减
								let login_user = this.$store.getters['user/getLoginUserInfoData'];
								if(!login_user.user_info) return;
								login_user.user_info.follows_count--;
								this.$store.commit('user/setLoginUserInfoData', login_user);
							})
						}
					}
				})
			}
		}
	}
</script>

<style>
	page {
		background: #FFFFFF;
	}
</style>

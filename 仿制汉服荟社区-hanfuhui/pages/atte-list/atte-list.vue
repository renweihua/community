<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni @down="downCallback" @up="upCallback">
			<block v-for="(item,index) in userAtteUserListData" :key="index">
				<view class="flex plr18r ptb18r bgwhite bbs2r">
					<user-avatar @click="fnUserInfo(item.friend_info.user_id)" :src="item.friend_info.user_avatar ? item.friend_info.user_avatar : '/static/default_avatar.png'"
					 tag="" size="md"></user-avatar>
					<view class="flexc-jsa ml18r mr28r flex-gitem w128r">
						<view>
							<text class="f28r fbold mr18r">{{item.friend_info.nick_name}}</text>
							<i-icon v-if="[0, 1].indexOf(item.friend_info.user_sex) > -1" :type="item.friend_info.user_sex_text == '男' ? 'nan':'nv' "
							 size="28" :color="item.friend_info.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
						</view>
						<view class="f24r cgray ellipsis">{{calFormatDate(item.created_time)}}</view>
						<view class="f24r cgray ellipsis">{{item.friend_info.user_introduction || '该同袍还不知道怎么描述寄己 (╯▽╰)╭'}}</view>
					</view>
					<view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnUserFollow(item)">{{ item.cross_correlation?'已关注':'关注'}}</view>
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
		getFollowsList,
		followUser
	} from "@/api/UserServer.js"

	export default {
		data() {
			return {
				id: -1,
			}
		},
		computed: {
			// 用户
			userAtteUserListData() {
				return this.$store.getters['user/getFollowsListData']
			},
		},
		onLoad(options) {
			if (options && options.id) {
				this.id = parseInt(options.id)
			}
		},
		methods: {
			/// 下拉刷新的回调
			downCallback(mescroll) {
				// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
				mescroll.resetUpScroll()
			},
			/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
			upCallback(mescroll) {
				getFollowsList({
					page: mescroll.num,
					limit: mescroll.size,
				}).then(res => {
					let lists = res.data;
					if (mescroll.num == 1) {
						this.$store.commit('user/setFollowsListData', lists.data)
					} else {
						this.$store.commit('user/setFollowsListData', this.userAtteUserListData.concat(lists.data))
					}
					mescroll.endSuccess(lists.data.length, mescroll.num < res.data.count_page);
				}).catch(() => {
					mescroll.endErr();
				})
			},
			/// 跳转用户信息页
			fnUserInfo(user_id) {
				uni.navigateTo({
					url: `/pages/user-info/user-info?user_id=${user_id}`
				})
			},
			/// 用户关注
			fnUserFollow(e) {
				// 用户被关注
				if (e.cross_correlation) {
					uni.showModal({
						content: '确定要取消关注TA吗？',
						success: res => {
							if (res.confirm) {
								followUser(e.friend_id).then(result => {
									uni.showToast({
										title: result.msg,
										icon: result.status == 1 ? 'success' : 'none'
									});
									if (!result.status) return;
									this.userAtteUserListData.filter(item => item.friend_id == e.friend_id).map(item => item.cross_correlation =
										false)
									// 登录用户关注数减
									let tempUser = this.$store.getters['user/getLoginUserInfoData']
									tempUser.user_info.follows_count--
									this.$store.commit('user/setUserInfoData', tempUser)
								})
							}
						}
					})
					return
				} else {
					followUser(e.friend_id).then(result => {
						uni.showToast({
							title: result.msg,
							icon: result.status == 1 ? 'success' : 'none'
						});
						if (!result.status) return;
						this.userAtteUserListData.filter(item => item.friend_id == e.friend_id).map(item => item.cross_correlation =
							true)
						// 登录用户关注数加
						let tempUser = this.$store.getters['user/getLoginUserInfoData']
						console.log(tempUser);
						tempUser.user_info.follows_count++;
						this.$store.commit('user/setUserInfoData', tempUser);
					})
				}
			},
			// 格式化时间
			calFormatDate(str) {
				return fnFormatDate(str)
			},
		}
	}
</script>

<style>
	page {
		background: #FFFFFF;
	}
</style>

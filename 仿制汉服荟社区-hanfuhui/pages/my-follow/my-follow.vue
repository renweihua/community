<template>
	<view>
		<!-- 顶部导航栏 -->
		<view class="posif posi-tlr0 z500 bgf8">
			<!-- 导航条 -->
			<scroll-view class="scroll-bar" :scroll-x="true" :show-scrollbar="false" :scroll-into-view="scrollInto" :scroll-with-animation="true">
				<view
					v-for="tab in tabBars"
					:key="tab.id"
					class="scroll-bar-item33v w50v"
					:class="{ 'scroll-bar-itemsh': current == tab.current }"
					:id="tab.id"
					:data-current="tab.current"
					@tap="fnBarClick(tab)"
				>
					{{ tab.name }}
				</view>
			</scroll-view>
		</view>
		<!-- 滑动切换视图 -->
		<swiper class="posia posi-all0" :current="current" @change="fnBarClick">
			<!-- 关注用户 -->
			<swiper-item>
				<mescroll-uni v-if="status.user" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(item, index) in userAtteUserListData" :key="index">
						<view class="flex plr18r ptb18r bgwhite bbs2r">
							<user-avatar
								@click="fnUserInfo(item.friend_info.user_id)"
								:src="item.friend_info.user_avatar ? item.friend_info.user_avatar : '/static/default_avatar.png'"
								tag=""
								size="md"
							></user-avatar>
							<view class="flexc-jsa ml18r mr28r flex-gitem w128r">
								<view>
									<text class="f28r fbold mr18r">{{ item.friend_info.nick_name }}</text>
									<i-icon
										v-if="[0, 1].indexOf(item.friend_info.user_sex) > -1"
										:type="item.friend_info.user_sex_text == '男' ? 'nan' : 'nv'"
										size="28"
										:color="item.friend_info.user_sex_text == '男' ? '#479bd4' : '#FF6699'"
									></i-icon>
								</view>
								<view class="f24r cgray ellipsis">{{ calFormatDate(item.created_time) }}</view>

								<view class="f24r cgray ellipsis">{{ item.friend_info.basic_extends.user_introduction || '该同袍还不知道怎么描述寄己 (╯▽╰)╭' }}</view>
							</view>
							<view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnUserFollow(item)">
								{{ item.cross_correlation ? '已关注' : '关注' }}
							</view>
						</view>
					</block>
				</mescroll-uni>
			</swiper-item>

			<!-- 关注荟吧 -->
			<swiper-item>
				<mescroll-uni v-if="status.huiba" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(item, index) in huibaUserFollowData" :key="index">
						<view class="flexr-jsb flex-aic plr18r ptb18r bgwhite bbs2r" @tap="fnHuibaInfo(item.topic.topic_id)">
							<view class="flex">
								<user-avatar :src="item.topic.topic_cover ? item.topic.topic_cover : '/static/default_avatar.png'" size="md" :square="true"></user-avatar>
								<view class="flexc-jsa ml28r">
									<view class="f28r fbold mr18r c111">{{ item.topic.topic_name }}</view>
									<view class="f24r cgray">
										<text class="mr8r">关注</text>
										{{ item.topic.follow_count || 0 }}
										<text class="ml28r mr8r">动态</text>
										{{ item.topic.dynamic_count || 0 }}
									</view>
								</view>
							</view>
							<i-icon type="you" size="36" color="#8F8F94"></i-icon>
						</view>
					</block>
				</mescroll-uni>
			</swiper-item>
		
			<!-- 关注话题 -->
			<swiper-item>
				<mescroll-uni v-if="status.topic" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(topic, index) in topicUserFollowData" :key="index">
						<view class="plr18r ptb18r bgwhite bbs2r h112r" @tap="fnTopicInfo(topic.Topic.ID)">
							<view class="f32r fbold ellipsis hl80r">{{ topic.Topic.Name }}</view>
							<view class="flexr-jsb flex-aic">
								<view class="f24r cgray flex-fitem">
									<text class="mr8r">关注</text>
									{{ topic.Topic.UserCount || 0 }}
									<text class="ml28r mr8r">动态</text>
									{{ topic.Topic.TrendCount || 0 }}
								</view>
								<view class="bgtheme cwhite br18r f24r plr18r" v-if="topic.NoReadCount">{{ topic.NoReadCount || 0 }}</view>
							</view>
						</view>
					</block>
				</mescroll-uni>
			</swiper-item>
		</swiper>
	</view>
</template>

<script>
import { fnFormatDate } from '@/utils/CommonUtil.js';
import { getFollowsList, followUser } from '@/api/UserServer.js';
import { getTopicUserFollow } from '@/api/TopicServer.js';
import { getHuibaUserFollow } from '@/api/HuibaServer.js';

let dataList = [getFollowsList, getHuibaUserFollow, getTopicUserFollow];

export default {
	data() {
		return {
			// 导航项滑动初始id
			scrollInto: 'user',
			// 顶部导航滑动页选中
			current: 0,
			// 导航项列表
			tabBars: [
				{
					id: 'user',
					name: '用户',
					current: 0
				},
				{
					id: 'huiba',
					name: '荟吧',
					current: 1
				},
				// {
				// 	id: 'topic',
				// 	name: '话题',
				// 	current: 2
				// }
			],
			// 激活顶部导航关联页状态
			status: {
				user: true,
				topic: false,
				huiba: false
			},
			// 双击刷新
			clickRefresh: false,
			// 刷新间隔
			timeOutFollow: 0,
			// 刷新组件实例
			mescroll: {
				user: null,
				topic: null,
				huiba: null
			},
		};
	},
	computed: {
		// 用户
		userAtteUserListData() {
			return this.$store.getters['user/getFollowsListData'];
		},
		// 话题
		topicUserFollowData() {
			return this.$store.getters['topic/getTopicUserFollowData'];
		},
		// 荟吧
		huibaUserFollowData() {
			return this.$store.getters['huiba/getHuibaUserFollowData'];
		}
		//
	},
	onLoad(options) {
		
	},
	methods: {
		/// mescroll组件初始化的回调,可获取到mescroll对象
		mescrollInit(mescroll) {
			this.mescroll[this.scrollInto] = mescroll;
		},
		/// 下拉刷新的回调
		downCallback(mescroll) {
			// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
			this.mescroll[this.scrollInto].resetUpScroll();
		},
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			dataList[this.current]({
				page: mescroll.num,
				limit: mescroll.size
			})
				.then(res => {
					console.log('---res---');
					console.log(res);
					let lists = res.data;

					// 用户
					if (this.scrollInto == 'user') {
						if (mescroll.num == 1) {
							this.$store.commit('user/setFollowsListData', lists.data);
						} else {
							this.$store.commit('user/setFollowsListData', this.userAtteUserListData.concat(lists.data));
						}
					}
					// 话题
					if (this.scrollInto == 'topic') {
						if (mescroll.num == 1) {
							this.$store.commit('topic/setTopicUserFollowData', lists.data);
						} else {
							this.$store.commit('topic/setTopicUserFollowData', this.topicUserFollowData.concat(lists.data));
						}
					}
					// 荟吧
					if (this.scrollInto == 'huiba') {
						if (mescroll.num == 1) {
							this.$store.commit('huiba/setHuibaUserFollowData', lists.data);
						} else {
							this.$store.commit('huiba/setHuibaUserFollowData', this.huibaUserFollowData.concat(lists.data));
						}
					}
					mescroll.endSuccess(lists.data.length, mescroll.num < lists.count_page);
				})
				.catch(() => {
					mescroll.endErr();
				});
		},
		/// 导航选项双击刷新获取新数据
		fnRefreshData() {
			this.mescroll[this.scrollInto].scrollTo(0, 300);
			setTimeout(() => {
				this.mescroll[this.scrollInto].resetUpScroll(true);
			}, 1000);
		},
		/// 顶部导航选项点击
		fnBarClick(e) {
			let current = e.hasOwnProperty('detail') ? e.detail.current : e.current;
			this.scrollInto = this.tabBars[current].id;
			// 是否当前项点击
			if (e.hasOwnProperty('id') && this.current == current) {
				this.timeOutFollow += 1;
				// 是否为刷新值和连续触发
				if (!this.clickRefresh && this.timeOutFollow >= 2) {
					// 刷新值开
					this.clickRefresh = true;
					// 获取新数据
					this.fnRefreshData();
					// 定时器重置
					this.timeOutFollow = setTimeout(() => {
						// 清除定时器
						clearTimeout(this.timeOutFollow);
						// 连续触发记录重置
						this.timeOutFollow = 0;
						// 刷新值关
						this.clickRefresh = false;
					}, 5000);
				}
				return;
			} else {
				// 改变顶部导航选中
				this.current = current;
				// 首次选中激活顶部导航关联页状态
				if (!this.status.huiba && current == 1) this.status.huiba = true;
				if (!this.status.topic && current == 2) this.status.topic = true;
				// 清除定时器
				clearTimeout(this.timeOutFollow);
				// 连续触发记录重置
				this.timeOutFollow = 0;
				// 刷新值关
				this.clickRefresh = false;
			}
		},
		/// 跳转用户信息页
		fnUserInfo(id) {
			uni.navigateTo({
				url: `/pages/user-info/user-info?id=${id}`
			});
		},
		/// 用户关注
		fnUserFollow(e) {
			console.log(e);
			let login_user = this.$store.getters['user/getLoginUserInfoData'];
			// 用户被关注
			if (e.cross_correlation) {
				uni.showModal({
					content: '确定要取消关注TA吗？',
					success: res => {
						if (res.confirm) {
							followUser(e.friend_id).then(result => {
								uni.showToast({
									title: result.msg,
									icon: 'none'
								});
								if (!result.status) return;
								this.userAtteUserListData.filter(item => item.friend_id == e.friend_id).map(item => (item.cross_correlation = false));
								// 登录用户关注数减
								if(!login_user.user_info) return;
								login_user.user_info.follows_count--;
								this.$store.commit('user/setLoginUserInfoData', login_user);
							});
						}
					}
				});
				return;
			} else {
				followUser(e.friend_id).then(result => {
					uni.showToast({
						title: result.msg,
						icon: result.status == 1 ? 'success' : 'none'
					});
					if (!result.status) return;
					this.userAtteUserListData.filter(item => item.friend_id == e.friend_id).map(item => (item.cross_correlation = true));
					// 登录用户关注数加
					if(!login_user.user_info) return;
					login_user.user_info.follows_count++;
					this.$store.commit('user/setLoginUserInfoData', login_user);
				});
			}
		},
		/// 跳转荟吧详情页
		fnHuibaInfo(topic_id) {
			uni.navigateTo({
				url: `/pages/huiba-details/huiba-details?topic_id=${topic_id}`
			});
		},
		/// 跳转话题详情页
		fnTopicInfo(id) {
			uni.navigateTo({
				url: `/pages/topic-details/topic-details?id=${id}`
			});
		},
		// 格式化时间
		calFormatDate(str) {
			return fnFormatDate(str);
		}
	}
};
</script>

<style></style>

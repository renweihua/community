<template>
	<view>
		<!-- 顶部导航栏 -->
		<view class="posif posi-tlr0 bgf8 z500">
			<video class="video-player" :src="videoInfoData.video_path" :poster="calVideoCover" :controls="true" :page-gesture="true"></video>
		</view>

		<!-- 滚动内容区 -->
		<mescroll-uni :top="400" :bottom="90" :down="{ use: false }" @up="upCallback" @init="mescrollInit">
			<!-- 基本 -->
			<view class="mb18r bgwhite">
				<!-- 中心内容 -->
				<view class="plr18r">
					<!-- 标题 -->
					<view class="f36r c111 fbold ptb18r">{{ videoInfoData.dynamic_title }}</view>
					<!-- 时间 -->
					<view class="f24r cgray mb18r">{{ calDatetime }}</view>
					<!-- 内容 -->
					<view class="f28r c555 fword">{{ videoInfoData.dynamic_content }}</view>
				</view>
				<!-- 用户信息 -->
				<view class="flex plr18r ptb18r">
					<user-avatar @click="fnUserInfo(calUser)" :src="calUserAvater" tag="" size="md"></user-avatar>
					<view class="flexc-jsa ml18r mr28r flex-gitem w128r">
						<view>
							<text class="f28r fbold mr18r">{{ calUser.nick_name || '#' }}</text>
							<i-icon
								v-if="[0, 1].indexOf(calUser.user_sex) > -1"
								:type="calUser.user_sex_text == '男' ? 'nan' : 'nv'"
								size="28"
								:color="calUser.user_sex_text == '男' ? '#479bd4' : '#FF6699'"
							></i-icon>
						</view>
						<view class="f24r cgray ellipsis">{{ calUser.user_introduction || '该同袍还不知道怎么描述寄己 (╯▽╰)╭' }}</view>
					</view>
					<!-- 如果登录会员就是发布者，那么不展示 -->
					<view v-if="!calUser.is_self" class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r" @tap="fnAtte(calUser)">
						{{ calUser.is_follow ? '已关注' : '关注' }}
					</view>
				</view>
				<!-- 点赞列表 -->
				<view class="flexr-jfe flex-aic ptb18r plr18r bts2r br8r" v-if="topListData">
					<block v-for="index in 8" :key="index">
						<view class="mr8r">
							<user-avatar
								v-if="topListData[index - 1]"
								:src="topListData[index - 1].user_info.user_avatar"
								tag=""
								size="sm"
								@click="fnUserInfo(topListData[index - 1].user_info)"
							></user-avatar>
						</view>
					</block>
					<view class="f24r c111 fcenter bgf8 w128r ptb18r" @tap="fnTopList">
						{{ videoInfoData.is_praise ? '已赞' : '赞' }}
						<text class="f24r cbrown ml18r">{{ videoInfoData.praise_count }}</text>
					</view>
				</view>
			</view>
			<!-- 评论区 -->
			<view class="plr18r ptb28r f32r fbold c111 bbs2r bgwhite">评论（{{ videoInfoData.comment_count || 0 }}）</view>
			<block v-for="(commData, index) in commentListData" :key="index">
				<comm-cell :info-data="commData" @user="fnUserInfo" @top="fnTopComm" @comm="fnComm" @more="fnMoreComm"></comm-cell>
			</block>
		</mescroll-uni>

		<!-- 固定底部评论点赞收藏分享 -->
		<view class="posif posi-blr0 flexr-jsa plr18r ptb18r bts2r z5 bgwhite">
			<view class="br8r bgf8 plr18r mr8r flex-fitem" @tap="fnCommOpen">
				<i-icon type="bianji" size="36" color="#8F8F94"></i-icon>
				<text class="f28r cgray ml8r">想说点什么？</text>
			</view>
			<view class="plr28r bls2r brs2r" @tap="fnTop">
				<i-icon type="dianzan" size="48" :color="videoInfoData.is_praise ? '#FF6699' : '#8F8F94'"></i-icon>
				<text class="f28r cgray ml8r">{{ videoInfoData.praise_count || 0 }}</text>
			</view>
			<view class="plr28r" @tap="fnSave">
				<i-icon type="shoucang" size="48" :color="videoInfoData.is_collection ? '#FF6699' : '#8F8F94'"></i-icon>
				<text class="f28r cgray ml8r">{{ videoInfoData.collection_count || 0 }}</text>
			</view>
			<view class="pl28r pr8r bls2r" @tap="fnShare"><i-icon type="fenxiang" size="48" color="#8F8F94"></i-icon></view>
		</view>
		<!-- 分享弹出层 -->
		<share-popup ref="share"></share-popup>
		<!-- 评论输入弹出层 -->
		<comm-input ref="comm" @send="fnCommSend"></comm-input>
	</view>
</template>

<script>
import { fnFormatDate } from '@/utils/CommonUtil.js';
import { getRsaText } from '@/api/CommonServer.js';
import { getDynamicInfo } from '@/api/TrendServer.js';
import { getVideoInfo, getVideoUrl } from '@/api/VideoServer.js';
import { getDynamicPraises, dynamicPraise, getCommentList, addComment, delComment, addCommentTop, delCommentTop, dynamicCollection } from '@/api/InteractServer.js';
import { followUser } from '@/api/UserServer.js';

// 分享弹出层组件
import SharePopup from '@/components/share-popup/share-popup';
// 评论列表单元组件
import CommCell from '@/components/comm-cell/comm-cell';
// 评论输入弹出层组件
import CommInput from '@/components/comm-input/comm-input';

export default {
	components: {
		SharePopup,
		CommCell,
		CommInput
	},

	data() {
		return {
			// 视频项ID
			dynamic_id: -1,
			// 跳转来源页
			fromPage: '',
			// 来源页标签数据下标
			current: -1,
			// 回复添加父ID
			reply_id: -1,
			// 回复的主评论Id
			top_level: 0,
			// mescroll组件实例
			mescroll: null
		};
	},
	onLoad(options) {
		if (options && options.dynamic_id) {
			uni.showLoading({
				title: '加载中',
				mask: true
			});
			this.dynamic_id = parseInt(options.dynamic_id);
			if (typeof options.fromPage == 'string') this.fromPage = options.fromPage;
			if (typeof options.current == 'string') this.current = parseInt(options.current);
			if (typeof options.comm == 'string') {
				setTimeout(() => {
					this.fnCommOpen();
				}, 1000);
			}
			uni.hideLoading();
		}
	},
	computed: {
		// 视频信息
		videoInfoData() {
			return this.$store.getters['video/getVideoInfoData'];
		},
		/// 计算显示视频封面
		calVideoCover() {
			if (this.videoInfoData.dynamic_type == 2) {
				let cover = '/static/default_image.png';
				if (this.videoInfoData.dynamic_images) cover = this.videoInfoData.dynamic_images[0];
				return cover;
			} else {
				return '';
			}
		},
		// 动态点赞列表数据
		topListData() {
			return this.$store.getters['interact/getDynamicPraisesData'];
		},
		// 动态评论列表数据
		commentListData() {
			return this.$store.getters['interact/getCommentListData'];
		},
		// 计算是否得到用户信息
		calUser() {
			return this.videoInfoData.user_info || false;
		},
		/// 计算显示用户头像
		calUserAvater() {
			return !!this.calUser ? this.calUser.user_avatar : '/static/default_avatar.png';
		},
		/// 计算格式友好时间 几天前
		calDatetime() {
			return fnFormatDate(this.videoInfoData.created_time);
		}
	},
	methods: {
		/// mescroll组件初始化完成的回调
		mescrollInit(mescroll) {
			this.mescroll = mescroll;
		},
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			let params = {
				dynamic_id: this.dynamic_id,
				page: mescroll.num,
				limit: mescroll.size
			};
			if (mescroll.num == 1) {
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				console.log(this.dynamic_id);
				// 获取详情信息
				getDynamicInfo(this.dynamic_id)
					.then(dynamic => {
						console.log('---dynamic---');
						console.log(dynamic);
						this.$store.commit('video/setVideoInfoData', dynamic.data);
						// 导航标题
						uni.setNavigationBarTitle({
							title: dynamic.data.dynamic_title
						});
						params.count = 8;
						// 获取点赞列表8项
						return getDynamicPraises(params);
					})
					.then(topRes => {
						this.$store.commit('interact/setTopListData', topRes.data.data);
						params.count = mescroll.size;
						// 获取评论列表
						return getCommentList(params);
					})
					.then(commRes => {
						this.$store.commit('interact/setCommentListData', commRes.data.data);
						mescroll.endSuccess(commRes.data.data.length, mescroll.num < commRes.data.count_page);
					})
					.catch(e => {
						mescroll.endSuccess(0, false);
					});
				setTimeout(() => {
					uni.hideLoading();
				}, 2000);
				return;
			} else {
				// 继续上拉获取评论
				getCommentList(params)
					.then(commRes => {
						this.$store.commit('interact/setCommentListData', this.commentListData.concat(commRes.data.data));
						mescroll.endSuccess(commRes.data.data.length, mescroll.num < commRes.data.count_page);
					})
					.catch(() => {
						mescroll.endErr();
					});
			}
		},
		/// 跳转用户信息页
		fnUserInfo(e) {
			uni.navigateTo({
				url: `/pages/user-info/user-info?user_id=${e.user_id}`
			});
		},
		/// 跳转点赞列表
		fnTopList() {
			uni.navigateTo({
				url: `/pages/top-list/top-list?dynamic_id=${this.dynamic_id}&type=video`
			});
		},
		/// 跳转评论列表
		fnMoreComm(id) {
			uni.navigateTo({
				url: `/pages/comm-list/comm-list?id=${id}`
			});
		},
		/// 分享图标
		fnShare() {
			this.videoInfoData.dynamic_id = this.dynamic_id;
			this.$refs.share.open(this.videoInfoData);
		},
		/// 详情点赞
		fnTop() {
			let filItem = {};
			// 来自主要跳转
			if (this.fromPage == 'home') {
				// 推荐
				if (this.current == 0) filItem = this.$store.getters['trend/getMainData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				// 关注
				if (this.current == 1) filItem = this.$store.getters['trend/getAtteData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				// 广场
				if (this.current == 2) filItem = this.$store.getters['trend/getSquareData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
			}
			// 来自用户详情
			if (this.fromPage == 'userinfo') {
				// 发布
				if (this.current == 0) filItem = this.$store.getters['user/getUserPublishListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				// 赞过
				if (this.current == 1) filItem = this.$store.getters['user/getUserTopListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
			}
			// 来自发现-视频跳转
			if (this.fromPage == 'find') {
				filItem = this.$store.getters['video/getVideoListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
			}
			// 点赞动态
			dynamicPraise(filItem.dynamic_id).then(res => {
				uni.showToast({
					title: res.msg,
					icon: res.status == 1 ? 'success' : 'none'
				});
				if (!res.status) return;
				let login_user = this.$store.getters['user/getLoginUserInfoData'];
				console.log('---login_user---');
				console.log(login_user);
				// 用户是否点过赞
				if (filItem.is_praise) {
					filItem.praise_count--;
					this.videoInfoData.is_praise = filItem.is_praise = false;
					this.videoInfoData.praise_count--;
					// 点赞列表减头像
					let filTopList = this.topListData.filter(item => item.user_id != login_user.user_id);
					this.$store.commit('interact/setTopListData', filTopList);
				} else {
					filItem.praise_count++;
					this.videoInfoData.is_praise = filItem.is_praise = true;
					this.videoInfoData.praise_count++;
					if (login_user) {
						// 点赞列表加会员信息
						this.topListData.unshift({
							user_id: login_user.user_id,
							user_info: login_user.user_info
						});
					}
				}
			});
		},
		// 评论点赞
		fnTopComm(e) {
			let filItem = this.commentListData.filter(item => item.comment_id == e.comment_id)[0];
			if (filItem.is_praise) {
				delCommentTop(filItem.comment_id).then(res => {
					if (!res.status) return;
					filItem.praise_count--;
					filItem.is_praise = false;
				});
			} else {
				addCommentTop(filItem.comment_id).then(res => {
					if (!res.status) return;
					filItem.praise_count++;
					filItem.is_praise = true;
				});
			}
		},
		/// 关注详情发布用户
		fnAtte(e) {
			let login_user = this.$store.getters['user/getLoginUserInfoData'];
			// 用户是否已经关注
			if (e.is_follow) {
				followUser(e.user_id).then(res => {
					uni.showToast({
						title: res.msg,
						icon: 'none'
					});
					if (!res.status) return;
					this.videoInfoData.user_info.user_id = false;
					// 来自主要跳转
					if (this.fromPage == 'home') {
						this.$store.getters['trend/getMainData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.user_id = false));
						this.$store.getters['trend/getAtteData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.user_id = false));
						this.$store.getters['trend/getSquareData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.user_id = false));
					}
					// 来自用户详情
					if (this.fromPage == 'userinfo') {
						this.$store.getters['user/getUserPublishListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.user_id = false));
						this.$store.getters['user/getUserTopListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.user_id = false));
					}
					// 来自发现-视频跳转
					if (this.fromPage == 'find') {
						this.$store.getters['video/getVideoListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.user_id = false));
					}
					// 登录用户关注数减
					if (!login_user.user_info) return;
					login_user.user_info.follows_count--;
					this.$store.commit('user/setLoginUserInfoData', login_user);
				});
			} else {
				followUser(e.user_id).then(res => {
					uni.showToast({
						title: res.msg,
						icon: res.status == 1 ? 'success' : 'none'
					});
					if (!res.status) return;
					this.videoInfoData.user_info.is_follow = true;
					// 来自主要跳转
					if (this.fromPage == 'home') {
						this.$store.getters['trend/getMainData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
						this.$store.getters['trend/getAtteData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
						this.$store.getters['trend/getSquareData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
					}
					// 来自用户详情
					if (this.fromPage == 'userinfo') {
						this.$store.getters['user/getUserPublishListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
						this.$store.getters['user/getUserTopListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
					}
					// 来自发现-视频跳转
					if (this.fromPage == 'find') {
						this.$store.getters['video/getVideoListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
					}
					// 登录用户关注数加
					if (!login_user.user_info) return;
					login_user.user_info.follows_count++;
					this.$store.commit('user/setLoginUserInfoData', login_user);
				});
			}
		},
		/// 详情收藏
		fnSave() {
			let filItem = {};
			// 来自主要跳转
			if (this.fromPage == 'home') {
				// 推荐
				if (this.current == 0) filItem = this.$store.getters['trend/getMainData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				// 关注
				if (this.current == 1) filItem = this.$store.getters['trend/getAtteData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				// 广场
				if (this.current == 2) filItem = this.$store.getters['trend/getSquareData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
			}
			// 来自用户详情
			if (this.fromPage == 'userinfo') {
				// 发布
				if (this.current == 0) filItem = this.$store.getters['user/getUserPublishListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				// 赞过
				if (this.current == 1) filItem = this.$store.getters['user/getUserTopListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
			}
			// 来自发现-视频跳转
			if (this.fromPage == 'find') {
				filItem = this.$store.getters['video/getVideoListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
			}

			dynamicCollection(filItem.dynamic_id).then(res => {
				uni.showToast({
					title: res.msg,
					icon: res.status == 1 ? 'success' : 'none'
				});
				if (!res.status) return;

				// 用户是否已收藏
				if (filItem.is_collection) {
					filItem.collection_count--;
					this.videoInfoData.is_collection = filItem.is_collection = false;
					this.videoInfoData.collection_count--;
				} else {
					filItem.collection_count++;
					this.videoInfoData.is_collection = filItem.is_collection = true;
					this.videoInfoData.collection_count++;
				}
			});
		},
		/// 显示评论输入框
		fnCommOpen() {
			this.$refs.comm.open({
				dynamic_id: this.dynamic_id,
				type: 'comment',
				content: this.$store.getters['getCommContentData']
			});
		},
		/// 评论发送
		fnCommSend(e) {
			// 不为发送时保存输入值
			if (e.type == 'comment') this.$store.commit('setCommContentData', e.content);
			// 无内容信息反馈
			if (e.content == '') {
				uni.showToast({
					title: '评论内容不能为空',
					icon: 'none'
				});
				return;
			}
			// 提交评论
			uni.showLoading({
				title: '提交中'
			});
			delete e.type;
			e.fromclient = 'android';
			addComment(e).then(res => {
				uni.showToast({
					title: res.msg,
					icon: !res.status ? 'none' : 'success'
				});
				if (!res.status) {
					return;
				}
				if (this.reply_id == 0) {
					// 直接评论
					this.$store.commit('interact/setCommentListData', this.commentListData.concat([res.data]));
				} else if (this.reply_id > 0) {
					// 有回复项追加
					let filCommentList = this.commentListData.filter(item => item.comment_id == this.top_level)[0];
					filCommentList.replies_count++;
					filCommentList.replies = filCommentList.replies.concat([res.data]);
				}
				this.$store.commit('setCommContentData', '');
				// 评论数量添加
				if (this.videoInfoData.comment_count == 0) this.mescroll.removeEmpty();
				this.videoInfoData.comment_count++;
				this.$refs.comm.visible = false;
				this.top_level = this.reply_id = 0;
				uni.hideLoading();
				// 改变上一窗口的数据
				let filItem = {};
				// 来自主要跳转
				if (this.fromPage == 'home') {
					// 推荐
					if (this.current == 0) filItem = this.$store.getters['trend/getMainData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
					// 关注
					if (this.current == 1) filItem = this.$store.getters['trend/getAtteData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
					// 广场
					if (this.current == 2) filItem = this.$store.getters['trend/getSquareData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				}
				// 来自用户详情
				if (this.fromPage == 'userinfo') {
					// 发布
					if (this.current == 0) filItem = this.$store.getters['user/getUserPublishListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
					// 赞过
					if (this.current == 1) filItem = this.$store.getters['user/getUserTopListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				}
				// 来自发现-视频跳转
				if (this.fromPage == 'find') {
					filItem = this.$store.getters['video/getVideoListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				}
				filItem.comment_count++;
			});
		},
		/// 评论项操作
		fnComm(e) {
			let itemList = ['回复', '复制', '举报'];
			if (e.user_info.user_id == this.$store.getters['user/getLoginUserInfoData'].user_id) itemList.push('删除');
			uni.showActionSheet({
				itemList,
				success: res => {
					switch (res.tapIndex) {
						case 0:
							this.$refs.comm.open({
								type: 'reply',
								user: e.user_info.nick_name,
								dynamic_id: e.dynamic_id,
								reply_id: e.comment_id,
								top_level: e.top_level
							});
							this.top_level = e.top_level;
							this.reply_id = e.comment_id;
							break;
						case 1:
							uni.setClipboardData({
								data: e.Content
							});
							break;
						case 2:
							uni.navigateTo({
								url: `/pages/report/report?dynamic_id=${this.dynamic_id}`
							});
							break;
						case 3:
							delComment(e.comment_id).then(res => {
								uni.showToast({
									title: res.msg,
									icon: !res.status ? 'none' : 'success'
								});
								if (!res.status) {
									return;
								}
								if (e.reply_id > 0) {
									// 有回复项删减
									let filCommentList = this.commentListData.filter(item => item.comment_id == e.top_level)[0];
									let filreplies = filCommentList.replies;
									filreplies = filreplies.filter(item => res.data.indexOf(item.comment_id, res.data) == -1);
									filCommentList.comment_count = filCommentList.comment_count - res.data.length;
									filCommentList.replies = filreplies;
									// 评论数量减少
									this.dynamic.comment_count = this.dynamic.comment_count - res.data.length;
								} else {
									// 评论发布项删除
									let filCommentList = this.commentListData.filter(item => item.comment_id != e.comment_id);
									this.$store.commit('interact/setCommentListData', filCommentList);
									// 评论数量减少
									this.dynamic.comment_count--;
								}
								if (this.videoInfoData.comment_count == 0) this.mescroll.showEmpty();
								// 改变上一窗口的数据
								let filItem = {};
								// 来自主要跳转
								if (this.fromPage == 'home') {
									// 推荐
									if (this.current == 0) filItem = this.$store.getters['trend/getMainData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
									// 关注
									if (this.current == 1) filItem = this.$store.getters['trend/getAtteData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
									// 广场
									if (this.current == 2) filItem = this.$store.getters['trend/getSquareData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
								}
								// 来自用户详情
								if (this.fromPage == 'userinfo') {
									// 发布
									if (this.current == 0) filItem = this.$store.getters['user/getUserPublishListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
									// 赞过
									if (this.current == 1) filItem = this.$store.getters['user/getUserTopListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
								}
								// 来自发现-视频跳转
								if (this.fromPage == 'find') {
									filItem = this.$store.getters['video/getVideoListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
								}
								filItem.comment_count = filItem.comment_count - res.data.length;
							});
							break;
						default:
							break;
					}
				}
			});
		}
		//
	},

	beforeDestroy() {
		// 清空预评论内容
		this.$store.commit('setCommContentData', '');
	}
};
</script>

<style>
/* 视频控件 */
.video-player {
	display: block;
	width: 100%;
	height: 400rpx;
}
</style>

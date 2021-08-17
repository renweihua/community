<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni :bottom="90" :down="{ use: false }" @up="upCallback" @init="mescrollInit">
			<!-- 基本 -->
			<view class="mb18r bgwhite">
				<!-- 标题 -->
				<view class="f36r c111 fbold plr18r ptb18r">{{ wordInfoData.dynamic_title }}</view>
				<!-- 时间和发布者 -->
				<view class="plr18r">
					<text class="f24r cgray">{{ calDatetime }}</text>
					<text class="f28r ctheme-aux ml18r">{{ !!calUser ? calUser.nick_name : '#' }}</text>
				</view>
				<!-- 内容 -->
				<view class="f28r c555 fword plr18r ptb18r"><u-parse :content="wordInfoData.dynamic_content" :loading="!wordInfoData.dynamic_content"></u-parse></view>
				<!-- 用户信息 -->
				<view class="flexr-jsb flex-aic plr18r ptb18r br8r bgf8 mtb18r mlr18r">
					<view class="flex" @tap="fnUserInfo(calUser)">
						<user-avatar :src="calUserAvater" tag="" size="md"></user-avatar>
						<view class="flexc-jsa ml28r">
							<view>
								<text class="f28r fbold mr18r">{{ !!calUser ? calUser.nick_name : '#' }}</text>
								<i-icon
									v-if="[0, 1].indexOf(calUser.user_sex) > -1"
									:type="calUser.user_sex_text == '男' ? 'nan' : 'nv'"
									size="28"
									:color="calUser.user_sex_text == '男' ? '#479bd4' : '#FF6699'"
								></i-icon>
							</view>
							<view class="f24r cgray" v-if="calUser.city_info">{{ calAddress }}</view>
						</view>
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
						{{ wordInfoData.is_praise ? '已赞' : '赞' }}
						<text class="f24r cbrown ml18r">{{ wordInfoData.cache_extends ? wordInfoData.cache_extends.praises_count : 0}}</text>
					</view>
				</view>
			</view>
			<!-- 评论区 -->
			<view class="plr18r ptb28r f32r fbold c111 bbs2r bgwhite">评论（{{ wordInfoData.cache_extends ? wordInfoData.cache_extends.comments_count : 0 }}）</view>
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
				<i-icon type="dianzan" size="48" :color="wordInfoData.is_praise ? '#FF6699' : '#8F8F94'"></i-icon>
				<text class="f28r cgray ml8r">{{ wordInfoData.cache_extends ? wordInfoData.cache_extends.praises_count : 0 }}</text>
			</view>
			<view class="plr28r" @tap="fnSave">
				<i-icon type="shoucang" size="48" :color="wordInfoData.is_collection ? '#FF6699' : '#8F8F94'"></i-icon>
				<text class="f28r cgray ml8r">{{ wordInfoData.cache_extends ? wordInfoData.cache_extends.collection_count : 0 }}</text>
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
import { getDynamicInfo } from '@/api/TrendServer.js';
import { getDynamicPraises, dynamicPraise, getCommentList, addComment, delComment, addCommentTop, delCommentTop, dynamicCollection } from '@/api/InteractServer.js';
import { followUser } from '@/api/UserServer.js';

// 分享弹出层组件
import SharePopup from '@/components/share-popup/share-popup';
// 评论列表单元组件
import CommCell from '@/components/comm-cell/comm-cell';
// 评论输入弹出层组件
import CommInput from '@/components/comm-input/comm-input';
// HTML富文本解析器
import uParse from '@/components/gaoyia-parse/parse.vue';

export default {
	components: {
		SharePopup,
		CommCell,
		CommInput,
		uParse
	},
	data() {
		return {
			// 文章项ID
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
			console.log(options);
			uni.showLoading({
				title: '加载中',
				mask: true
			});
			this.dynamic_id = parseInt(options.dynamic_id);
			setTimeout(() => {
				uni.hideLoading();
				if (typeof options.fromPage == 'string') this.fromPage = options.fromPage;
				if (typeof options.current == 'string') this.current = parseInt(options.current);
				if (typeof options.comm == 'string') {
					setTimeout(() => {
						this.fnCommOpen();
					}, 1000);
				}
			}, 2000);
		}
	},
	computed: {
		// 文章基本信息
		wordInfoData() {
			return this.$store.getters['word/getWordInfoData'];
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
			return this.wordInfoData.user_info || false;
		},
		/// 计算显示用户头像
		calUserAvater() {
			return !!this.calUser ? this.calUser.user_avatar : '/static/default_avatar.png';
		},
		/// 时间格式
		calDatetime() {
			return fnFormatDate(this.wordInfoData.created_time);
		},
		/// 地址逗号换空格
		calAddress() {
			if (!!this.calUser) {
				return this.calUser.city_info ? this.calUser.city_info.split(',').join(' ') : '';
			} else {
				return '';
			}
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
			console.log(mescroll);
			if (mescroll.num == 1) {
				// 获取详情信息
				getDynamicInfo(this.dynamic_id)
					.then(res => {
						if (!res.status) {
							uni.showToast({
								title: res.msg,
								icon: 'none'
							});
							return;
						}
						this.$store.commit('word/setWordInfoData', res.data);
						// 导航标题
						uni.setNavigationBarTitle({
							title: res.data.dynamic_title
						});

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
					.catch(() => {
						mescroll.endSuccess(0, false);
					});
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
				url: `/pages/top-list/top-list?dynamic_id=${this.dynamic_id}`
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
			this.wordInfoData.dynamic_id = this.dynamic_id;
			this.$refs.share.open(this.wordInfoData);
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
			// 来自发现-文章跳转
			if (this.fromPage == 'find') {
				filItem = this.$store.getters['word/getWordListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
			}
			// 点赞动态
			dynamicPraise(filItem.dynamic_id).then(res => {
				uni.showToast({
					title: res.msg,
					icon: res.status == 1 ? 'success' : 'none'
				});
				if (!res.status) return;
				let login_user = this.$store.getters['user/getLoginUserInfoData'];
				// 用户是否点过赞
				if (filItem.is_praise) {
					filItem.cache_extends.praises_count--;
					this.wordInfoData.is_praise = filItem.is_praise = false;
					this.wordInfoData.cache_extends.praises_count--;
					// 点赞列表减头像
					let filTopList = this.topListData.filter(item => item.user_id != login_user.user_id);
					this.$store.commit('interact/setTopListData', filTopList);
				} else {
					filItem.cache_extends.praises_count++;
					this.wordInfoData.is_praise = filItem.is_praise = true;
					this.wordInfoData.cache_extends.praises_count++;
					if (!login_user.user_id) {
						// 点赞列表加会员信息
						this.topListData.unshift({
							user_id: login_user.user_id,
							user_info: login_user.user_info
						});
					}
				}
			});
		},
		/// 评论点赞
		fnTopComm(e) {
			let filItem = this.commentListData.filter(item => item.comment_id == e.comment_id)[0];
			if (filItem.is_praise) {
				delCommentTop(filItem.comment_id).then(res => {
					if (!res.status) return;
					filItem.cache_extends.praises_count--;
					filItem.is_praise = false;
				});
			} else {
				addCommentTop(filItem.comment_id).then(res => {
					if (!res.status) return;
					filItem.cache_extends.praises_count++;
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
					this.wordInfoData.user_info.is_follow = false;
					// 来自主要跳转
					if (this.fromPage == 'home') {
						this.$store.getters['trend/getMainData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = false));
						this.$store.getters['trend/getAtteData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = false));
						this.$store.getters['trend/getSquareData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = false));
					}
					// 来自用户详情
					if (this.fromPage == 'userinfo') {
						this.$store.getters['user/getUserPublishListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = false));
						this.$store.getters['user/getUserTopListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = false));
					}
					// 来自发现-视频跳转
					if (this.fromPage == 'find') {
						this.$store.getters['word/getWordListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = false));
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
					this.wordInfoData.user_info.is_follow = true;
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
						this.$store.getters['word/getWordListData'].filter(item => item.user_info.user_id == e.user_id).map(item => (item.user_info.is_follow = true));
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
			// 来自发现-文章跳转
			if (this.fromPage == 'find') {
				filItem = this.$store.getters['word/getWordListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
			}
			dynamicCollection(filItem.dynamic_id).then(res => {
				uni.showToast({
					title: res.msg,
					icon: res.status == 1 ? 'success' : 'none'
				});
				if (!res.status) return;

				// 用户是否已收藏
				if (filItem.is_collection) {
					filItem.cache_extends.collection_count--;
					this.wordInfoData.is_collection = filItem.is_collection = false;
					this.wordInfoData.cache_extends.collection_count--;
				} else {
					filItem.cache_extends.collection_count++;
					this.wordInfoData.is_collection = filItem.is_collection = true;
					this.wordInfoData.cache_extends.collection_count++;
				}
			});
		},
		/// 显示评论输入框
		fnCommOpen() {
			this.$refs.comm.open({
				type: 'comment',
				content: this.$store.getters['getCommContentData'],
				dynamic_id: this.dynamic_id
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
				if (this.wordInfoData.cache_extends.comments_count == 0) this.mescroll.removeEmpty();
				this.wordInfoData.cache_extends.comments_count++;
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
				// 来自发现-文章跳转
				if (this.fromPage == 'find') {
					filItem = this.$store.getters['word/getWordListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
				}
				filItem.cache_extends.comments_count++;
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
								url: `/pages/report/report?dynamic_id=${e.dynamic_id}`
							});
							break;
						case 3:
							// 删除评论与回复
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
									filCommentList.cache_extends.comments_count = filCommentList.cache_extends.comments_count - res.data.length;
									filCommentList.replies = filreplies;
									// 评论数量减少
									this.dynamic.cache_extends.comments_count = this.dynamic.cache_extends.comments_count - res.data.length;
								} else {
									// 评论发布项删除
									let filCommentList = this.commentListData.filter(item => item.comment_id != e.comment_id);
									this.$store.commit('interact/setCommentListData', filCommentList);
									// 评论数量减少
									this.dynamic.cache_extends.comments_count--;
								}
								if (this.wordInfoData.cache_extends.comments_count == 0) this.mescroll.showEmpty();
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
								// 来自发现-文章跳转
								if (this.fromPage == 'find') {
									filItem = this.$store.getters['word/getWordListData'].filter(item => item.dynamic_id == this.dynamic_id)[0];
								}
								filItem.cache_extends.comments_count = filItem.cache_extends.comments_count - res.data.length;
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

<style></style>

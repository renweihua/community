<template>
	<view>
		<!-- 发现顶部导航栏 -->
		<view class="posif posi-tlr0 z500 bgtheme">
			<!-- #ifdef APP-PLUS -->
			<view class="status-bar"></view>
			<!-- #endif -->
			<!-- 导航条 -->
			<scroll-view class="scroll-bar-find" :scroll-x="true" :show-scrollbar="false" :scroll-into-view="scrollInto" :scroll-with-animation="true">
				<view
					v-for="tab in tabBars"
					:key="tab.id"
					class="scroll-bar-finditem"
					:class="{ 'scroll-bar-finditemsh': current == tab.current }"
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
			<!-- 推荐 -->
			<swiper-item>
				<mescroll-uni v-if="status.recommend" :top="90" :bottom="112" @down="downRecommend" :up="{ use: false }">
					<!-- 轮播 -->
					<swiper autoplay="true" interval="3000" duration="300" circular="true" indicator-dots="true" indicator-active-color="#FF6699">
						<block v-for="banner in bannerListData" :key="banner.banner_id">
							<swiper-item><image style="width: 100%;height: 100%;" :src="banner.banner_cover" mode="scaleToFill" :lazy-load="true" /></swiper-item>
						</block>
					</swiper>
					<!-- 热门话题 -->
					<view class="flexr-jsb flex-fww ptb28r plr18r mb18r bgwhite">
						<block v-for="(hotspot, index) in hotTopicListData" :key="index">
							<view class="w44v f28r c555 br8r bgf8 ellipsis ptb18r plr18r" :class="{ mb18r: index == 0 || index == 1 }">#{{ hotspot.Title }}</view>
						</block>
					</view>
					<!-- 推荐荟吧 -->
					<view class="plr18r ptb18r bgwhite">
						<view class="f32r fbold c555 mb18r">推荐荟吧</view>
						<view class="flexr-jsb flex-fww flex-aic fcenter">
							<view v-for="huiba in huibaListData" :key="huiba.topic_id" class="w32v-mb2v"><huiba-card :info-data="huiba" @click="fnHuibaInfo"></huiba-card></view>
							<view class="w32v-mb2v" v-if="false"><i-icon v-if="huibaListData.length" type="jinru" size="96" color="#555555"></i-icon></view>
						</view>
					</view>
				</mescroll-uni>
			</swiper-item>

			<!-- 文章 -->
			<swiper-item>
				<mescroll-uni v-if="status.word" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(word, index) in wordListData" :key="index"><word-card :info-data="word" @user="fnUserInfo" @click="fnWordInfo"></word-card></block>
				</mescroll-uni>
			</swiper-item>
			
			<!-- 视频 -->
			<swiper-item>
				<mescroll-uni v-if="status.video" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(video, index) in videoListData" :key="index">
						<video-card :info-data="video" @user="fnUserInfo" @follow="fnUserFollow" @huiba="fnHuibaInfo" @click="fnVideoInfo"></video-card>
					</block>
				</mescroll-uni>
			</swiper-item>

			<!-- 摄影 -->
			<swiper-item>
				<mescroll-uni v-if="status.album" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<!-- 周、月榜单 -->
					<view class="flex plr18r ptb18r bgwhite mb18r">
						<view class="flex-fitem posir br8r rank-ho200r mr18r" @tap="fnAlbumRank(1)">
							<image
								style="width: 100%;height: 100%;"
								:src="rankFaceData.week ? rankFaceData.week + '_200x200.jpg/format/webp' : '/static/default_image.png'"
								mode="aspectFill"
								:lazy-load="true"
							></image>
							<image class="rank-tag" src="/static/rank_week.png" mode="aspectFit" :lazy-load="true"></image>
						</view>
						<view class="flex-fitem posir br8r rank-ho200r" @tap="fnAlbumRank(2)">
							<image
								style="width: 100%;height: 100%;"
								:src="rankFaceData.month ? rankFaceData.month + '_200x200.jpg/format/webp' : '/static/default_image.png'"
								mode="aspectFill"
								:lazy-load="true"
							></image>
							<image class="rank-tag" src="/static/rank_month.png" mode="aspectFit" :lazy-load="true"></image>
						</view>
					</view>
					<!-- 摄影展示卡列表 -->
					<view class="flexr-jsb flex-fww ptb18r plr18r bgwhite">
						<view v-for="(album, index) in albumListData" :key="index" class="w48v-mb2v">
							<album-card :info-data="album" @user="fnUserInfo" @click="fnAlbumInfo"></album-card>
						</view>
					</view>
				</mescroll-uni>
			</swiper-item>

			<!-- 话题 -->
			<swiper-item v-if="false">
				<mescroll-uni v-if="status.topic" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<block v-for="(topic, index) in topicListData" :key="index"><topic-card :info-data="topic" @huiba="fnHuibaInfo" @click="fnTopicInfo"></topic-card></block>
				</mescroll-uni>
			</swiper-item>

			<!-- 活动 -->
			<swiper-item v-if="false">
				<mescroll-uni v-if="status.org" :top="90" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
					<!-- 活动地区、状态 -->
					<view class="flexr-jsa" @tap="fnOrgProvinceState">
						<view class="flex-fitem f32r fcenter c555 hl90r">
							<text class="mr18r">{{ org.provinceName }}</text>
							<i-icon type="xia" size="36" color="#8F8F94"></i-icon>
						</view>
						<view class="flex-fitem f32r fcenter c555 hl90r">
							<text class="mr18r">{{ org.stateName }}</text>
							<i-icon type="xia" size="36" color="#8F8F94"></i-icon>
						</view>
					</view>
					<block v-for="(org, index) in orgListData" :key="index"><org-card :info-data="org" @click="fnOrgInfo"></org-card></block>
				</mescroll-uni>
			</swiper-item>
		</swiper>
		<!-- 组织活动省状态选择弹出层 -->
		<org-province-state-picker ref="orgProvinceState" @picker="fnOrgPicker"></org-province-state-picker>
	</view>
</template>

<script>
import { getBannerTopicList } from '@/api/CommonServer.js';
import { getHuibaList } from '@/api/HuibaServer.js';
import { getAlbumList, getRankFace } from '@/api/AlbumServer.js';
import { getVideoList } from '@/api/VideoServer.js';
import { getTopicList } from '@/api/TopicServer.js';
import { getWordList } from '@/api/WordServer.js';
import { getOrgList, getOrgProvinceList } from '@/api/OrgServer.js';
import { followUser } from '@/api/UserServer.js';

// 组织活动展示卡组件
import OrgCard from '@/components/org-card/org-card';
// 文章展示卡组件
import WordCard from '@/components/word-card/word-card';
// 话题展示卡组件
import TopicCard from '@/components/topic-card/topic-card';
// 视频展示卡组件
import VideoCard from '@/components/video-card/video-card';
// 摄影展示卡组件
import AlbumCard from '@/components/album-card/album-card';
// 荟吧展示卡组件
import HuibaCard from '@/components/huiba-card/huiba-card';
// 组织活动省状态选择弹出层组件
import OrgProvinceStatePicker from '@/components/org-province-state-picker/org-province-state-picker';

let listData = {
	1: getAlbumList,
	2: getVideoList,
	3: getTopicList,
	4: getWordList,
	5: getOrgList
};

export default {
	components: {
		HuibaCard,
		AlbumCard,
		VideoCard,
		TopicCard,
		WordCard,
		OrgCard,
		OrgProvinceStatePicker
	},

	props: {
		// 底部导航双击连续触发刷新
		refresh: {
			type: Boolean,
			default: false
		}
	},

	data() {
		return {
			// 导航项滑动初始id
			scrollInto: 'recommend',
			// 顶部导航滑动页选中
			current: -1,
			// 导航项列表
			tabBars: [
				{
					id: 'recommend',
					name: '推荐',
					current: 0
				},
				{
					id: 'word',
					name: '文章',
					current: 1
				},
				{
					id: 'video',
					name: '视频',
					current: 2
				},
				{
					id: 'video',
					name: '摄影',
					current: 3
				},
				// {
				// 	id: 'album',
				// 	name: '摄影',
				// 	current: 1
				// },
				// {
				// 	id: 'topic',
				// 	name: '话题',
				// 	current: 3
				// },
				// {
				// 	id: 'org',
				// 	name: '活动',
				// 	current: 5
				// }
			],
			// 激活顶部导航关联页状态
			status: {
				recommend: true,
				album: false,
				video: false,
				topic: false,
				word: false,
				org: false
			},
			// 双击刷新
			clickRefresh: false,
			// 刷新间隔
			timeOutFind: 0,
			// 组织活动地区状态
			org: {
				stateName: '全部状态',
				state: 0,
				provinceName: '全国',
				province: 0
			},
			// 刷新组件实例
			mescroll: {
				recommend: null,
				album: null,
				video: null,
				topic: null,
				word: null,
				org: null
			}
			//
		};
	},

	watch: {
		// 监听底部导航双击触发
		refresh(val) {
			if (val && !this.clickRefresh) this.fnRefreshData();
		}
	},

	computed: {
		// Banner图
		bannerListData() {
			return this.$store.getters['common/getBannerListData'];
		},
		// 热门话题
		hotTopicListData() {
			return this.$store.getters['common/getHotTopicListData'];
		},
		// 热门荟吧
		huibaListData() {
			let topics = this.$store.getters['huiba/getHuibaListData'];
			console.log(topics);
			return topics;
		},
		// 摄影榜封面
		rankFaceData() {
			return this.$store.getters['album/getRankFaceData'];
		},
		// 摄影列表
		albumListData() {
			return this.$store.getters['album/getAlbumListData'];
		},
		// 话题列表
		topicListData() {
			return this.$store.getters['topic/getTopicListData'];
		},
		// 视频列表
		videoListData() {
			return this.$store.getters['video/getVideoListData'];
		},
		// 文章列表
		wordListData() {
			return this.$store.getters['word/getWordListData'];
		},
		// 组织活动列表
		orgListData() {
			return this.$store.getters['org/getOrgListData'];
		},
		// 组织活动省市列表
		orgProvinceListData() {
			return this.$store.getters['org/getOrgProvinceListData'];
		}
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
			// 联网获取数据
			console.log(this.scrollInto, this.current);
			// 摄影榜获取数据
			if (this.current == 1 && !this.rankFaceData.hasOwnProperty('week')) {
				getRankFace().then(rankRes => {
					this.$store.commit('album/setRankFaceData', rankRes.data.Data);
				});
			}
			// 组织活动省市获取数据
			if (this.current == 5 && this.orgProvinceListData.length == 0) {
				getOrgProvinceList().then(orgProvinceRes => {
					let orgProvince = orgProvinceRes.data.Data;
					orgProvince.unshift({
						Datetime: '2016-03-20T03:12:32',
						ID: 0,
						Name: '全国',
						ParentID: 0
					});
					this.$store.commit('org/setOrgProvinceListData', orgProvince);
				});
			}
			// 默认传递参数
			let params = {
				page: mescroll.num,
				limit: mescroll.size
			};
			// 组织活动追加省市参数
			if (this.current == 5) {
				params = Object.assign(params, {
					state: this.org.state,
					province: this.org.province,
					role: 5
				});
			}
			// 多组请求数据保存分类
			listData[this.current](params)
				.then(res => {
					// 摄影
					if (this.current == 1) {
						if (mescroll.num == 1) {
							this.$store.commit('album/setAlbumListData', res.data.Data);
						} else {
							this.$store.commit('album/setAlbumListData', this.albumListData.concat(res.data.Data));
						}
					}
					// 视频
					if (this.current == 2) {
						if (mescroll.num == 1) {
							this.$store.commit('video/setVideoListData', res.data.Data);
						} else {
							this.$store.commit('video/setVideoListData', this.videoListData.concat(res.data.Data));
						}
					}
					// 话题
					if (this.current == 3) {
						if (mescroll.num == 1) {
							this.$store.commit('topic/setTopicListData', res.data.Data);
						} else {
							this.$store.commit('topic/setTopicListData', this.topicListData.concat(res.data.Data));
						}
					}
					// 文章
					if (this.current == 4) {
						if (mescroll.num == 1) {
							this.$store.commit('word/setWordListData', res.data.Data);
						} else {
							this.$store.commit('word/setWordListData', this.wordListData.concat(res.data.Data));
						}
					}
					// 组织活动
					if (this.current == 5) {
						if (mescroll.num == 1) {
							this.$store.commit('org/setOrgListData', res.data.Data);
						} else {
							this.$store.commit('org/setOrgListData', this.orgListData.concat(res.data.Data));
						}
					}
					mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size);
				})
				.catch(() => {
					mescroll.endErr();
				});
		},
		/// 推荐栏-独立下拉加载
		downRecommend(mescroll) {
			// console.log(mescroll);
			this.mescroll[this.scrollInto] = mescroll;
			Promise.all([getBannerTopicList(), getHuibaList()])
				.then(resArray => {
					// Banner
					this.$store.commit('common/setBannerListData', resArray[0].data);
					// 推荐demo数据
					this.$store.commit('common/setHotTopicListData', [
						{ ID: 1, Title: '穿汉服给祖国庆生', Link: 'huiapp://open?topic=147207', FaceSrc: '', Datetime: '2019-10-03T20:37:21', ClickCount: 1 },
						{ ID: 2, Title: '袍子的第一件入坑汉服', Link: 'huiapp://open?topic=147207', FaceSrc: '', Datetime: '2019-10-03T20:37:21', ClickCount: 1 },
						{ ID: 3, Title: '汉服小剧本征集', Link: 'huiapp://open?topic=147207', FaceSrc: '', Datetime: '2019-10-03T20:37:21', ClickCount: 1 },
						{ ID: 4, Title: '喜欢什么样的汉服搭配', Link: 'huiapp://open?topic=147207', FaceSrc: '', Datetime: '2019-10-03T20:37:21', ClickCount: 1 }
					]);

					// 荟吧列表
					console.log(resArray[0].data);
					this.$store.commit('huiba/setHuibaListData', resArray[1].data);
					mescroll.endDownScroll();
				})
				.catch(() => {
					mescroll.endErr();
				});
		},

		/// 导航选项双击刷新获取新数据
		fnRefreshData() {
			this.mescroll[this.scrollInto].scrollTo(0, 300);
			setTimeout(() => {
				if (this.current == 0) return this.mescroll[this.scrollInto].triggerDownScroll();
				this.mescroll[this.scrollInto].resetUpScroll(true);
			}, 1000);
		},
		/// 顶部导航选项点击
		fnBarClick(e) {
			let current = e.hasOwnProperty('detail') ? e.detail.current : e.current;
			console.log(current);
			this.scrollInto = this.tabBars[current].id;
			// 是否当前项点击
			if (e.hasOwnProperty('id') && this.current == current) {
				this.timeOutFind += 1;
				// 是否为刷新值和连续触发
				if (!this.clickRefresh && this.timeOutFind >= 2) {
					// 刷新值开
					this.clickRefresh = true;
					// 获取新数据
					this.fnRefreshData();
					// 定时器重置
					this.timeOutFind = setTimeout(() => {
						// 清除定时器
						clearTimeout(this.timeOutFind);
						// 连续触发记录重置
						this.timeOutFind = 0;
						// 刷新值关
						this.clickRefresh = false;
					}, 5000);
				}
				return;
			} else {
				// 改变顶部导航选中
				this.current = current;
				// 首次选中激活顶部导航关联页状态
				if (!this.status.album && current == 1) this.status.word = true;
				if (!this.status.video && current == 2) this.status.video = true;
				// 清除定时器
				clearTimeout(this.timeOutFind);
				// 连续触发记录重置
				this.timeOutFind = 0;
				// 刷新值关
				this.clickRefresh = false;
			}
		},

		/// 组织活动城市选择
		fnOrgProvinceState() {
			this.$refs.orgProvinceState.open(this.org);
		},
		/// 组织活动状态选择
		fnOrgPicker(e) {
			if (e.provinceID == -1) return;
			this.org = {
				state: e.stateID,
				stateName: e.stateName,
				province: e.provinceID,
				provinceName: e.provinceName
			};
			// 回顶刷新
			this.mescroll[this.scrollInto].scrollTo(0, 300);
			setTimeout(() => {
				this.mescroll[this.scrollInto].resetUpScroll(true);
			}, 1000);
		},

		/// 跳转用户信息页
		fnUserInfo(e) {
			uni.navigateTo({
				url: `/pages/user-info/user-info?id=${e.ID}`
			});
		},
		/// 跳转荟吧信息页
		fnHuibaInfo(e) {
			uni.navigateTo({
				url: `/pages/huiba-details/huiba-details?topic_id=${e.topic_id}`
			});
		},
		/// 用户关注
		fnUserFollow(e) {
			// 用户是否已经关注
			if (e.UserAtte) {
				uni.showModal({
					content: '确定要取消关注TA吗？',
					success: res => {
						if (res.confirm) {
							followUser(e.ID).then(delRes => {
								if (delRes.data.Data == false) return;
								this.videoListData.filter(item => item.User.ID == e.ID).map(item => (item.User.UserAtte = false));
								// 登录用户关注数减
								let tempUser = this.$store.getters['user/getLoginUserInfoData'];
								tempUser.user_info.follows_count--;
								this.$store.commit('user/setLoginUserInfoData', tempUser);
							});
						}
					}
				});
				return;
			} else {
				followUser(e.ID).then(addRes => {
					if (addRes.data.Data == false) return;
					this.videoListData.filter(item => item.User.ID == e.ID).map(item => (item.User.UserAtte = true));
					// 登录用户关注数加
					let tempUser = this.$store.getters['user/getLoginUserInfoData'];
					tempUser.user_info.follows_count++;
					this.$store.commit('user/setLoginUserInfoData', tempUser);
				});
			}
		},
		/// 跳转视频详情页
		fnVideoInfo(e) {
			uni.navigateTo({
				url: `/pages/video-details/video-details?id=${e.ID}&fromPage=find&current=${this.current}`
			});
		},
		/// 跳转话题详情页
		fnTopicInfo(e) {
			uni.navigateTo({
				url: `/pages/topic-details/topic-details?id=${e.ID}&fromPage=find&current=${this.current}`
			});
		},
		/// 跳转文章详情页
		fnWordInfo(e) {
			uni.navigateTo({
				url: `/pages/word-details/word-details?id=${e.ID}&fromPage=find&current=${this.current}`
			});
		},
		/// 跳转组织活动详情页
		fnOrgInfo(e) {
			uni.navigateTo({
				url: `/pages/org-details/org-details?id=${e.ID}&fromPage=find&current=${this.current}`
			});
		},
		/// 跳转摄影详情页
		fnAlbumInfo(e) {
			uni.navigateTo({
				url: `/pages/album-details/album-details?id=${e.ID}&fromPage=find&current=${this.current}`
			});
		},
		/// 跳转摄影榜单页
		fnAlbumRank(current) {
			uni.navigateTo({
				url: `/pages/album-rank/album-rank?current=${current}`
			});
		}
		//
	}
};
</script>

<style>
/* 滑动scroll导航条 */
.scroll-bar-find {
	display: flex;
	flex-direction: row;
	white-space: nowrap;
	flex-wrap: nowrap;
	overflow: hidden;
	background: #ff6699;
}

/* 滑动scroll导航条项*/
.scroll-bar-finditem {
	display: inline-block;
	width: 20%;
	height: 86rpx;
	line-height: 86rpx;
	text-align: center;
	font-size: 32rpx;
	color: rgba(255, 255, 255, 0.6);
	border-bottom: 4rpx transparent solid;
}

.scroll-bar-finditemsh {
	color: #ffffff;
	border-bottom: 4rpx #ffffff solid;
	font-size: 36rpx;
	font-weight: bold;
}

/*摄影榜*/
.rank-ho200r {
	height: 200rpx;
	overflow: hidden;
}

.rank-tag {
	width: 112rpx;
	height: 36rpx;
	position: absolute;
	right: 0;
	bottom: 0;
}
</style>

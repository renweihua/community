<template>
	<view class="bgwhite mb18r">
		<!-- 头部显示信息含时间  -->
		<view class="flexr-jsb flex-aic plr18r ptb18r" v-if="head">
			<view class="flex" @tap="$_click('user')">
				<user-avatar :src="calUserAvater" tag="" size="md"></user-avatar>
				<view class="flexc-jsa ml28r">
					<view>
						<text class="f28r fbold mr18r">{{item.user_info.nick_name}}</text>
						<i-icon v-if="[0, 1].indexOf(item.user_info.user_sex) > -1" :type="item.user_info.user_sex_text == '男' ? 'nan':'nv' "
						 size="28" :color="item.user_info.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
					</view>
					<view class="f24r cgray">{{calDatetime}}</view>
				</view>
			</view>
			<!-- 更多按钮，暂时隐藏，功能还没有完善起来 -->
			<view class="w128r hl80r fcenter">
				<i-icon type="xia" size="48" color="#999999" @click="fnActionSheet"></i-icon>
			</view>
		</view>
		<view class="flex flex-aic plr18r ptb18r" v-else>
			<view class="f28r cgray hl80r">{{calDatetime}}</view>
		</view>
		<!-- 中心内容 -->
		<view class="plr18r pb18r">
			<!-- 标题 -->
			<view class="ellipsis f32r fbold c111 mb18r" v-if="calTitle" @tap="$_click('click')">{{calTitle}}</view>
			<!-- 内容 -->
			<view class="fword f28r c555" :class="{mb18r: calImageSrcs}" @tap="$_click('click')" v-html="calStringCut"></view>
			<!-- 摄影信息 -->
			<view class="bgf8 ptb18r" v-if="calAlbum" :class="{mb18r: calImageSrcs}" @tap="$_click('click')">
				<view class="mlr18r f24r cgray ellipsis" v-for="album in calAlbum" :key="album.title">
					<text>{{album.title}}</text>
					<text class="mlr8r">|</text>
					<text class="c555 mlr8r" v-for="item in album.list" :key="item.id">{{item.name}}</text>
				</view>
			</view>
			<!-- 视频格 -->
			<view class="trend-ho400r posir posi-lr0" v-if="calVideoCover" @tap="$_click('click')">
				<image class="hw100v br4r" :src="calVideoCover" :lazy-load="true" mode="aspectFill"></image>
				<image class="trend-play z5" src="/static/video_play.png" :lazy-load="true" mode="aspectFit"></image>
			</view>
			<!-- 图片格 -->
			<view class="flex flex-fww" v-else>
				<block v-for="(img,index) in calImageSrcs" :key="index">
					<image class="hw100v br4r flex-33v" :class="{mlr05v: index==1 || index==4 || index==7,mb05v: (index==1  && calImageSrcs.length>3) || (index==4 && calImageSrcs.length>6)}"
					 @tap="fnPreviewImage(index)" :src="img" :lazy-load="true" mode="widthFix"></image>
				</block>
			</view>
			<!-- 荟吧标签 -->
			<view class="flex flex-fww">
				<block v-if="item.topic">
					<text class="huiba-tag mr18r mt18r" @tap="$emit('huiba', item.topic)">{{item.topic.topic_name}}</text>
				</block>
			</view>
		</view>
		<!-- 尾部 -->
		<view class="flexr-jsa bts2r">
			<view class="trend-w20v hl80r fcenter" @tap="$_click('top')">
				<i-icon type="dianzan" size="48" :color="item.is_praise?'#FF6699':'#8f8f94'"></i-icon>
				<text class="ml8r f28r cgray">
				{{item.is_praise?'已赞':'赞'}}
				<span v-if="item.cache_extends.praises_count > 0">({{item.cache_extends.praises_count}})</span>
				</text>
			</view>
			<view class="trend-w20v hl80r fcenter" @tap="$_click('comm')">
				<i-icon type="pinglun" size="48" color="#8f8f94"></i-icon>
				<text class="ml8r f28r cgray">{{item.cache_extends.comment_count || '评论'}}</text>
			</view>
			<view class="trend-w20v hl80r fcenter" @tap="$_click('save')">
				<i-icon type="shoucang" size="48" :color="item.is_collection?'#FF6699':'#8f8f94'"></i-icon>
				<text class="ml8r f28r cgray">
				{{item.is_collection?'已收藏':'收藏'}}
				<span v-if="item.cache_extends.collection_count > 0">({{item.cache_extends.collection_count}})</span>
				</text>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		fnCutString,
		fnFormatDate
	} from "@/utils/CommonUtil.js"
	import {
		previewImage
	} from "@/utils/UniUtil.js"
	import {
		getUserExistsBlack
	} from "@/api/UserServer.js"

	/**
	 * 动态信息项卡片组件
	 * @property {Boolean} head 显示顶部头像区
	 * @property {Object} item 动态信息
	 * @event {Function} user 用户头像 点击事件
	 * @event {Function} top 点赞 点击事件
	 * @event {Function} comm 评论 点击事件
	 * @event {Function} save 收藏 点击事件
	 * @event {Function} follow 更多-关注 点击事件
	 * @event {Function} black 更多-拉黑 点击事件
	 * @event {Function} report 更多-举报 点击事件
	 * @event {Function} click 卡片 点击事件
	 * @event {Function} huiba 荟吧标签 点击事件
	 */
	export default {
		name: 'trend-card',
		props: {
			// 动态信息
			item: {
				type: Object,
				default: () => {
					return {}
				}
			},
			// 显示顶部头像区
			head: {
				type: Boolean,
				default: true
			}
		},
		computed: {
			/// 计算显示用户头像
			calUserAvater() {
				return this.item.user_info ? this.item.user_info.user_avatar : '/static/default_avatar.png'
			},
			/// 计算摄影信息
			calAlbum() {
				if (this.item.ObjectType != 'album') return false
				let albumInfo = []
				let data = JSON.parse(this.item.ObjectData)
				if (data == null) return false
				// 出镜
				if (data.hasOwnProperty('model')) {
					albumInfo.push({
						title: '出镜',
						list: data.model
					})
				}
				// 摄影
				if (data.hasOwnProperty('photoer')) {
					albumInfo.push({
						title: '摄影',
						list: data.photoer
					})
				}
				// 服装
				if (data.hasOwnProperty('store')) {
					albumInfo.push({
						title: '服装',
						list: data.store
					})
				}
				return albumInfo
			},
			/// 计算显示视频封面
			calVideoCover() {
				if (this.item.dynamic_type == 2){
					let cover = '/static/default_image.png';
					if (this.item.dynamic_images) cover = this.item.dynamic_images[0];
					return cover;
				}else{
					 return false;
				}
			},
			/// 计算显示图片格
			calImageSrcs() {
				let imgArray = this.item.dynamic_images || [];
				return imgArray.length > 9 ? imgArray.slice(0, 9) : imgArray;
			},
			/// 计算内容截取字符长度 180
			calStringCut() {
				let content = this.item.dynamic_content + ''; //转成字符串
				if (content == null || content == "" || content == "undefined" || content == "null") return;
				let {
					cutstring,
					cutflag
				} = fnCutString(content, 180);
				return cutflag ? cutstring + "..." : cutstring;
			},
			/// 计算格式友好时间 几天前
			calDatetime() {
				return fnFormatDate(this.item.created_time);
			},
			/// 计算标题字符转json
			calTitle() {
				if (this.item.dynamic_title && this.item.dynamic_type != 0) {
					return this.item.dynamic_title;
				}
				return false;
			}
		},
		methods: {
			/// 预览图片组
			fnPreviewImage(current) {
				let urls = this.item.dynamic_images.map(url => url);
				previewImage(current, urls);
			},
			/// 更多菜单操作
			async fnActionSheet() {
				let login_user = this.$store.getters['user/getLoginUserInfoData'];
				let atteTitle = this.item.user_info.is_follow ? '取消关注' : '关注TA';
				// this.item.user_info.is_black 一直为false 这里用网络来判断存在咯
				let blackRes = await getUserExistsBlack(this.item.user_info.user_id);
				this.item.user_info.is_black = blackRes.data.Data;
				// 黑名单状态
				let blackTitle = this.item.user_info.is_black ? '移出黑名单' : '将TA拉黑';
				let blackContent = this.item.user_info.is_black ? '是否将该用户移出黑名单' : '拉黑后再广场将看不到TA的动态，TA将无法对你发消息、评论。';
				console.log('---this.item---')
				console.log(this.item)
				console.log(this.item.user_info.is_self);
				uni.showActionSheet({
					itemList: this.item.user_info.is_self ? [blackTitle, '举报'] : [atteTitle, blackTitle, '举报'],
					success: res => {
						if (res.tapIndex == 0){
							return this.item.user_info.is_self ? uni.showModal({
								content: blackContent,
								success: res => {
									if (res.confirm) {
										this.$emit('black', this.item)
									}
								}
							}) : this.$emit('follow', this.item);
						}
						if (res.tapIndex == 1) {
							this.item.user_info.is_self ? this.$emit('report', this.item) : 
							uni.showModal({
								content: blackContent,
								success: res => {
									if (res.confirm) {
										this.$emit('black', this.item)
									}
								}
							});
							return
						}
						if (res.tapIndex == 2) return this.$emit('report', this.item);
					}
				});
			},
			/// 点击事件反馈
			$_click(type = 'click') {
				this.$emit(type, this.item)
			}
			//
		}
	}
</script>

<style>
	/*底部项的宽度*/
	.trend-w20v {
		width: 30%;
	}

	/*视频格固定高度*/
	.trend-ho400r {
		height: 400rpx;
		overflow: hidden;
	}

	/*视频播放icon位置大小*/
	.trend-play {
		position: absolute;
		top: 40%;
		left: 40%;
		width: 100rpx;
		height: 100rpx;
	}
</style>

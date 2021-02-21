<template>
	<view class="mb18r bgwhite">
		<view class="flex flex-aic" @tap="fnOpenWin('news-notice')">
			<image class="hw64r plr28r" src="/static/icon-news/icon_notice.png" mode="aspectFit"></image>
			<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
				<text class="f36r c111 flex-gitem">公告</text>
				<text class="f32r fright cgray mr18r" v-if="newsCountData.NoticeCount">
					<text class="ctheme mr18r">{{ newsCountData.NoticeCount }}</text>
					条未读
				</text>
				<i-icon type="you" size="42" color="#8F8F94"></i-icon>
			</view>
		</view>
		<view class="flex flex-aic" @tap="fnOpenWin('news-remind')">
			<image class="hw64r plr28r" src="/static/icon-news/icon_remind.png" mode="aspectFit"></image>
			<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
				<text class="f36r c111 flex-gitem">提醒</text>
				<text class="f32r fright cgray mr18r" v-if="newsCountData.system_unreads">
					<text class="ctheme mr18r">{{ newsCountData.system_unreads }}</text>
					条未读
				</text>
				<i-icon type="you" size="42" color="#8F8F94"></i-icon>
			</view>
		</view>
		<view class="flex flex-aic" @tap="fnOpenWin('news-top')">
			<image class="hw64r plr28r" src="/static/icon-news/icon_top.png" mode="aspectFit"></image>
			<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
				<text class="f36r c111 flex-gitem">赞</text>
				<text class="f32r fright cgray mr18r" v-if="newsCountData.praise_unreads">
					<text class="ctheme mr18r">{{ newsCountData.praise_unreads }}</text>
					条未读
				</text>
				<i-icon type="you" size="42" color="#8F8F94"></i-icon>
			</view>
		</view>
		<view class="flex flex-aic" @tap="fnOpenWin('news-comment')">
			<image class="hw64r plr28r" src="/static/icon-news/icon_comment.png" mode="aspectFit"></image>
			<view class="flex-fitem flex flex-aic hl90r bbs2r pr28r">
				<text class="f36r c111 flex-gitem">评论</text>
				<text class="f32r fright cgray mr18r" v-if="newsCountData.comment_unreads">
					<text class="ctheme mr18r">{{ newsCountData.comment_unreads }}</text>
					条未读
				</text>
				<i-icon type="you" size="42" color="#8F8F94"></i-icon>
			</view>
		</view>
		<view v-if="false" class="flex flex-aic" @tap="fnOpenWin('news-topic')">
			<image class="hw64r plr28r" src="/static/icon-news/icon_topic.png" mode="aspectFit"></image>
			<view class="flex-fitem flex flex-aic hl90r pr28r">
				<text class="f36r c111 flex-gitem">话题</text>
				<text class="f32r fright cgray mr18r" v-if="newsCountData.TopicCount">
					<text class="ctheme mr18r">{{ newsCountData.TopicCount }}</text>
					条未读
				</text>
				<i-icon type="you" size="42" color="#8F8F94"></i-icon>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	computed: {
		// 未读消息数
		newsCountData() {
			let news = this.$store.getters['getNewsCountData'];
			console.log(news);
			return news;
		}
	},
	methods: {
		/// 跳转打开新窗口
		fnOpenWin(type) {
			uni.navigateTo({
				url: `/pages/news/${type}/${type}`
			});
		}
	}
};
</script>

<style></style>

<template>
	<view class="br8r mtb18r mlr18r bgwhite" style="overflow: hidden;">
		<image
			v-if="infoData.dynamic_images.length > 0"
			class="word-cover"
			@tap="$emit('click', infoData)"
			:src="infoData.dynamic_images ? infoData.dynamic_images[0] : '/static/default_image.png'"
			mode="scaleToFill"
			:lazy-load="true"
		></image>
		<view class="ptb18r plr18r">
			<view class="f36r fbold fcenter c555 ellipsis" @tap="$emit('click', infoData)">{{ infoData.dynamic_title }}</view>
			<view class="f28r cgray mtb18r" @tap="$emit('click', infoData)">{{ calStringCut }}</view>
			<view class="flex flex-aic">
				<user-avatar
					@click="$emit('user', infoData.user_info)"
					:src="infoData.user_info.user_avatar ? infoData.user_info.user_avatar : '/static/default_avatar.png'"
					tag=""
					size="sm"
				></user-avatar>
				<text class="f28r c555 ml18r flex-fitem" @tap="$emit('click', infoData)">{{ infoData.user_info.nick_name }}</text>
				<view @tap="$emit('click', infoData)">
					<i-icon type="shijian" size="32" color="#8F8F94"></i-icon>
					<text class="f24r cgray ml8r">{{ calDatetime }}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
import { fnCutString, fnFormatDate } from '@/utils/CommonUtil.js';

/**
 * 文章展示卡组件
 * @property {Object} infoData 信息数据
 * @event {Function} user 用户头像 点击事件
 * @event {Function} click 展示卡 点击事件
 */
export default {
	name: 'word-card',

	props: {
		/**
		 * 信息数据
		 */
		infoData: {
			type: Object,
			default: () => {
				return {
				};
			}
		}
	},
	computed: {
		/// 内容截取字符长度80
		calStringCut() {
			let content = this.infoData.dynamic_content + ''; //转成字符串
			if (content == null || content == '' || content == 'undefined' || content == 'null') return;
			let { cutstring, cutflag } = fnCutString(content, 80);
			return cutflag ? cutstring + '...' : cutstring;
		},
		/// 时间格式
		calDatetime() {
			return fnFormatDate(this.infoData.created_time);
		}
	}
};
</script>

<style lang="scss">
/*封面图*/
.word-cover {
	height: 300rpx;
	width: 100%;
	border-radius: 4rpx;
	display: block;
}

/*展示卡*/
.word-card {
	border-radius: 8rpx;
	background: #ffffff;
	margin: 24rpx;

	/*标题*/
	.title {
		font-size: 36rpx;
		font-weight: bold;
		color: #333333;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		text-align: center;
		padding: 18rpx 24rpx;
	}

	/*内容*/
	.content {
		font-size: 28rpx;
		color: #999999;
		height: 80rpx;
		padding: 0 24rpx;
		overflow: hidden;
	}

	/*信息*/
	.info {
		display: flex;
		align-items: center;
		padding: 24rpx 24rpx;

		&-user {
			flex: 1;
			display: flex;
			align-items: center;

			.avatar {
				height: 48rpx;
				width: 48rpx;
				border-radius: 50%;
			}

			.nickname {
				margin-left: 12rpx;
				font-size: 28rpx;
				color: #666666;
			}
		}

		&-date {
			font-size: 24rpx;
			color: #999999;
		}
	}
}
</style>

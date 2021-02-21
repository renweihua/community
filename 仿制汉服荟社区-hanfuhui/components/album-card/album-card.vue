<template>
	<view class="br8r" style="overflow: hidden;">
		<image
			v-if="infoData.dynamic_images.length > 0"
			class="album-cover"
			@tap="$emit('click', infoData)"
			:src="infoData.dynamic_images ? infoData.dynamic_images[0] : '/static/default_image.png'"
			mode="scaleToFill"
			:lazy-load="true"
		></image>
		<view class="bbs2r bls2r brs2r flex flex-aic plr18r ptb18r">
			<user-avatar
				@click="$emit('user', infoData.user_info)"
				:src="infoData.user_info.user_avatar ? infoData.user_info.user_avatar : '/static/default_avatar.png'"
				tag=""
				size="sm"
			></user-avatar>
			<view class="w128r flex-gitem f28r mlr18r c555 ellipsis" @tap="$emit('user', infoData.user_info)">{{ infoData.user_info.nick_name }}</view>
			<view class="flex flex-aic" @tap="$emit('click', infoData)">
				<i-icon type="remen" size="28" color="#FF6699"></i-icon>
				<text class="f24r cgray ml8r">{{ infoData.praise_count }}</text>
			</view>
		</view>
	</view>
</template>

<script>
/**
 * 摄影展示卡组件
 * @property {Object} infoData 信息数据
 * @event {Function} user 用户头像 点击事件
 * @event {Function} click 展示卡 点击事件
 */
export default {
	name: 'album-card',
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
	}
};
</script>

<style>
/* 摄影封面 */
.album-cover {
	height: 350rpx;
	width: 100%;
	display: block;
}
</style>

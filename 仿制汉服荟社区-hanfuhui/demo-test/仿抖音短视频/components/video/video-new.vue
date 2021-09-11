<template>
	<view>
		<video
			:src="video_url"
			preload
			:show-play-btn="true"
			:controls="false"
			:style="{ height: height, width: width }"
			:loop="true"
			:id="`video_${video_id}`"
			objectFit="fill"
			:enable-progress-gesture="false"
			@click="clickVideo"
			ref="video_url"
			play-btn-position="center"
			class="video"
			:poster="cover_url"
			@timeupdate="timeupdate">
		</video>
		<cover-image class="play" v-if="show_play" src="/static/play_1.png"></cover-image>
		<cover-view class="cover-view-left">
			<text class="view-left-text">@{{ nickname }}</text>
			<view class="view-left-text-content">
				<text class="text-content-text">{{ video_describe }}</text>
			</view>
		</cover-view>
		<cover-view class="cover-view-right">
			<cover-image :src="cover_url" class="avater img" @click.stop="tapAvater"></cover-image>
			<text class="right-follow">+</text>
			<cover-image :src="is_dianzan ? '/static/img/axc.png' : '/static/img/bed.png'" class="img-left" @click.stop="tapLove"></cover-image>
			<text class="right-text">{{ dianzan }}</text>
			<cover-image src="/static/img/ay2.png" style="height: 80upx;" class="img-left" @click.stop="tapMsg"></cover-image>
			<text class="right-text">{{ pinglun }}</text>
			<cover-image src="/static/img/b6p.png" style="height: 76upx;" class="img-left" @click.stop="tapShare"></cover-image>
			<text class="right-text">{{ zhuanfa }}</text>
			<cover-image src="/static/changpian.png" class="musicIcon img"></cover-image>
		</cover-view>
		<!-- <cover-view class="progressBar" :animation="animationData" :style="`width:${barWidth}px`"></cover-view> -->
	</view>
</template>
<script>
export default {
	props: {
		video_id: {
			type: Number,
			default: 0
		},
		nickname: {
			type: String,
			default: ''
		},
		video_describe: {
			type: String,
			default: ''
		},
		cover_url: {
			type: String,
			default: ''
		},
		video_url: {
			type: String,
			default: ''
		},
		dianzan: {
			type: Number,
			default: 0
		},
		is_dianzan: {
			type: Boolean,
			default: false
		},
		pinglun: {
			type: Number,
			default: 0
		},
		zhuanfa: {
			type: Number,
			default: 0
		},
		play: {
			type: Boolean,
			default: false
		},
		index: {
			type: Number,
			default: 0
		},
		cur_index: {
			type: Number,
			default: 0
		},
		height: {
			type: String,
			default: ''
		},
		width: {
			type: String,
			default: ''
		}
	},
	data() {
		return {
			time: 0,
			duration: 10,
			barWidth:0,
			animationData: {},
			times:null,
			show_play:false
		};
	},
	methods: {
		timeupdate(event) {
			let t_w = parseInt(this.width);
			this.duration = event.detail.duration;
			this.time = event.detail.currentTime;
			let width = (this.time / this.duration) * t_w;
			let w = 0;
			// console.log(t_w);
			// console.log(width);
			// console.log(this.barWidth);
			// if(width > this.barWidth){
			// 	w = (width - this.barWidth) /20;
			// 	//#ifndef APP-PLUS-NVUE
			// 	w = (width - this.barWidth) /5;
			// 	//#endif
			// }
			// if(this.barWidth >= t_w || width >= t_w || (this.barWidth >= 300 && width <= 150)){
			// 	this.barWidth = 0;
			// 	clearInterval(this.times);
			// 	console.log('播放完毕');
			// }else{
			// 	clearInterval(this.times);
			// 	this.times = setInterval(()=>{
			// 		this.barWidth += w;
			// 	},50)
			// }
		},
		clickVideo() {
			console.log('单视频点击事件');
			this.$emit('click');
		},
		videoPlay() {
			if (this.play) {
				console.log('播放视频',`video_${this.video_id}`);
				this.videoCtx = uni.createVideoContext(`video_${this.video_id}`, this);
				this.videoCtx.play();
				this.show_play = false;
			} else {
				console.log('暂停视频',`video_${this.video_id}`);
				this.videoCtx = uni.createVideoContext(`video_${this.video_id}`, this);
				this.videoCtx.pause();
				this.show_play = true;
			}
		}
	},
	watch: {
		play(newVal, oldVal) {
			this.videoPlay();
		}
	}
};
</script>

<style>
.video {
	width: 100%;
	height: 100%;
	position: relative;
}
.play{
	position: absolute;
	width: 20vw;
	height: 20vw;
	left: 40vw;
	top: 40vh;
	opacity: 0.5;
}
.progressBar {
	border-radius: 2upx;
	height: 4upx;
	background-color: #FF4500;
	z-index: 999999;
	position: absolute;
	bottom: 68rpx;
	/* #ifndef APP-PLUS-NVUE */
	bottom: 0rpx;
	/* #endif */
}

.cover-view-left {
	position: absolute;
	margin-left: 20upx;
	width: 580upx;
	bottom: 100upx;
	z-index: 9999;
	font-size: 14px;
	color: #ffffff;
	/* #ifndef APP-PLUS-NVUE */
	bottom: 30upx;
	/* #endif */
}
.left-text {
	font-size: 14px;
	color: #ffffff;
}

.cover-view-right {
	position: absolute;
	bottom: 40px;
	right: 30upx;
	z-index: 9999;
	text-align: center;
	/* #ifndef APP-PLUS-NVUE */
	bottom: 0upx;
	/* #endif  */
}

.avater {
	border-radius: 50%;
	border-width: 2px;
	border-style: solid;
	border-color: #ffffff;
}

.img {
	height: 90upx;
	width: 90upx;
	margin-bottom: 50upx;
}

.img-left {
	width: 80upx;
	height: 66upx;
	padding-left: 4px;
}
.right-follow {
	position: absolute;
	z-index: 100;
	top: 75upx;
	left: 28upx;
	color: #ffffff;
	font-size: 16px;
	width: 34upx;
	height: 34upx;
	background-color: #f12f5b;
	text-align: center;
	line-height: 34upx;
	border-radius: 17upx;
}

.right-text {
	color: #ffffff;
	font-size: 11px;
	text-align: center;
	margin-bottom: 40upx;
}
.musicIcon {
	margin-top: 40upx;
	/* #ifndef APP-PLUS-NVUE */
	animation: rotating 3s linear infinite;
	/* #endif */
}
@keyframes rotating {
	from {
		transform: rotate(0);
	}
	to {
		transform: rotate(360deg);
	}
}
.view-left-text-content {
	flex: 1;
}
.view-left-text {
	color: #ffffff;
	font-size: 18px;
	margin-bottom: 10upx;
	font-weight: bold;
}
.text-content-text {
	color: #ffffff;
	font-size: 16px;
	line-height: 50upx;
	height: 100upx;
	overflow: hidden;
}
</style>

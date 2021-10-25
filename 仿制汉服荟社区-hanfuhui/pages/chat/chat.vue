<template>
	<view>
	    <!-- 商城顶部导航栏 -->
	    <view class="posif posi-tlr0 z500 bgtheme">
			<!-- #ifdef APP-PLUS -->
			<view class="status-bar"></view>
			<!-- #endif -->
			<view class="hl90r pr28r" style="text-align: center;color: #FFFFFF;"> 会员列表 </view>
	    </view>
	    <!-- 好友列表 -->
		<mescroll-uni top="250" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
			<view class="list">
				<view
					class="flex_col"
					@longpress="onLongPress"
					:class="{ active: pickerUserIndex == index }"
					@tap="chat(item)"
					v-for="(item, index) in users"
					:key="index"
					:data-index="index"
				>
					<image :src="item.user_avatar ? item.user_avatar : '../../static/logo.png'" mode="aspectFill"></image>
					<view class="flex_grow">
						<view class="flex_col">
							<view class="flex_grow">{{ item.nick_name }}</view>
							<view class="time">{{ item.updated_time }}</view>
						</view>
						<view class="info">{{ item.basic_extends ? item.basic_extends.user_introduction : '' }}</view>
					</view>
				</view>
				<!--
				<view
					class="flex_col"
					@longpress="onLongPress"
					:class="{ active: pickerUserIndex == index }"
					@tap="listTap"
					v-for="(item, index) in userList"
					:key="index"
					:data-index="index"
				>
					<image src="../../static/logo.png" mode="aspectFill"></image>
					<view class="flex_grow">
						<view class="flex_col">
							<view class="flex_grow">{{ item.name }}</view>
							<view class="time">{{ item.time }}</view>
						</view>
						<view class="info">{{ item.info }}</view>
					</view>
				</view>
				-->
			</view>
			<view class="shade" v-show="showShade" @tap="hidePop">
				<view class="pop" :style="popStyle" :class="{ show: showPop }">
					<view v-for="(item, index) in popButton" :key="index" @tap="pickerMenu" :data-index="index">{{ item }}</view>
				</view>
			</view>
		</mescroll-uni>
	</view>
</template>

<script>
import { friends } from '@/api/friend.js';
import {
	goLogin
} from '@/api/CommonServer.js';

export default {
	components: {},
	props: {
		// 连续触发刷新
		refresh: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return {
			// 滚动区实例
			mescroll: null,

			users: [],
			userList: [],
			/* 窗口尺寸 */
			winSize: {},
			/* 显示遮罩 */
			showShade: false,
			/* 显示操作弹窗 */
			showPop: false,
			/* 弹窗按钮列表 */
			popButton: ['标为关注', '置顶聊天', '删除该聊天'],
			/* 弹窗定位样式 */
			popStyle: '',
			/* 选择的用户下标 */
			pickerUserIndex: -1
		};
	},
	watch: {
		// 监听底部导航双击触发
		refresh(newVal) {
			if (newVal) this.fnRefreshData();
		}
	},
	computed: {
	},
	mounted: function(){
		// 获取登录账户用户信息
		let user_info = this.$store.getters['user/getLoginUserInfoData'].user_info;
		if(!user_info){
			// 自动跳转登录页
			goLogin();
			return false;
		}
	},
	methods: {
		chat(item){
			uni.navigateTo({
				// url: `/pages/chat/chat1?friend_id=` + item.user_id
				url: `/pages/chat/private-chat?friend_id=` + item.user_id
			});
		},
		demoLoad() {
			console.log('---onload---');
			this.getWindowSize();

			// #ifdef H5
			document.onLong = function(e) {
				var e = e || window.event;
				e.preventDefault();
			};
			// #endif
		},
		/* 获取窗口尺寸 */
		getWindowSize() {
			uni.getSystemInfo({
				success: res => {
					this.winSize = {
						witdh: res.windowWidth,
						height: res.windowHeight
					};
				}
			});
		},
		/* 长按监听 */
		onLongPress(e) {
			let [touches, style, index] = [e.touches[0], '', e.currentTarget.dataset.index];

			/* 因 非H5端不兼容 style 属性绑定 Object ，所以拼接字符 */
			if (touches.clientY > this.winSize.height / 2) {
				style = `bottom:${this.winSize.height - touches.clientY}px;`;
			} else {
				style = `top:${touches.clientY}px;`;
			}
			if (touches.clientX > this.winSize.witdh / 2) {
				style += `right:${this.winSize.witdh - touches.clientX}px`;
			} else {
				style += `left:${touches.clientX}px`;
			}

			this.popStyle = style;
			this.pickerUserIndex = Number(index);
			this.showShade = true;
			this.$nextTick(() => {
				setTimeout(() => {
					this.showPop = true;
				}, 10);
			});
		},
		/* 隐藏弹窗 */
		hidePop() {
			this.showPop = false;
			this.pickerUserIndex = -1;
			setTimeout(() => {
				this.showShade = false;
			}, 250);
		},
		/* 选择菜单 */
		pickerMenu(e) {
			let index = Number(e.currentTarget.dataset.index);
			// console.log(`第${this.pickerUserIndex + 1}个用户,第${index + 1}个按钮`);
			// 在这里开启你的代码秀

			uni.showToast({
				title: `第${this.pickerUserIndex + 1}个用户,第${index + 1}个按钮`,
				icon: 'none',
				mask: true,
				duration: 600
			});

			/* 
			 因为隐藏弹窗方法中会将当前选择的用户下标还原为-1,
			 如果行的菜单方法存在异步情况，请在隐藏之前将该值保存，或通过参数传入异步函数中
			 */
			this.hidePop();
		},
		/// mescroll组件初始化的回调,可获取到mescroll对象
		mescrollInit(mescroll) {
			this.mescroll = mescroll;
		},
		/// 下拉刷新的回调
		downCallback(mescroll) {
			// 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
			this.mescroll.resetUpScroll();
		},
		/// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
		upCallback(mescroll) {
			this.demoLoad();
			
			friends().then(res => {
				this.users = res.data;
			})
			.catch(() => {
			});
			
			this.showShade = false;
		},
		// 获取新数据
		fnRefreshData() {
			this.mescroll.scrollTo(0, 300);
			setTimeout(() => {
				this.mescroll.resetUpScroll(true);
			}, 1000);
		}
	}
};
</script>

<style scoped lang="scss">
/* 列式弹性盒子 */
.flex_col {
	display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	justify-content: flex-start;
	align-items: center;
	align-content: center;
}

/* 弹性盒子弹性容器 */
.flex_col .flex_grow {
	width: 0;
	-webkit-box-flex: 1;
	-ms-flex-positive: 1;
	flex-grow: 1;
}

.flex_row .flex_grow {
	-webkit-box-flex: 1;
	-ms-flex-positive: 1;
	flex-grow: 1;
}

/* 弹性盒子允许换行 */
.flex_col.flex_wrap {
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
}

/* 列表 */
.list {
	background-color: #fff;
	font-size: 28upx;
	color: #333;
	user-select: none;
	touch-callout: none;

	& > view {
		padding: 24upx 30upx;
		position: relative;

		&:active,
		&.active {
			background-color: #f3f3f3;
		}

		image {
			height: 80upx;
			width: 80upx;
			border-radius: 4px;
			margin-right: 20upx;
		}

		& > view {
			line-height: 40upx;

			.time,
			.info {
				color: #999;
				font-size: 24upx;
			}

			.time {
				width: 150upx;
				text-align: right;
			}

			.info {
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
			}
		}
	}

	& > view:not(:first-child) {
		margin-top: 1px;

		&::after {
			content: '';
			display: block;
			height: 0;
			border-top: #ccc solid 1px;
			width: 620upx;
			position: absolute;
			top: -1px;
			right: 0;
			transform: scaleY(0.5); /* 1px像素 */
		}
	}
}

/* 遮罩 */
.shade {
	position: fixed;
	z-index: 100;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	-webkit-touch-callout: none;

	.pop {
		position: fixed;
		z-index: 101;
		width: 200upx;
		box-sizing: border-box;
		font-size: 28upx;
		text-align: left;
		color: #333;
		background-color: #fff;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
		line-height: 80upx;
		transition: transform 0.15s ease-in-out 0s;
		user-select: none;
		-webkit-touch-callout: none;
		transform: scale(0, 0);

		&.show {
			transform: scale(1, 1);
		}

		& > view {
			padding: 0 20upx;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			user-select: none;
			-webkit-touch-callout: none;

			&:active {
				background-color: #f3f3f3;
			}
		}
	}
}
</style>

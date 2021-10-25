<template>
	<view class="page-session">
		<view class="tips color_fff size_12 align_c" :class="{ show: ajax.loading }" @tap="getHistoryMsg">{{ ajax.loadText }}</view>
		<view class="box-1" id="list-box">
			<view class="talk-list chat-message">
				<view
					v-for="(item, index) in messages_list"
					:key="index"
					:id="`msg-${item.record_id}`"
					class="item chat-message-item"
					:class="item.user_id == login_user.user_id ? 'push is-right' : 'pull is-left'"
				>
					<!-- 如果上一条记录，在5分钟之内，那么此记录无需展示时间 -->
					<uni-text class="date" v-if="(parseInt(messages_list[index-1] ? messages_list[index-1].created_time : 0) + parseInt(60 * 5)) < item.created_time">
						<span>{{ getDateByTime(item.created_time) }}</span>
					</uni-text>
					<uni-view class="main">
						<uni-view class="avatar">
							<uni-view class="cl-avatar cl-avatar--circle" style="height: 38px; width: 38px;">
								<uni-image class="cl-avatar__target">
									<div
										style="background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;"
										:style="{ backgroundImage: 'url(' + (item.user_info ? item.user_info.user_avatar : '') + ')' }"
									></div>
									<img :src="item.user_info ? item.user_info.user_avatar : ''" />
								</uni-image>
							</uni-view>
						</uni-view>
						<uni-view class="det">
							<uni-text class="name">
								<span>{{ item.user_info.nick_name }}</span>
							</uni-text>
							<uni-view class="content is-text">{{ item.chat_content }}</uni-view>
						</uni-view>
					</uni-view>
				</view>
			</view>
		</view>
		<view class="box-2" v-if="friend_id">
			<view class="flex_col">
				<view class="flex_grow">
					<input type="text" class="content" v-model="content" placeholder="请输入聊天内容"
					 placeholder-style="color:#DDD;" :cursor-spacing="6" 
					 @keyup.enter="sendMsg"
					 :confirm-type="'send'"
					@confirm="sendMsg"
					  />
				</view>
				<button class="send" @tap="sendMsg">发送</button>
			</view>
		</view>
	</view>
</template>

<script>
import { getUserInfo } from '@/api/UserServer.js';
import { fnFormatDate } from '@/utils/CommonUtil.js';
export default {
	data() {
		return {
			// 加载历史消息
			ajax: {
				page: 1, //页码
				search_month: '', // 历史记录的月份
				limit: 20, //每页数量
				flag: true, // 请求开关
				loading: true, // 加载中
				loadText: '正在获取消息'
			},

			// 登录会员
			login_user: {},
			// 指定会员
			friend_id: 0,
			// 指定会员的信息
			friend_info: {},
			// 消息记录
			messages_list: [],
			// 发送的文本
			content: '',
			// socket
			socket: {},
			// 登录会员的Token
			login_token: '',
			// 录音组件
			Recorder: uni.getRecorderManager(),
			// 音频播放
			Audio: uni.createInnerAudioContext()
		};
	},
	mounted() {
		this.$nextTick(() => {
			this.getHistoryMsg();
		});
	},
	onPageScroll(e) {
		if (e.scrollTop < 5) {
			this.getHistoryMsg();
		}
	},
	onLoad(options) {
		console.log('---onLoad---');
		// 验证是否已登录
		// console.log(options)

		// 是否设置了聊天会员Id
		if (!options.friend_id) {
			uni.showToast({
				title: '请指定聊天会员',
				icon: 'none'
			});
			setTimeout(() => {
				uni.navigateBack();
			}, 2000);
			return;
		}

		// 登录会员的Token
		this.login_token = uni.getStorageSync('TOKEN') || '';

		// 登录会员的基本信息
		this.login_user = this.$store.getters['user/getLoginUserInfoData'];
		console.log('---this.login_user---');
		console.log(this.login_user);
		// 聊天好友的Id
		this.friend_id = options.friend_id;

		// 获取指定会员的基本信息
		this.getUserInfoByAsync(this.friend_id);
	},
	onReady() {
		console.log('---onReady---');
		//自定义返回按钮 因为原生的返回按钮不可阻止默认事件
		// #ifdef H5
		const icon = document.getElementsByClassName('uni-page-head-btn')[0];
		icon.style.display = 'none';
		// #endif

		//  开始监听 Socket
		this.monitorSocket();

		//录音开始事件
		this.Recorder.onStart(e => {
			this.beginVoice();
		});
		//录音结束事件
		this.Recorder.onStop(res => {
			clearInterval(this.voiceInterval);
			this.handleRecorder(res);
		});

		//音频停止事件
		this.Audio.onStop(e => {
			this.closeAnmition();
		});

		//音频播放结束事件
		this.Audio.onEnded(e => {
			this.closeAnmition();
		});
	},
	methods: {
		// 时间戳格式化
		getDateByTime(created_time) {
			return fnFormatDate(created_time);
		},
		// 获取好友信息
		async getUserInfoByAsync(user_id) {
			let friend = new Promise((resolve, reject) => {
				getUserInfo(user_id)
					.then(friend => {
						this.friend_info = friend.data.user_info;

						// 导航栏设置为好友的昵称
						uni.setNavigationBarTitle({
							title: this.friend_info.nick_name
						});

						resolve(friend);
					})
					.catch(err => {
						console.log(err);
					});
			});
		},
		//拼接消息 处理滚动
		async joinData() {
			if (!this.ajax.loading) {
				//如果没有获取数据 即loading为false时，return 避免用户重复上拉触发加载
				return;
			}
			this.ajax.loading = false;
			const data = await this.getPrivateChatRecords();
			this.ajax.loading = true;
		},
		// 发送信息
		sendMsg(data) {
			// console.log(data);
			const params = {
				content: this.content,
				friend_id: this.friend_id,
				chat_type: 0
			};
			if (data) {
				if (data.contentType == 2) {
					//说明是发送语音
					params.content = data.content;
					params.contentType = data.contentType;
					params.contentDuration = data.contentDuration;
					params.anmitionPlay = false;
				} else if (data.contentType == 1) {
					//发送图片
					params.content = data.content;
					params.contentType = data.contentType;
				}
			} else if (!this.content) {
				uni.showToast({
					title: '请输入有效的内容',
					icon: 'none'
				});
				return;
			}

			uni.showLoading({
				title: '正在发送'
			});

			// 私聊：发送文本消息
			this.socket.emit('private-chat', params, console.log);

			this.$nextTick(() => {
				this.content = '';
				// #ifdef MP-WEIXIN
				if (params.chat_type == 1) {
					uni.pageScrollTo({
						scrollTop: 99999,
						duration: 0 //小程序如果有滚动效果 input的焦点也会随着页面滚动...
					});
				} else {
					setTimeout(() => {
						uni.pageScrollTo({
							scrollTop: 99999,
							duration: 0 //小程序如果有滚动效果 input的焦点也会随着页面滚动...
						});
					}, 150);
				}
				// #endif

				// #ifndef MP-WEIXIN
				uni.pageScrollTo({
					scrollTop: 99999,
					duration: 100
				});
				// #endif

				if (this.showFunBtn) {
					this.showFunBtn = false;
				}

				// #ifdef MP-WEIXIN
				if (params.chat_type == 1) {
					this.mpInputMargin = true;
				}
				// #endif
				//h5浏览器并没有很好的办法控制键盘一直处于唤起状态 而且会有样式性的问题
				// #ifdef H5
				uni.hideKeyboard();
				// #endif
			});

			uni.hideLoading();
		},
		// 自动加载：监听socket
		monitorSocket() {
			this.socket = this.$socket;
			// 如果socket未生效，那么不做监听
			if(!this.socket){
				return false;
			}
			// 监听：私聊发送事件，服务端返回数据
			this.socket.on('private-chat', (data, status, msg) => {
				console.log('---获取私聊消息推送事件---');
				console.log(data);
				console.log(status);
				console.log(msg);

				// demo  test
				setTimeout(() => {
					uni.hideLoading();
					// 数据追加到当前消息列表
					if (status == 1) {
						data.anmitionPlay = false; //标识音频是否在播放
						this.messages_list.push(data);
					} else {
						// 提醒发送者，消息发送失败
						uni.showToast({
							title: msg,
							icon: 'none'
						});
						return;
					}
					this.$nextTick(() => {
						// 清空内容框中的内容
						this.content = '';
						uni.pageScrollTo({
							scrollTop: 999999, // 设置一个超大值，以保证滚动条滚动到底部
							duration: 0
						});
					});
				}, 1500);
			});

			// 监听：获取私聊历史聊天记录
			this.socket.on('private-chat-records', (lists, status, msg) => {
				console.log('---获取私聊历史聊天记录---');
				console.log(lists);
				console.log(status);
				console.log(msg);

				this.hideLoadTips();
				this.ajax.flag = false;
				// 获取待滚动元素选择器，解决插入数据后，滚动条定位时使用
				let selector = '';

				if (this.ajax.page > 1) {
					// 非第一页，则取历史消息数据的第一条信息元素
					if (lists.data.length > 0) selector = `#msg-${this.messages_list[0].record_id}`;
					// 将获取到的消息数据合并到消息数组中
					this.messages_list = [...lists.data, ...this.messages_list];
				} else {
					// 第一页，则取当前消息数据的最后一条信息元素
					if (lists.data.length > 0) selector = `#msg-${lists.data[lists.data.length - 1].record_id}`;
					// 将获取到的消息数据合并到消息数组中
					this.messages_list = lists.data;
				}
				// 数据挂载后执行，不懂的请自行阅读 Vue.js 文档对 Vue.nextTick 函数说明。
				this.$nextTick(() => {
					// 设置当前滚动的位置
					if (selector) this.setPageScrollTo(selector);

					this.hideLoadTips(true);

					// 设置下一次请求页码
					this.ajax.page = parseInt(lists.current_page) + parseInt(1);
					// 设置月份
					this.ajax.month_table = lists.month_table;

					if (lists.data.length < this.ajax.limit) {
						// 当前消息数据条数小于请求要求条数时，则无更多消息，不再允许请求。
						// 可在此处编写无更多消息数据时的逻辑
					} else {
						// 延迟 200ms ，以保证设置窗口滚动已完成
						setTimeout(() => {
							this.ajax.flag = true;
						}, 200);
					}
				});
			});
		},
		// 获取历史聊天记录
		getPrivateChatRecords() {
			if (!this.ajax.flag) {
				return; //
			}
			// 如果socket未生效，那么不做监听
			if(!this.socket){
				return false;
			}
			this.hideLoadTips();
			this.ajax.flag = false;
			// 私聊：发送文本消息
			this.socket.emit(
				'get-private-chat-records',
				{
					page: this.ajax.page,
					friend_id: this.friend_id,
					month_table: this.ajax.month_table
				},
				console.log
			);
			// 延迟 200ms ，以保证设置窗口滚动已完成
			setTimeout(() => {
				this.ajax.flag = true;
			}, 200);
		},
		// 获取历史消息
		getHistoryMsg() {
			console.log('---开始获取历史聊天记录---');
			this.getPrivateChatRecords();
		},
		// 设置页面滚动位置
		setPageScrollTo(selector) {
			let view = uni
				.createSelectorQuery()
				.in(this)
				.select(selector);
			view.boundingClientRect(res => {
				uni.pageScrollTo({
					scrollTop: res.top - 30, // -30 为多显示出大半个消息的高度，示意上面还有信息。
					duration: 0
				});
			}).exec();
		},
		// 隐藏加载提示
		hideLoadTips(flag) {
			if (flag) {
				this.ajax.loadText = '消息获取成功';
				setTimeout(() => {
					this.ajax.loading = false;
				}, 300);
			} else {
				this.ajax.loading = true;
				this.ajax.loadText = '正在获取消息';
			}
		}
	}
};
</script>

<style lang="scss">
@import 'chat2.scss';
page {
	background-color: #f3f3f3;
	font-size: 28rpx;
}

/* 加载数据提示 */
.tips {
	position: fixed;
	left: 0;
	top: var(--window-top);
	width: 100%;
	z-index: 9;
	background-color: rgba(0, 0, 0, 0.15);
	height: 72rpx;
	line-height: 72rpx;
	transform: translateY(-80rpx);
	transition: transform 0.3s ease-in-out 0s;

	&.show {
		transform: translateY(0);
	}
}

.box-1 {
	width: 100%;
	height: auto;
	padding-bottom: 100rpx;
	box-sizing: content-box;

	/* 兼容iPhoneX */
	margin-bottom: 0;
	margin-bottom: constant(safe-area-inset-bottom);
	margin-bottom: env(safe-area-inset-bottom);
}
.box-2 {
	position: fixed;
	left: 0;
	width: 100%;
	bottom: 0;
	height: auto;
	z-index: 2;
	border-top: #e5e5e5 solid 1px;
	box-sizing: content-box;
	background-color: #f3f3f3;

	/* 兼容iPhoneX */
	padding-bottom: 0;
	padding-bottom: constant(safe-area-inset-bottom);
	padding-bottom: env(safe-area-inset-bottom);

	> view {
		padding: 0 20rpx;
		height: 100rpx;
	}

	.content {
		background-color: #fff;
		height: 64rpx;
		padding: 0 20rpx;
		border-radius: 32rpx;
		font-size: 28rpx;
	}

	.send {
		background-color: #42b983;
		color: #fff;
		height: 64rpx;
		margin-left: 20rpx;
		border-radius: 32rpx;
		padding: 0;
		width: 120rpx;
		line-height: 62rpx;

		&:active {
			background-color: #5fc496;
		}
	}
}

.talk-list {
	padding-bottom: 20rpx;

	/* 消息项，基础类 */
	.item {
		padding: 20rpx 20rpx 0 20rpx;
		align-items: flex-start;
		align-content: flex-start;
		color: #333;

		.pic {
			width: 92rpx;
			height: 92rpx;
			border-radius: 50%;
			border: #fff solid 1px;
		}

		.content {
			padding: 20rpx;
			border-radius: 4px;
			max-width: 500rpx;
			word-break: break-all;
			line-height: 52rpx;
			position: relative;
		}

		/* 收到的消息 */
		&.pull {
			clear: both;
			.content {
				margin-left: 32rpx;
				background-color: #fff;

				&::after {
					content: '';
					display: block;
					width: 0;
					height: 0;
					border-top: 16rpx solid transparent;
					border-bottom: 16rpx solid transparent;
					border-right: 20rpx solid #fff;
					position: absolute;
					top: 30rpx;
					left: -18rpx;
				}
			}
		}

		/* 发出的消息 */
		&.push {
			/* 主轴为水平方向，起点在右端。使不修改DOM结构，也能改变元素排列顺序 */
			flex-direction: row-reverse;
			clear: both;

			.content {
				margin-right: 32rpx;
				background-color: #a0e959;

				&::after {
					content: '';
					display: block;
					width: 0;
					height: 0;
					border-top: 16rpx solid transparent;
					border-bottom: 16rpx solid transparent;
					border-left: 20rpx solid #a0e959;
					position: absolute;
					top: 30rpx;
					right: -18rpx;
				}
			}
		}
	}
}

























/**
 * 聊天样式
 *
 * 参考地址：http://uni-chat.cool-admin.com/#/pages/chat/session
 */
@import 'index.c4fe38c7.css';

.page-session {
    display: -webkit-box;
    display: -webkit-flex;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    flex-direction: column;
    height: 100%;
    padding-bottom: env(safe-area-inset-bottom);
    box-sizing: border-box;
    background-color: #fff;
}
uni-view {
    display: block;
}
.chat-message-item{
    font-size: 12px;
    padding: 9px;
}
.chat-message-item .date {
    display: block;
    text-align: center;
    margin: 9px 0;
    font-size: 11px;
}
.chat-message-item .main {
    display: -webkit-box;
    display: -webkit-flex;
    display: flex;
}
.chat-message-item .main .avatar {
    -webkit-flex-shrink: 0;
    flex-shrink: 0;
    height: 38px;
}
.chat-message-item.is-left .det {
    margin-left: 9px;
    -webkit-box-align: start;
    -webkit-align-items: flex-start;
    align-items: flex-start;
}
.chat-message-item .main .det {
    display: -webkit-box;
    display: -webkit-flex;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    flex-direction: column;
    max-width: 60%;
}
.cl-avatar--circle {
    border-radius: 100%;
}
.cl-avatar {
    display: -webkit-box;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -webkit-align-items: center;
    align-items: center;
    background-color: #c0c4cc;
    font-size: 13px;
    color: #fff;
    overflow: hidden;
    box-sizing: border-box;
}
.cl-avatar__target {
    height: 100%;
    width: 100%;
}
uni-image {
    width: 320px;
    height: 240px;
    display: inline-block;
    overflow: hidden;
    position: relative;
}
uni-image>div, uni-image>img {
    width: 100%;
    height: 100%;
}
uni-image>img {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
}
.chat-message-item .main .det .name {
    margin-bottom: 4px;
}
.chat-message-item.is-left .det .content.is-text, .chat-message-item.is-left .det .content.is-voice {
    border-top-left-radius: 0;
    background-color: #f6f7f9;
}
.chat-message-item .main .det .content.is-text, .chat-message-item .main .det .content.is-voice {
    padding: 9px;
    line-height: 19px;
    letter-spacing: 1px;
}
.chat-message-item .main .det .content {
    display: inline-block;
    border-radius: 7px;
    box-sizing: border-box;
}

.chat-message-item.is-right .det {
    margin-right: 9px;
    -webkit-box-align: end;
    -webkit-align-items: flex-end;
    align-items: flex-end;
}
.chat-message-item .main .det .content.is-image uni-image {
    max-height: 144px;
    max-width: 144px;
    border-radius: 7px;
}
uni-image {
    width: 320px;
    height: 240px;
    display: inline-block;
    overflow: hidden;
    position: relative;
}
.chat-message-item.is-right .det .content.is-text, .chat-message-item.is-right .det .content.is-voice {
    border-top-right-radius: 0;
    background-color: #ffc100;
}
.chat-message-item .main .det .content.is-text, .chat-message-item .main .det .content.is-voice {
    padding: 9px;
    line-height: 19px;
    letter-spacing: 1px;
}
.chat-message-item .main .det .content {
    display: inline-block;
    border-radius: 7px;
    box-sizing: border-box;
}
.chat-message-item.is-right .main {
    -webkit-box-orient: horizontal;
    -webkit-box-direction: reverse;
    -webkit-flex-direction: row-reverse;
    flex-direction: row-reverse;
}

</style>

<template>
	<view>
		<view class="tips color_fff size_12 align_c" :class="{ show: ajax.loading }" @tap="getHistoryMsg">{{ ajax.loadText }}</view>
		<view class="box-1" id="list-box">
			<view class="talk-list">
				<view v-for="(item, index) in talkList" :key="index" :id="`msg-${item.record_id}`">
					<view class="item flex_col" :class="item.is_read == 1 ? 'push' : 'pull'">
						<image :src="item.user.user_avatar" mode="aspectFill" class="pic"></image>
						<view class="content">{{ item.chat_content }}</view>
					</view>
				</view>
			</view>
		</view>
		<view class="box-2">
			<view class="flex_col">
				<view class="flex_grow">
					<input type="text" class="content" v-model="content" placeholder="请输入聊天内容" placeholder-style="color:#DDD;" :cursor-spacing="6" />
				</view>
				<button class="send" @tap="sendMsg">发送</button>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			ajax: {
				rows: 20, //每页数量
				page: 1, //页码
				flag: true, // 请求开关
				loading: true, // 加载中
				loadText: '正在获取消息'
			},
			
			
			
			// 消息记录
			talkList: [],
			// 发送的文本
			content: '',
			// socket
			socket : {},
			// 登录会员的Token
			login_token: '',
			// 录音组件
			Recorder: uni.getRecorderManager(),
			// 音频播放
			Audio: uni.createInnerAudioContext(),
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
		// 验证是否已登录
		options.friend_id = 1;
		
		// 登录会员的Token
		this.login_token = uni.getStorageSync('TOKEN') || '';
		

		console.log(options);
		// 登录会员的基本信息
		this.login_user = this.$store.getters['user/getLoginUserInfoData'];
		// 聊天好友的Id
		this.friend_id = options.friend_id;
		this.friend_userinfo = {
			user_id: 1,
			nick_name: '昵称1',
			user_avatar: ''
		};
		/**
		if (options.friend_uuid) this.friend_uuid = options.friend_uuid;
		// 获取好友列表
		this.firends = this.$cache.getUserFriends();
		// 获取好友的基本信息
		const friend_userinfo = this.firends.filter(item => item.friend_id == this.friend_id)[0];
		this.friend_userinfo = {
			user_id: friend_userinfo.friend.user_id,
			nick_name: friend_userinfo.initials_name,
			user_avatar: friend_userinfo.friend.avatar.file_path
		};
		**/
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
		// 发送信息
		sendMsg(data) {
			console.log(data);
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
			
			
			// demo  test
			setTimeout(() => {
				uni.hideLoading();
			
				// 将当前发送信息 添加到消息列表。
				let data = {
					id: new Date().getTime(),
					content: this.content,
					type: 1,
					pic: '/static/logo.png'
				};
				this.talkList.push(data);
			
				this.$nextTick(() => {
					// 清空内容框中的内容
					this.content = '';
					uni.pageScrollTo({
						scrollTop: 999999, // 设置一个超大值，以保证滚动条滚动到底部
						duration: 0
					});
				});
			}, 1500);
			
			
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
		},
		// 自动加载：监听socket
		monitorSocket() {
			this.socket = this.$socket;
			
			// 监听私聊事件，服务端返回数据
			this.socket.on('private-chat', (data, status, msg) => {
				console.log(data);
				console.log(status);
				console.log(msg);
				
				// 数据追加到当前消息列表
				data.anmitionPlay = false; //标识音频是否在播放
				if(status == 1){
					this.talkList.push(data);
				}else{
					// 提醒发送者，消息发送失败
				}
				console.log(this.talkList);
			});
		},
		// 获取历史消息
		getHistoryMsg() {
			if (!this.ajax.flag) {
				return; //
			}

			// 此处用到 ES7 的 async/await 知识，为使代码更加优美。不懂的请自行学习。
			let get = async () => {
				this.hideLoadTips();
				this.ajax.flag = false;
				let data = await this.joinHistoryMsg();

				console.log('----- 模拟数据格式，供参考 -----');
				console.log(data); // 查看请求返回的数据结构

				// 获取待滚动元素选择器，解决插入数据后，滚动条定位时使用
				let selector = '';

				if (this.ajax.page > 1) {
					// 非第一页，则取历史消息数据的第一条信息元素
					selector = `#msg-${this.talkList[0].id}`;
				} else {
					// 第一页，则取当前消息数据的最后一条信息元素
					selector = `#msg-${data[data.length - 1].id}`;
				}

				// 将获取到的消息数据合并到消息数组中
				this.talkList = [...data, ...this.talkList];

				// 数据挂载后执行，不懂的请自行阅读 Vue.js 文档对 Vue.nextTick 函数说明。
				this.$nextTick(() => {
					// 设置当前滚动的位置
					this.setPageScrollTo(selector);

					this.hideLoadTips(true);

					if (data.length < this.ajax.rows) {
						// 当前消息数据条数小于请求要求条数时，则无更多消息，不再允许请求。
						// 可在此处编写无更多消息数据时的逻辑
					} else {
						this.ajax.page++;

						// 延迟 200ms ，以保证设置窗口滚动已完成
						setTimeout(() => {
							this.ajax.flag = true;
						}, 200);
					}
				});
			};
			get();
		},
		// 拼接历史记录消息，正式项目可替换为请求历史记录接口
		joinHistoryMsg() {
			let join = () => {
				let arr = [];

				//通过当前页码及页数，模拟数据内容
				let startIndex = (this.ajax.page - 1) * this.ajax.rows;
				let endIndex = startIndex + this.ajax.rows;
				for (let i = startIndex; i < endIndex; i++) {
					arr.push({
						record_id: i, // 消息的ID
						chat_content: `这是历史记录的第${i + 1}条消息`, // 消息内容
						is_read: Math.random() > 0.5 ? 1 : 0, // 此为消息类别，设 1 为发出去的消息，0 为收到对方的消息,
						user: {
							'user_avatar' : '/static/logo.png' // 头像
						}
					});
				}

				/*
						颠倒数组中元素的顺序。将最新的数据排在本次接口返回数据的最后面。
						后端接口按 消息的时间降序查找出当前页的数据后，再将本页数据按消息时间降序排序返回。
						这是数据的重点，因为页面滚动条和上拉加载历史的问题。
					 */
				arr.reverse();

				return arr;
			};

			// 此处用到 ES6 的 Promise 知识，不懂的请自行学习。
			return new Promise((done, fail) => {
				// 无数据请求接口，由 setTimeout 模拟，正式项目替换为 ajax 即可。
				setTimeout(() => {
					let data = join();
					done(data);
				}, 1500);
			});
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
		},
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
</style>

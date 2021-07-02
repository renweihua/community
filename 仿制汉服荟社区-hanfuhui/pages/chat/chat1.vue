<template>
	<view class="content">
		<view class="content-box" @touchstart="touchstart" id="content-box" :class="{ 'content-showfn': showFunBtn }">
			<!-- 背景图- 定位方式 -->
			require('@/static/image/Ran.jpg')
			<!-- <image class="content-box-bg" :src="login_user.avatar.file_path" :style="{ height: imgHeight }"></image> -->
			<image class="content-box-bg" src="/static/image/Ran.jpg" :style="{ height: imgHeight }"></image>
			
			<view class="content-box-loading" v-if="!loading"><u-loading mode="flower"></u-loading></view>
			<view class="message" v-for="(item, index) in messageList" :key="index" :id="`msg-${item.record_id}`">
				<view class="message-item " :class="(login_user.user_id == item.friend_id) ? 'left' : 'right'">
					<!-- 登录会员是发送者 -->
					<image v-if="login_user.user_id == item.friend_id" class="img" :src="item.user.avatar.file_path" mode="" @tap="linkToUserCard(item.user.user_uuid)"></image>
					<!-- 登录会员是接收者 -->
					<image v-else class="img" :src="item.user.avatar.file_path" mode="" @tap="linkToUserCard(item.friend.user_uuid)"></image>
					<!-- contentType = 0 文本 -->
					<view class="content" v-if="item.chat_type == 0">
					{{ item.chat_content }}
					</view>
					<!-- contentType = 1 图片 -->
					<view class="content contentType1" v-if="item.chat_type == 1" @tap="viewImg([item.chat_content])">
						<image :src="item.chat_content" class="img" mode="widthFix"></image>
					</view>
					<!-- contentType = 2 语音 -->
					<view
						class="content contentType2"
						:class="[{ 'content-type-right': item.isItMe }]"
						v-if="item.chat_type == 2"
						@tap="handleAudio(item)"
						hover-class="contentType2-hover-class"
						:style="{ width: `${130 + item.contentDuration * 2}rpx` }"
					>
						<view
							class="voice_icon"
							:class="[
								{ voice_icon_right: item.isItMe },
								{ voice_icon_left: !item.isItMe },
								{ voice_icon_right_an: item.anmitionPlay && item.isItMe },
								{ voice_icon_left_an: item.anmitionPlay && !item.isItMe }
							]"
						></view>
						<view class="">{{ item.contentDuration }}''</view>
					</view>
				</view>
			</view>
		</view>

		<!-- 底部聊天输入框 -->
		<view class="input-box" :class="{ 'input-box-mpInputMargin': mpInputMargin }">
			<view class="input-box-flex">
				<!-- #ifndef H5 -->
				<image v-if="chatType === 'voice'" class="icon_img" :src="require('@/static/voice.png')" @click="switchChatType('keyboard')"></image>
				<image v-if="chatType === 'keyboard'" class="icon_img" :src="require('@/static/keyboard.png')" @click="switchChatType('voice')"></image>
				<!-- #endif -->
				<view class="input-box-flex-grow">
					<input
						v-if="chatType === 'voice'"
						type="text"
						class="content"
						id="input"
						v-model="formData.content"
						:hold-keyboard="true"
						:confirm-type="'send'"
						:confirm-hold="true"
						placeholder-style="color:#DDDDDD;"
						:cursor-spacing="10"
						@confirm="sendMsg(null)"
					/>
					<view
						class="voice_title"
						v-if="chatType === 'keyboard'"
						:style="{ background: recording ? '#c7c6c6' : '#FFFFFF' }"
						@touchstart.stop.prevent="startVoice"
						@touchmove.stop.prevent="moveVoice"
						@touchend.stop="endVoice"
						@touchcancel.stop="cancelVoice"
					>
						{{ voiceTitle }}
					</view>
				</view>

				<!-- 功能性按钮 -->
				<image class=" icon_btn_add" :src="require('@/static/add.png')" @tap="switchFun"></image>

				<!-- #ifdef H5 -->
				<!-- #endif -->
				
				<button class="btn" type="primary" size="mini" @touchend.prevent="sendMsg(null)">发送</button>
			</view>

			<view class="fun-box" :class="{ 'show-fun-box': showFunBtn }">
				<u-grid :col="4" hover-class="contentType2-hover-class" :border="false" @click="clickGrid">
					<u-grid-item v-for="(item, index) in funList" :index="index" :key="index" bg-color="#eaeaea">
						<i-icon size="28" :name="item.icon" :color="item.icon"></i-icon>
						<view class="grid-text">{{ item.title }}</view>
					</u-grid-item>
				</u-grid>
			</view>
		</view>

		<!-- //语音动画 -->
		<view class="voice_an" v-if="recording">
			<view class="voice_an_icon">
				<view id="one" class="wave"></view>
				<view id="two" class="wave"></view>
				<view id="three" class="wave"></view>
				<view id="four" class="wave"></view>
				<view id="five" class="wave"></view>
				<view id="six" class="wave"></view>
				<view id="seven" class="wave"></view>
			</view>
			<view class="text">{{ voiceIconText }}</view>
		</view>
	</view>
</template>

<script>
	import { get } from '@/api/CommonServer.js';
export default {
	data() {
		return {
			formData: {
				content: '',
				limit: 15,
				index: 1
			},
			loading: true, //标识是否正在获取数据
			imgHeight: '1000px',
			mpInputMargin: false, //适配微信小程序 底部输入框高度被顶起的问题
			chatType: 'voice', // 图标类型 'voice'语音 'keyboard'键盘
			voiceTitle: '按住 说话',
			Recorder: uni.getRecorderManager(),
			Audio: uni.createInnerAudioContext(),
			recording: false, //标识是否正在录音
			isStopVoice: false, //加锁 防止点击过快引起的当录音正在准备(还没有开始录音)的时候,却调用了stop方法但并不能阻止录音的问题
			voiceInterval: null,
			voiceTime: 0, //总共录音时长
			canSend: true, //是否可以发送
			PointY: 0, //坐标位置
			voiceIconText: '正在录音...',
			showFunBtn: false, //是否展示功能型按钮
			AudioExam: null, //正在播放音频的实例
			funList: [{ icon: 'photo-fill', title: '照片', uploadType: ['album'] }, { icon: 'camera-fill', title: '拍摄', uploadType: ['camera'] }],
			
			
			
			// 定义
			socket : {},
			friend_id : 0, // 好友Id：与会员Id，正在聊天……
			friend_uuid : '', // 返回上一页，会员详情
			friend_userinfo:{}, // 好友的基本信息
			login_user : {}, // 登录会员的基本信息
			page : 1, // 历史聊天记录的页码
			month_table : '', // 历史聊天记录的月份表
			messageList: [], // 历史聊天记录的信息
		};
	},
	onLoad(options) {
		// 验证是否已登录
		this.$cache.isLogin();
		
		console.log(options);
		// 登录会员的基本信息
		this.login_user = this.$cache.getLoginUser();
		// 聊天好友的Id
		this.friend_id = options.friend_id;
		if(options.friend_uuid) this.friend_uuid = options.friend_uuid;
		// 获取好友列表
		this.firends = this.$cache.getUserFriends();
		// 获取好友的基本信息
		const friend_userinfo = this.firends.filter(item => item.friend_id == this.friend_id)[0];
		this.friend_userinfo = {
			user_id: friend_userinfo.friend.user_id,
			nick_name: friend_userinfo.initials_name,
			user_avatar: friend_userinfo.friend.avatar.file_path
		};
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
	onReady() {
		//自定义返回按钮 因为原生的返回按钮不可阻止默认事件
		// #ifdef H5
		const icon = document.getElementsByClassName('uni-page-head-btn')[0];
		icon.style.display = 'none';
		// #endif

		// 导航栏设置为好友的昵称
		uni.setNavigationBarTitle({
			title: this.friend_userinfo.nick_name
		});
		this.joinData();
		
		uni.getSystemInfo({
			success: res => {
				this.imgHeight = res.windowHeight + 'px';
			}
		});

		uni.onKeyboardHeightChange(res => {
			if (res.height == 0) {
				// #ifdef MP-WEIXIN
				this.mpInputMargin = false;
				// #endif
			} else {
				this.showFunBtn = false;
			}
		});
	},
	methods: {
		// 监听返回按钮
		onNavigationBarButtonTap({ index }) {
			// 如果存在uuid，就返回详情页；否则返回会员列表
			if(this.friend_uuid){
				this.$u.route({
					url: 'pages/user/visit-card',
					params: { user_uuid: this.friend_uuid }
				});
			}else{
				this.$u.route({
					url: 'pages/friends/friends',
				});
			}
		},
		// 自动加载：监听socket
		monitorSocket() {
			this.socket = this.$socket;			
			// console.log(this.socket);
			var token = this.$cache.getUserToken();
			
			// 监听私聊事件，服务端返回数据
			this.socket.on('private-chat', (data, status, msg) => {
				console.log(data);
				console.log(status);
				console.log(msg);
				// 数据追加到当前消息列表
				data.anmitionPlay = false; //标识音频是否在播放
				if(status == 1) this.messageList.push(data);
				else{
					// 提醒发送者，消息发送失败
				}
				console.log(this.messageList);
			});
		},
		// 获取历史聊天记录
		getPrivateChatRecords(){
			return new Promise((resolve, reject) => {
				get({
					url:'private-chat-records',
					data : {
						page : this.page,
						friend_id : this.friend_id,
						month_table : this.month_table,
					}
				}).then((res)=>{
					console.log(res);
					// 数据
					// this.messageList = res.data.current_page == 1 ? res.data.data : this.$wui.wListrendering(this.messageList, res.data.data, true);
					// console.log('getPrivateChatRecords：' + this.messageList);
					// 请求页码
					this.page = parseInt(res.data.current_page) + parseInt(1);
					// 设置月份
					this.month_table = res.data.month_table;
					
					resolve(res.data);
				}).catch(err => {
					console.log(err);
				});
			});
		},
		//发送消息
		sendMsg(data) {
			console.log(data);
			const params = {
				content: this.formData.content,
				friend_id: this.friend_id,
				chat_type: 0
			};
			console.log(params);

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
			} else if (this.formData.content) {
				//验证输入框书否为空字符传
				return;
			}

			// 私聊：发送文本消息
			this.socket.emit('private-chat', params, console.log);
			
			this.$nextTick(() => {
				this.formData.content = '';
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
		//拼接消息 处理滚动
		async joinData() {
			if (!this.loading) {
				//如果没有获取数据 即loading为false时，return 避免用户重复上拉触发加载
				return;
			}
			this.loading = false;
			const data = await this.getPrivateChatRecords();
			console.log(data);
			console.log('this.messageList： ');
			console.log(this.messageList);
			let record_id = this.messageList.length > 0 ? this.messageList[0].record_id : 0;
			// console.log(this.messageList[0].record_id);
			// console.log(data.data[data.data.length - 1]);
			//获取节点信息
			const { index } = this.formData;
			const sel = `#msg-${index > 1 ? record_id : (data.data.length > 0 ? data.data[data.data.length - 1].record_id : 0)}`;
			console.log('sel：' + sel);
			this.messageList = [...data.data, ...this.messageList];
			
			this.loading = true;
			//填充数据后，视图会自动滚动到最上面一层然后瞬间再跳回bindScroll的指定位置 ---体验不是很好，后期优化
			this.$nextTick(() => {
				this.bindScroll(sel);
				//如果还有数据
				console.log('this.page：' + this.page);
				console.log('data.last_page：' + data.last_page);
				if (this.page <= data.last_page) {
					this.formData.index++;
					setTimeout(() => {
						this.loading = true;
					}, 200);
				}
			});
		},
		//处理滚动
		bindScroll(sel, duration = 0) {
			const query = uni.createSelectorQuery().in(this);
			query
				.select(sel)
				.boundingClientRect(data => {
					uni.pageScrollTo({
						scrollTop: data && data.top - 40,
						duration
					});
				})
				.exec();
		},
		//切换语音或者键盘方式
		switchChatType(type) {
			this.chatType = type;
			this.showFunBtn = false;
		},
		//切换功能性按钮
		switchFun() {
			this.chatType = 'keyboard';
			this.showFunBtn = !this.showFunBtn;
			uni.hideKeyboard();
		},
		//用户触摸屏幕的时候隐藏键盘
		touchstart() {
			uni.hideKeyboard();
		},
		// userid 用户id
		linkToUserCard(user_id) {
			this.$u.route({
				url: 'pages/user/visit-card',
				params: {
					user_id
				}
			});
		},
		//准备开始录音
		startVoice(e) {
			if (!this.Audio.paused) {
				//如果音频正在播放 先暂停。
				this.stopAudio(this.AudioExam);
			}
			this.recording = true;
			this.isStopVoice = false;
			this.canSend = true;
			this.voiceIconText = '正在录音...';
			this.PointY = e.touches[0].clientY;
			this.Recorder.start({
				format: 'mp3'
			});
		},
		//录音已经开始
		beginVoice() {
			if (this.isStopVoice) {
				this.Recorder.stop();
				return;
			}
			this.voiceTitle = '松开 结束';
			this.voiceInterval = setInterval(() => {
				this.voiceTime++;
			}, 1000);
		},
		//move 正在录音中
		moveVoice(e) {
			const PointY = e.touches[0].clientY;
			const slideY = this.PointY - PointY;
			if (slideY > uni.upx2px(120)) {
				this.canSend = false;
				this.voiceIconText = '松开手指 取消发送 ';
			} else if (slideY > uni.upx2px(60)) {
				this.canSend = true;
				this.voiceIconText = '手指上滑 取消发送 ';
			} else {
				this.voiceIconText = '正在录音... ';
			}
		},
		//结束录音
		endVoice() {
			this.isStopVoice = true; //加锁 确保已经结束录音并不会录制
			this.Recorder.stop();
			this.voiceTitle = '按住 说话';
		},
		//录音被打断
		cancelVoice(e) {
			this.voiceTime = 0;
			this.voiceTitle = '按住 说话';
			this.canSend = false;
			this.Recorder.stop();
		},
		//处理录音文件
		handleRecorder({ tempFilePath, duration }) {
			let contentDuration;
			// #ifdef MP-WEIXIN
			this.voiceTime = 0;
			if (duration < 600) {
				this.voiceIconText = '说话时间过短';
				setTimeout(() => {
					this.recording = false;
				}, 200);
				return;
			}
			contentDuration = duration / 1000;
			// #endif

			// #ifdef APP-PLUS
			contentDuration = this.voiceTime + 1;
			this.voiceTime = 0;
			if (contentDuration <= 0) {
				this.voiceIconText = '说话时间过短';
				setTimeout(() => {
					this.recording = false;
				}, 200);
				return;
			}
			// #endif

			this.recording = false;
			const params = {
				contentType: 2,
				content: tempFilePath,
				contentDuration: Math.ceil(contentDuration)
			};
			this.canSend && this.sendMsg(params);
		},
		//控制播放还是暂停音频文件
		handleAudio(item) {
			this.AudioExam = item;
			this.Audio.paused ? this.playAudio(item) : this.stopAudio(item);
		},
		//播放音频
		playAudio(item) {
			console.log('playAudio');
			return;
			this.Audio.src = item.chat_content;
			this.Audio.record_id = item.record_id;
			this.Audio.play();
			item.anmitionPlay = true;
		},
		//停止音频
		stopAudio(item) {
			console.log('stopAudio');
			return;
			item.anmitionPlay = false;
			this.Audio.src = '';
			this.Audio.stop();
		},
		//关闭动画
		closeAnmition() {
			const record_id = this.Audio.record_id;
			const item = this.messageList.find(it => it.record_id == record_id);
			item.anmitionPlay = false;
		},
		//点击宫格时触发
		clickGrid(index) {
			if (index == 0) {
				this.chooseImage(['album']);
			} else if (index == 1) {
				this.chooseImage(['camera']);
			}
		},
		//发送图片
		chooseImage(sourceType) {
			uni.chooseImage({
				sourceType,
				sizeType: ['compressed'],
				success: res => {
					this.showFunBtn = false;
					for (let i = 0; i < res.tempFilePaths.length; i++) {
						const params = {
							contentType: 1,
							content: res.tempFilePaths[i]
						};
						this.sendMsg(params);
					}
				}
			});
		},
		//查看大图
		viewImg(imgList) {
			uni.previewImage({
				urls: imgList,
				// #ifndef MP-WEIXIN
				indicator: 'number'
				// #endif
			});
		}
	},
	onPageScroll(e) {
		if (e.scrollTop < 50) {
			this.joinData();
		}
	},
	onNavigationBarButtonTap({ index }) {
		if (index == 0) {
			//用户详情 设置
		} else if (index == 1) {
			//返回按钮
			this.$u.route({
				type: 'switchTab',
				url: 'pages/home/index'
			});
		}
	},
	//返回按钮事件
	onBackPress(e) {
		//以下内容对h5不生效
		//--所以如果用浏览器自带的返回按钮进行返回的时候页面不会重定向 正在寻找合适的解决方案
		this.$u.route({
			type: 'switchTab',
			url: 'pages/home/index'
		});
		return true;
	}
};
</script>

<style lang="scss" scoped>
@import './index.scss';
</style>

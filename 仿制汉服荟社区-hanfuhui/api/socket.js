// socket
import io from '@hyoga/uni-socket.io';
// 配置项
import config from '../config.js';

export default {
	init() {
		let login_token = uni.getStorageSync('TOKEN') || '';
		if(!login_token) return;
		var start_setInterval;

		const socket = io(config.ws_http, {
			query: {
				token: login_token
			},
			transports: ['websocket', 'polling'],
			timeout: 5000,
		});

		socket.on('connect', () => {
			console.log('连接成功');
			// ws连接已建立，此时可以进行socket.io的事件监听或者数据发送操作
			console.log('ws 已连接');
			// socket.io 唯一连接id，可以监控这个id实现点对点通讯
			const {
				id
			} = socket;

			console.log(id);

			socket.on(id, (message) => {
				// 收到服务器推送的消息，可以跟进自身业务进行操作
				console.log('ws 收到服务器消息：', message);
			});

			// // 后期开启 ping ……
			// start_setInterval = setInterval(function() {
			// 	var date = new Date();
			// 	var year = date.getFullYear();
			// 	var month = date.getMonth() + 1;
			// 	var day = date.getDate();
			// 	var hour = date.getHours();
			// 	var minute = date.getMinutes();
			// 	var second = date.getSeconds();
			// 	month = month < 10 ? ("0" + month) : month;
			// 	day = day < 10 ? ("0" + day) : day;
			// 	hour = hour < 10 ? ("0" + hour) : hour;
			// 	minute = minute < 10 ? ("0" + minute) : minute;
			// 	second = second < 10 ? ("0" + second) : second;
			// 	var Timer = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
			// 	console.log(Timer);
				
				
			// 	socket.emit('start-ping', '{"contnet": ' + Timer +'}', console.log);
			// }, 3000);
			
			// // 监听服务端：ping事件
			// socket.on('start-ping', (data, status) => {
			// 	console.log(data);
			// 	console.log(status);
			// });

			// 发送登录
			socket.emit('user-login', {"type":"login", "token": login_token}, console.log);

			// 监听服务端：登录事件
			socket.on('user-login', (data, status) => {
				console.log(data);
				console.log(status);
			});
			
			// 关于 token 的定时刷新（5分钟自动给服务端推送消息，然后监听服务端消息返回）
		});
		// 正在连接
		socket.on('connecting', d => {
			console.log('正在连接', d);
		});
		// 连接错误
		socket.on('connect_error', d => {
			console.log('连接失败', d);
		});
		// 连接超时
		socket.on('connect_timeout', d => {
			console.log('连接超时', d);
		});
		// 断开连接
		socket.on('disconnect', reason => {
			// 清除定时器
			window.clearInterval(start_setInterval);
			
			console.log('断开连接', reason);
		});
		// 重新连接
		socket.on('reconnect', attemptNumber => {
			console.log('成功重连', attemptNumber);
		});
		// 连接失败
		socket.on('reconnect_failed', () => {
			console.log('重连失败');
		});
		// 尝试重新连接
		socket.on('reconnect_attempt', () => {
			console.log('尝试重新重连');
		});
		// 错误发生，并且无法被其他事件类型所处理
		socket.on('error', (msg) => {
			console.log('ws error', msg);
			console.log('错误发生，并且无法被其他事件类型所处理', err);
		});


		// 加入聊天室
		socket.on('login', d => {
			console.log(`您已加入聊天室，当前共有 ${d.numUsers} 人`);
		});
		// 接受到新消息
		socket.on('new message', d => {
			console.log('new message', d);

		});
		// 有人加入聊天室
		socket.on('user joined', d => {
			console.log(`${d.username} 来了，当前共有 ${d.numUsers} 人`);

		});
		// 有人离开聊天室
		socket.on('user left', d => {
			console.log(`${d.username} 离开了，当前共有 ${d.numUsers} 人`);
		});

		return socket;
	}
}

<template>
	<view>
		<!-- 滚动内容区 -->
		<mescroll-uni :down="{use:false}" :up="{use:false}">
			<!-- 签到信息 -->
			<view class="bgwhite mb18r fcenter ptb28r">
				<view class="f32r c555 mb18r">今日第 <text class="f36r ctheme mlr18r">{{signinInfoData.order}}</text> 个签到</view>
				<view class="f28r cgray">距明日抢签到还有 <text class="ml8r">{{remaiTime}}</text></view>
				<button class="btn-sub w44v mtb28r" hover-class="btn-hover" :disabled="signinInfoData.issignin" @tap="fnSignin">{{signinInfoData.issignin?'已签到':'签 到'}}</button>
				<view class="f28r cgray">已连续签到{{signinInfoData.connect}}天，共漏签{{signinInfoData.miss}}天</view>
			</view>
			<!-- 签到日历 -->
			<signin-calendar ref="signinCalendar"></signin-calendar>
			<view class="hl80r f32r fcenter c555 bgwhite bts2r mb18r" @tap="fnSigninRecord">奖励记录</view>
			<!-- 补签卡兑换 -->
			<block v-for="(item,index) in signinShopListData" :key="index">
				<view class="bgwhite mb18r">
					<view class="bbs2r ptb18r plr18r flex">
						<image class="hw200r br8r mr18r" :src="item.ImgSrc + '_200x200.jpg'" mode="aspectFill"></image>
						<view class="flex-fitem flexc-jsa">
							<view class="f28r c555">{{item.Name}}</view>
							<view class="f28r ctheme hl80r bbdash2r"><text class="f36r mr8r">{{item.Price}}</text>汉币</view>
							<view class="f24r cgray">
								<text class="ctheme mr8r">{{item.Orders.User.NickName}}</text> {{calDatetime(item.Orders.Datetime)}}分钟前换取了
							</view>
						</view>
					</view>
					<view class="flexr-jfe ptb18r plr18r">
						<view class="f28r bgtheme cwhite br8r plr18r ptb18r" @tap="fnHanbiExc(item.ID,item.Price)">汉币兑换</view>
					</view>
				</view>
			</block>
		</mescroll-uni>
	</view>
</template>

<script>
	import {
		fnFormatDate,
		fnSecondToTime
	} from "@/utils/CommonUtil.js"
	import {
		getSigninInfo,
		getHanbiShopList,
		signIn,
		addHanbiOrders
	} from "@/api/HanbiServer.js"

	// 签到日历组件
	import SigninCalendar from "@/components/signin-calendar/signin-calendar.vue"

	export default {
		components: {
			SigninCalendar
		},
		data() {
			return {
				// 距明日倒计时
				remaiTime: '00时00分00秒',
				timeInter: null,
				// 签到状态
				signinState: false,
				// 兑换状态
				orderState: false
			}
		},

		computed: {
			// 签到信息
			signinInfoData() {
				return this.$store.getters['getSigninInfoData']
			},
			// 签到商品列表
			signinShopListData() {
				return this.$store.getters['getSigninShopListData']
			},
			//
		},

		onLoad(options) {
			if (options && options.id) {
				// 获取签到信息
				getSigninInfo().then(signinRes => {
					// 保存签到信息
					this.$store.commit('setSigninInfoData', signinRes.data.Data)
					// 获取汉币兑换签到商品
					return getHanbiShopList({
						tag: 'signin',
						haveorders: true,
						role: 5,
						page: 1,
						limit: 10
					})
				}).then(shopRes => {
					// 保存签到商品列表
					this.$store.commit('setSigninShopListData', shopRes.data.Data)
					// 触发倒计时
					this.calRemaiTime()
				})
			}
		},

		methods: {
			/// 计算格式友好时间 几天前
			calDatetime(str) {
				let timestamp = new Date(str).getTime();
				return fnFormatDate(timestamp);
			},
			// 倒计时明日签到时间
			calRemaiTime() {
				this.timeInter = setInterval(() => {
					this.remaiTime = fnSecondToTime(this.signinInfoData.timecount);
					this.signinInfoData.timecount--
				}, 1000)
			},
			/// 签到
			fnSignin() {
				if (this.signinInfoData.issignin || this.signinState) return
				this.signinState = true
				uni.showLoading({
					title: '签到中',
					mask: true
				})
				signIn().then(res => {
					uni.hideLoading()
					if(!res.status){
						uni.showToast({
							title: res.msg,
							icon: 'error'
						})
						return;
					};
					uni.showToast({
						title: res.msg,
						icon: 'none'
					})
					// this.signinState = false
					return getSigninInfo()
				}).then(signinRes => {
					console.log(signinRes);
					
					// 保存签到信息
					this.$store.commit('setSigninInfoData', signinRes.data.Data)
					// 重新初始签到日历
					this.$refs.signinCalendar.init()
				}).catch((e) => {
					console.log(e);
					this.signinState = false
				})
			},
			/// 跳转签到奖励记录
			fnSigninRecord() {
				uni.navigateTo({
					url: '/pages/signin-record/signin-record'
				})
			},
			/// 汉币兑换
			fnHanbiExc(id, price) {
				if (this.orderState) return
				uni.showModal({
					content: '确认兑换吗？',
					success: res => {
						if (res.confirm) {
							this.orderState = true
							uni.showLoading({
								title: '兑换中',
								mask: true
							})
							addHanbiOrders({
								shopid: id,
								useraddressid: 0,
								remark: '',
							}).then(res => {
								uni.hideLoading()
								uni.showToast({
									title: '兑换成功'
								})
								this.orderState = false;
								// 登录用户汉币数减少
								let tempUser = this.$store.getters['user/getUserInfoData']
								tempUser.Hanbi = Number(tempUser.Hanbi) - Number(price)
								this.$store.commit('user/setUserInfoData', tempUser)
								// 重新初始签到日历
								this.$refs.signinCalendar.init()
							}).catch(() => {
								this.orderState = false;
							})
						}
					}
				})
			}
			//
		},

		beforeDestroy() {
			clearInterval(this.timeInter)
		}
	}
</script>

<style>

</style>

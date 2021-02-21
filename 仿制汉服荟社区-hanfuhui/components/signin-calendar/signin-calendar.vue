<template>
	<view>
		<!-- 顶部年月 -->
		<view class="flexr-jsb flex-aic ptb8r plr18r bgwhite bbs2r">
			<view class="hw64r fcenter" @tap="calDate.use ? dataBefor(-1) : ''"><i-icon type="zuo" size="42" color="#8F8F94" v-if="calDate.use"></i-icon></view>
			<view class="f28r c555">{{ canlenderData.year || 2020 }}年{{ canlenderData.month || 1 }}月</view>
			<view class="hw64r fcenter" @tap="calDate.end ? dataBefor(1) : ''"><i-icon type="you" size="42" color="#8F8F94" v-if="calDate.end"></i-icon></view>
		</view>
		<!-- 日历表 -->
		<view class="plr28r bgwhite h420r-min">
			<!-- 星期 -->
			<view class="flexr-jsb">
				<view class="day">日</view>
				<view class="day">一</view>
				<view class="day">二</view>
				<view class="day">三</view>
				<view class="day">四</view>
				<view class="day">五</view>
				<view class="day">六</view>
			</view>
			<!-- 日 -->
			<block v-for="(week, weekIndex) in canlenderData.weeks" :key="weekIndex">
				<view class="flexr-jsb">
					<view class="day" :class="{ 'day-signin': day.signin, 'day-disable': day.disable }" v-for="(day, dayIndex) in week" :key="dayIndex">{{ day.date }}</view>
				</view>
			</block>
		</view>
	</view>
</template>

<script>
import { getSignInDateList } from '@/api/HanbiServer.js';

/**
 * 签到日历组件
 * @property {Object} date 指定日期
 */
export default {
	name: 'signin-calendar',
	props: {
		// 指定日期
		date: {
			type: String,
			default: ''
		}
	},
	data() {
		return {
			// 日历数据
			canlenderData: {},
			// 当前年月日
			year: '',
			month: '',
			day: '',
			// 截止最新签到时间为当年当月
			endYear: '',
			endMonth: '',
			endDay: '',
			// 签到开通时间
			useDate: new Date().toISOString(),
			useYear: '',
			useMonth: '',
			useDay: ''
		};
	},
	computed: {
		// 计算日期范围上下翻
		calDate() {
			let use = true;
			let end = true;
			if (this.year == this.endYear && this.month == this.endMonth) end = false;
			if (this.year == this.useYear && this.month == this.useMonth) use = false;
			return {
				use,
				end
			};
		}
	},
	created() {
		this.init();
	},
	methods: {
		/// 初始化年月日
		init() {
			// 取日期对象
			let now = this.date ? new Date(this.date) : new Date();
			this.year = now.getFullYear();
			this.month = now.getMonth() + 1;
			this.day = now.getDate();
			// 最新签到年月
			this.endYear = now.getFullYear();
			this.endMonth = now.getMonth() + 1;
			this.endDay = now.getDate();
			// 获取签到日历
			this.fnSignInDate(this.year + '-' + this.month);
		},
		/// 获取签到日历
		fnSignInDate(date = '') {
			getSignInDateList(date).then(res => {
				// 保存签到日历记录信息
				this.$store.commit('setSigninDateData', res.data);
				// 刷新日历
				this.canlenderData = this.getCanlender(`${this.year}-${this.month}-${this.day}`);
				// 用户最多回看年月日
				let _useDate = new Date(res.data[0].day);
				this.useYear = _useDate.getFullYear();
				this.useMonth = 1; // 默认只能看今年的记录，开始限制：每年的1月份
				this.useDay = _useDate.getDate();

				// 关闭加载效果
				uni.hideLoading();
			});
		},
		/// 切换前一月 -1|| 后一月 1
		dataBefor(id) {
			// 前一个月
			if (id == -1) {
				this.month = Number(this.month) - 1;
				if (this.month == 0) {
					this.month = 12;
					this.year = Number(this.year) - 1;
				}
			}
			// 后一个月
			if (id == 1) {
				this.month = Number(this.month) + 1;
				if (this.month >= 13) {
					this.month = 1;
					this.year = Number(this.year) + 1;
				}
			}
			uni.showLoading({ title: '日历切换中', mask: true });
			// 获取签到日历
			this.fnSignInDate(`${this.year}-${this.month}`);
		},
		/// 获取日历内容周分组
		getCanlender(dateData) {
			// 判断当前是 安卓还是ios ，传入不容的日期格式
			if (typeof dateData !== 'object') {
				dateData = dateData.replace(/-/g, '/');
			}
			// 获取年，月，日，星期
			let _date = new Date(dateData);
			let year = _date.getFullYear();
			let month = _date.getMonth() + 1;
			let date = _date.getDate();
			let day = _date.getDay();
			// 日期数据对象
			let dates = {
				firstDay: new Date(year, month - 1, 1).getDay(),
				lastMonthDays: [], // 上个月末尾几天
				currentMonthDys: [], // 本月天数
				nextMonthDays: [], // 下个月开始几天
				endDay: new Date(year, month, 0).getDay(),
				weeks: []
			};
			// 循环上个月末尾几天添加到数组
			for (let i = dates.firstDay; i > 0; i--) {
				const beforeDate = new Date(year, month - 1, -i + 1).getDate() + '';
				dates.lastMonthDays.push({
					date: beforeDate,
					month: month - 1,
					disable: true,
					isDay: false
				});
			}
			// 循环本月天数添加到数组
			for (let i = 1; i <= new Date(year, month, 0).getDate(); i++) {
				// 确认签到日期
				let signin = this.$store.getters['getSigninDateData'].some(item => {
					if (!item.is_sign) return false;
					let _dataArr = item.day.split(' ')[0].split('-');
					return year == _dataArr[0] && month == _dataArr[1] && i == Number(_dataArr[2]);
				});
				//
				dates.currentMonthDys.push({
					date: i + '',
					month: month,
					signin: signin,
					disable: false,
					isDay: dateData == year + '/' + month + '/' + i
				});
			}
			// 循环下个月开始几天 添加到数组
			const surplus = 42 - (dates.lastMonthDays.length + dates.currentMonthDys.length);
			for (let i = 1; i < surplus + 1; i++) {
				dates.nextMonthDays.push({
					date: i + '',
					month: month + 1,
					disable: true,
					isDay: false
				});
			}
			// 拼接数组  上个月开始几天 + 本月天数+ 下个月开始几天
			let canlender = [].concat(dates.lastMonthDays, dates.currentMonthDys, dates.nextMonthDays);
			// 日历分周
			for (let i = 0; i < canlender.length; i++) {
				if (i % 7 === 0) {
					dates.weeks[parseInt(i / 7)] = new Array(7);
				}
				dates.weeks[parseInt(i / 7)][i % 7] = canlender[i];
			}
			return {
				weeks: dates.weeks,
				month: month,
				date: date,
				day: day,
				year: year,
				lastDate: dates.currentMonthDys[dates.currentMonthDys.length - 1].date
			};
		}
		//
	}
};
</script>

<style>
.h420r-min {
	min-height: 420rpx;
}

.day {
	width: 64rpx;
	height: 64rpx;
	line-height: 54rpx;
	font-size: 24rpx;
	color: #8f8f94;
	text-align: center;
}

.day-signin {
	color: #ff6699;
	border: 2px #ff6699 solid;
	border-radius: 50%;
	overflow: hidden;
	box-sizing: border-box;
}

.day-disable {
	color: #eeeeee;
}
</style>

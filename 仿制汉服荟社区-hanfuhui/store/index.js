import Vue from 'vue'
import Vuex from 'vuex'
// 模块
import album from './module/album.js'
import common from './module/common.js'
import huiba from './module/huiba.js'
import interact from './module/interact.js'
import org from './module/org.js'
import shop from './module/shop.js'
import topic from './module/topic.js'
import trend from './module/trend.js'
import user from './module/user.js'
import video from './module/video.js'
import word from './module/word.js'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    // 评论内容
    commContent: '',
    // 签到信息
    signinInfo: {},
    // 签到日历记录
    signinDate: [],
    // 签到记录
    signinList: [],
    // 签到商品列表
    signinShopList: [],
    // 汉币规则列表
    hanbiRule: [],
    // 消息数对象数据
    newsCount: {},
  },
  getters: {
    // 获取评论内容  this.$store.getters['getCommContentData']
    getCommContentData: state => state.commContent,
    // 获取签到信息  this.$store.getters['getSigninInfoData']
    getSigninInfoData: state => state.signinInfo,
    // 获取签到状态  this.$store.getters['getSigninStatusData']
    getSigninStatusData: state => state.signinInfo.issignin,
    // 获取签到日历记录  this.$store.getters['getSigninDateData']
    getSigninDateData: state => state.signinDate,
    // 获取签到记录  this.$store.getters['getSigninListData']
    getSigninListData: state => state.signinList,
    // 获取签到商品列表  this.$store.getters['getSigninShopListData']
    getSigninShopListData: state => state.signinShopList,
    // 获取消息数对象数据  this.$store.getters['getNewsCountData']
    getNewsCountData: state => state.newsCount,
    // 获取汉币规则列表  this.$store.getters['getHanbiRuleData']
    getHanbiRuleData: state => state.hanbiRule,
    // 获取消息总数  this.$store.getters['getNewsTotalData']
    getNewsTotalData(state) {
		return 0;
      let {
        RemindCount,
        CommentCount,
        TopCount,
        NoticeCount,
        TopicCount
      } = state.newsCount
      return RemindCount + CommentCount + TopCount + NoticeCount + TopicCount
    },
    //
  },
  mutations: {
    // 修改评论内容 this.$store.commit('setCommContentData', '')
    setCommContentData(state, data) {
      state.commContent = data
    },
    // 修改签到信息 this.$store.commit('setSigninInfoData', {...})
    setSigninInfoData(state, data) {
      state.signinInfo = data
    },
    // 修改签到日历记录 this.$store.commit('setSigninDateData', {...})
    setSigninDateData(state, data) {
      state.signinDate = data
    },
    // 修改签到记录 this.$store.commit('setSigninListData', [...])
    setSigninListData(state, data) {
      state.signinList = data
    },
    // 修改签到商品列表 this.$store.commit('setSigninShopListData', {...})
    setSigninShopListData(state, data) {
      state.signinShopList = data
    },
    // 修改汉币规则列表 this.$store.commit('setHanbiRuleData', {...})
    setHanbiRuleData(state, data) {
      state.hanbiRule = data
    },
    // 修改消息数对象数据 this.$store.commit('setNewsCountData', {...})
    setNewsCountData(state, data) {
      state.newsCount = data
    },
    //
  },
  modules: {
    album,
    common,
    huiba,
    interact,
    org,
    shop,
    topic,
    trend,
    user,
    video,
    word
  }
})

export default store

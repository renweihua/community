/**
 * ==========
 * 话题状态数据
 * ==========
 */
const topic = {
  namespaced: true,

  state: {
    // 话题列表数据 this.$store.state.topic.topicList
    topicList: [],
    // 话题项信息数据
    topicInfo: {},
    // 话题内最新列表数据
    topicLatestList: [],
    // 话题内最热列表数据
    topicHottestList: [],
    // 话题讨论项信息数据
    topicReplyInfo: {},
    // 话题用户关注列表数据
    topicUserFollow: [],
    // 话题拉黑名单列表数据
    topicBlackList: []
    // 
  },
  getters: {
    // 话题列表数组对象  this.$store.getters['topic/getTopicListData']
    getTopicListData: state => state.topicList,
    // 话题项信息数据  this.$store.getters['topic/getTopicInfoData']
    getTopicInfoData: state => state.topicInfo,
    // 话题内最新列表数据  this.$store.getters['topic/getTopicLatestListData']
    getTopicLatestListData: state => state.topicLatestList,
    // 话题内最热列表数据  this.$store.getters['topic/getTopicHottestListData']
    getTopicHottestListData: state => state.topicHottestList,
    // 话题讨论项信息数据  this.$store.getters['topic/getTopicReplyInfoData']
    getTopicReplyInfoData: state => state.topicReplyInfo,
    // 话题用户关注列表数据  this.$store.getters['topic/getTopicUserFollowData']
    getTopicUserFollowData: state => state.topicUserFollow,
    // 话题拉黑名单列表数据  this.$store.getters['topic/getTopicBlackListData']
    getTopicBlackListData: state => state.topicBlackList,
    //
  },
  mutations: {
    // 修改话题列表数组对象 this.$store.commit('topic/setTopicListData', [...])
    setTopicListData(state, data) {
      state.topicList = data
    },
    // 修改话题项信息数据 this.$store.commit('topic/setTopicInfoData', {...})
    setTopicInfoData(state, data) {
      state.topicInfo = data
    },
    // 修改话题内最新列表数据 this.$store.commit('topic/setTopicLatestListData', [...])
    setTopicLatestListData(state, data) {
      state.topicLatestList = data
    },
    // 修改话题内最热列表数据 this.$store.commit('topic/setTopicHottestListData', [...])
    setTopicHottestListData(state, data) {
      state.topicHottestList = data
    },
    // 修改话题讨论项信息数据 this.$store.commit('topic/setTopicReplyInfoData', {...})
    setTopicReplyInfoData(state, data) {
      state.topicReplyInfo = data
    },
    // 修改话题用户关注列表数据 this.$store.commit('topic/setTopicUserFollowData', [...])
    setTopicUserFollowData(state, data) {
      state.topicUserFollow = data
    },
    // 修改话题拉黑名单列表数据 this.$store.commit('topic/setTopicBlackListData', [...])
    setTopicBlackListData(state, data) {
      state.topicBlackList = data
    }, 
    //
  } 
}

export default topic

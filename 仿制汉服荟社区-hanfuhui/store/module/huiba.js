/**
 * ==========
 * 荟吧状态数据
 * ==========
 */
const huiba = {
  namespaced: true,

  state: {
    // 荟吧列表数据 this.$store.state.huiba.huibaList
    huibaList: [],
    // 荟吧项信息数据
    huibaInfo: {},
    // 荟吧项置顶公告信息数据
    huibaTop: [],
    // 荟吧内最新列表数据
    huibaLatestList: [],
    // 荟吧内最热列表数据
    huibaHottestList: [],
    // 荟吧用户关注列表数据
    huibaUserFollow: [],
    // 荟吧类型项列表数据
    huibaType: [],
    // 荟吧类型列表数据
    huibaTypeList: [],
    //
  },
  getters: { 
    // 荟吧列表数据对象  this.$store.getters['huiba/getHuibaListData']
    getHuibaListData: state => state.huibaList,
    // 荟吧项信息数据 this.$store.getters['huiba/getHuibaInfoData']
    getHuibaInfoData: state => state.huibaInfo,
    // 荟吧项置顶公告信息数据
    getHuibaTopData: state => state.huibaTop,
    // 荟吧内最新列表数据
    getHuibaLatestListData: state => state.huibaLatestList,
    // 荟吧内最热列表数据
    getHuibaHottestListData: state => state.huibaHottestList,
    // 荟吧用户关注列表数据 this.$store.getters['huiba/getHuibaUserFollowData']
    getHuibaUserFollowData: state => state.huibaUserFollow, 
    //
  },
  mutations: {
    // 修改荟吧列表数据对象 this.$store.commit('huiba/setHuibaListData', [...])
    setHuibaListData(state, data) {
      state.huibaList = data
    },
    // 修改荟吧项信息数据对象 this.$store.commit('huiba/setHuibaInfoData', [...])
    setHuibaInfoData(state, data) {
      state.huibaInfo = data
    },
    // 修改荟吧项置顶公告信息对象 this.$store.commit('huiba/setHuibaTopData', [...])
    setHuibaTopData(state, data) {
      state.huibaTop = data
    },
    // 修改荟吧内最新列表数据对象
    setHuibaLatestListData(state, data) {
      state.huibaLatestList = data
    },
    // 修改荟吧内最热列表数据对象
    setHuibaHottestListData(state, data) {
      state.huibaHottestList = data
    },
    // 修改荟吧用户关注列表数据对象 this.$store.commit('huiba/setHuibaUserFollowData', [...])
    setHuibaUserFollowData(state, data) {
      state.huibaUserFollow = data
    },
    //
  }

}

export default huiba

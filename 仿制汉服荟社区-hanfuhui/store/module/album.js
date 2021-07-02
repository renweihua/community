/**
 * ==========
 * 摄影状态数据
 * ==========
 */
const album = {
  namespaced: true,

  state: {
    // 摄影列表数据 this.$store.state.album.albumList
    albumList: [],
    // 摄影项信息数据
    albumInfo: {},
    // 摄影榜封面数据
    rankFace: {},
    // 摄影周榜列表数据
    rankWeekList: {},
    // 摄影月榜列表数据
    rankMonthList: {},
    //
  },
  getters: {
    // 摄影列表数据条数  this.$store.getters['album/getAlbumListCount']
    getAlbumListCount: state => state.albumList.length,
    // 摄影列表数组对象  this.$store.getters['album/getAlbumListData']
    getAlbumListData: state => state.albumList,
    // 摄影项数据对象
    getAlbumInfoData: state => state.albumInfo,
    // 摄影榜封面对象
    getRankFaceData: state => state.rankFace,
    // 摄影周榜列表对象
    getRankWeekListData: state => state.rankWeekList,
    // 摄影月榜列表对象 this.$store.getters['album/getRankMonthListData']
    getRankMonthListData: state => state.rankMonthList,
    //
  },
  mutations: {
    // 修改摄影列表数组对象 this.$store.commit('album/setAlbumListData', [...])
    setAlbumListData(state, data) {
      state.albumList = data
    },
    // 修改摄影项数据对象
    setAlbumInfoData(state, data) {
      state.albumInfo = data
    },
    // 修改摄影榜封面对象 this.$store.commit('album/setRankFaceData', {...})
    setRankFaceData(state, data) {
      state.rankFace = data
    },
    // 修改摄影周榜列表对象 this.$store.commit('album/setRankWeekListData', {...})
    setRankWeekListData(state, data) {
      state.rankWeekList = data
    },
    // 修改摄影月榜列表对象 this.$store.commit('album/setRankMonthListData', {...})
    setRankMonthListData(state, data) {
      state.rankMonthList = data
    },
    //
  }
}

export default album

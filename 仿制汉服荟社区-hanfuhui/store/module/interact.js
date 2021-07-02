/**
 * ==========
 * 动态状态数据
 * ==========
 */
const interact = {
  namespaced: true,

  state: {
    // 动态项点赞列表数据 this.$store.state.interact.topList
    topList: [],
    // 动态项评论列表数据
    commentList: [],
    // 动态用户收藏-全部数据
    userSaveAllList: [],
    // 动态用户收藏-摄影数据
    userSaveAlbumList: [],
    // 动态用户收藏-视频数据
    userSaveVideoList: [],
    // 动态用户收藏-文章数据
    userSaveWordList: [],
    //
  },
  getters: {
    // 动态项点赞列表数组对象  this.$store.getters['interact/getDynamicPraisesData']
    getDynamicPraisesData: state => state.topList,
    // 动态项评论列表数组对象
    getCommentListData: state => state.commentList,
    // 动态用户收藏-全部数据
    getUserSaveAllListData: state => state.userSaveAllList,
    // 动态用户收藏-摄影数据
    getUserSaveAlbumListData: state => state.userSaveAlbumList,
    // 动态用户收藏-视频数据
    getUserSaveVideoListData: state => state.userSaveVideoList,
    // 动态用户收藏-文章数据
    getUserSaveWordListData: state => state.userSaveWordList,
  },
  mutations: {
    // 修改动态项点赞列表数组对象 this.$store.commit('interact/setTopListData', [...])
    setTopListData(state, data) {
      state.topList = data
    },
    // 修改动态项评论列表数组对象 this.$store.commit('interact/setCommentListData', [...])
    setCommentListData(state, data) {
      state.commentList = data
    },
    // 修改动态用户收藏-全部数据对象 this.$store.commit('interact/setUserSaveAllListData', [...])
    setUserSaveAllListData(state, data) {
      state.userSaveAllList = data
    },
    // 修改动态用户收藏-摄影数据对象 this.$store.commit('interact/setUserSaveAlbumListData', [...])
    setUserSaveAlbumListData(state, data) {
      state.userSaveAlbumList = data
    },
    // 修改动态用户收藏-视频数据对象 this.$store.commit('interact/setUserSaveVideoListData', [...])
    setUserSaveVideoListData(state, data) {
      state.userSaveVideoList = data
    },
    // 修改动态用户收藏-文章数据对象 this.$store.commit('interact/setUserSaveWordListData', [...])
    setUserSaveWordListData(state, data) {
      state.userSaveWordList = data
    },
    //
  }
}
export default interact

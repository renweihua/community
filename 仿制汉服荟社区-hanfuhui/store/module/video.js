/**
 * ==========
 * 视频状态数据
 * ==========
 */
const video = {
  namespaced: true,

  state: {
    // 视频列表数据 this.$store.state.video.videoList
    videoList: [],
    // 视频项信息数据
    videoInfo: {},
    // 视频解密后地址
    videoUrl: "",
    //
  },
  getters: {
    // 视频列表数组对象  this.$store.getters['video/getVideoListData']
    getVideoListData: state => state.videoList,
    // 视频项信息数据对象  this.$store.getters['video/getVideoInfoData']
    getVideoInfoData: state => state.videoInfo,
    // 视频解密后地址对象  this.$store.getters['video/getVideoUrlData']
    getVideoUrlData: state => state.videoUrl,
    //
  },
  mutations: {
    // 修改视频列表数组对象 this.$store.commit('video/setVideoListData', [...])
    setVideoListData(state, data) {
      state.videoList = data
    },
    // 修改视频项数据对象 this.$store.commit('video/setVideoInfoData', {...})
    setVideoInfoData(state, data) {
      state.videoInfo = data
    },
    // 修改解密视频播放地址
    setVideoUrlData(state, data) {
      state.videoUrl = data
    },
    //
  }

}

export default video

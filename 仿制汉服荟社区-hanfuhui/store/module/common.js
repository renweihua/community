/**
 * ==========
 * 常见公共状态数据
 * ==========
 */
const common = {
  namespaced: true,

  state: {
    // Banner图数据 this.$store.state.common.bannerList
    bannerList: [],
    // 热门话题数据
    hotTopicList: [],
    // 启动封面图数据
    starCover: [],
    // 省列表
    provinceList: [],
    // 城市列表
    cityList: [], 
    // 排行榜-摄影列表
    rankAlbumList: [],
    // 排行榜-汉币列表
    rankHanbiList: [],
    // 排行榜-人气列表
    rankPopularityList: [],
    // 排行榜-签到列表
    rankSigninList: [],
    // 处罚公示列表
    punishList: []
    //
  },
  getters: {
    // Banner图数组对象  this.$store.getters['common/getBannerListData']
    getBannerListData: state => state.bannerList,
    // 热门话题数组对象 
    getHotTopicListData: state => state.hotTopicList,
    // 启动封面图数据 this.$store.getters['common/getStarCoverData']
    getStarCoverData: state => state.starCover[0],
    // 省列表对象
    getProvinceListData: state => state.provinceList,
    // 城市列表对象
    getCityListData: state => state.cityList,
    // 排行榜-摄影列表对象 this.$store.getters['common/getRankAlbumListData']
    getRankAlbumListData: state => state.rankAlbumList,
    // 排行榜-汉币列表对象
    getRankHanbiListData: state => state.rankHanbiList,
    // 排行榜-人气列表对象
    getRankPopularityListData: state => state.rankPopularityList,
    // 排行榜-签到列表对象
    getRankSigninListData: state => state.rankSigninList,
    // 处罚公示列表数据对象 this.$store.getters['common/getPunishListData']
    getPunishListData: state => state.punishList,
    //
  },
  mutations: {
    // 修改封面图数据 this.$store.commit('common/setStarCoverData', [...])
    setStarCoverData(state, data) {
      state.starCover = data
    },
    // 修改省列表对象数据 this.$store.commit('common/setProvinceListData', [...])
    setProvinceListData(state, data) {
      state.provinceList = data
    },
    // 修改城市列表对象数据 this.$store.commit('common/setCityListData', [...])
    setCityListData(state, data) {
      state.cityList = data
    },
    // 修改Banner图数组对象 this.$store.commit('common/setBannerListData', [...])
    setBannerListData(state, data) {
      state.bannerList = data
    },
    // 修改热门话题数组对象 this.$store.commit('common/setHotTopicListData', [...])
    setHotTopicListData(state, data) {
      state.hotTopicList = data
    }, 
    // 修改排行榜-摄影列表 this.$store.commit('common/setRankAlbumListData', [...])
    setRankAlbumListData(state, data) {
      state.rankAlbumList = data
    },
    // 修改排行榜-汉币列表 this.$store.commit('common/setRankHanbiListData', [...])
    setRankHanbiListData(state, data) {
      state.rankHanbiList = data
    },
    // 修改排行榜-人气列表 this.$store.commit('common/setRankPopularityListData', [...])
    setRankPopularityListData(state, data) {
      state.rankPopularityList = data
    },
    // 修改排行榜-签到列表 this.$store.commit('common/setRankSigninListData', [...])
    setRankSigninListData(state, data) {
      state.rankSigninList = data
    },
    // 修改处罚公示列表数据对象 this.$store.commit('common/setPunishListData', [...])
    setPunishListData(state, data) {
      state.punishList = data
    },
    //
  },
}

export default common

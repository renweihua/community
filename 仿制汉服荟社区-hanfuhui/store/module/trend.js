/**
 * ==========
 * 最新动态状态数据
 * ==========
 */
const trend = {
  namespaced: true,

  state: {
    // 广场数据 this.$store.state.trend.squareData
    squareData: [],
    // 推荐数据
    mainData: [],
    // 关注数据
    atteData: [],
    // 动态信息数据
    dynamics: {}
    //
  },
  getters: {
    // 广场数据条数  this.$store.getters['trend/getSquareCount']
    getSquareCount: state => state.squareData.length,
    // 广场数据数组对象  this.$store.getters['trend/getSquareData']
    getSquareData: state => state.squareData,
    // 推荐数据条数
    getMainCount: state => state.mainData.length,
    // 推荐数据数组对象
    getMainData: state => state.mainData,
    // 关注数据条数
    getAtteCount: state => state.atteData.length,
    // 关注数据数组对象
    getAtteData: state => state.atteData,
    // 动态信息数据对象
    getDynamics: state => state.dynamics,
    //
  },
  mutations: {
    // 修改广场数据数组对象 this.$store.commit('trend/setSquareData', [...])
    setSquareData(state, data) {
      state.squareData = data
    },
    // 修改推荐数据数组对象
    setMainData(state, data) {
      state.mainData = data
    },
    // 修改关注数据数组对象
    setAtteData(state, data) {
      state.atteData = data
    },
    // 修改动态信息数据对象
    setTrendData(state, data) {
      state.dynamics = data
    },
    //
  }
}

export default trend

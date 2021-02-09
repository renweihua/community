/**
 * ==========
 * 商城状态数据
 * ==========
 */
const shop = {
  namespaced: true,

  state: {
    // 商城专题列表 this.$store.state.shop.specialList
    specialList: [],
    // 商城专题项信息数据
    specialInfo: {}, 
    // 商品热卖列表数据
    productHottestList: [],
    // 商品精选最好列表
    productBestList: [],
    // 商品最新发布列表
    productNewestList: [],
  },
  getters: {
    // 商城列表数据条数  this.$store.getters['shop/getSpecialListCount']
    getSpecialListCount: state => state.specialList.length,
    // 商城专题列表数组对象  this.$store.getters['shop/getSpecialListData']
    getSpecialListData: state => state.specialList,
    // 商城专题项信息数据对象
    getSpecialInfoData: state => state.specialInfo,
    // 商品精选最好列表数组对象
    getProductBestListData: state => state.productBestList,
    // 商品最新发布列表数组对象
    getProductNewestListData: state => state.productNewestList,
    // 商品热卖对象数组对象
    getProductHottestListData: state => state.productHottestList,
    //
  },
  mutations: {
    // 修改商城专题列表数组对象 this.$store.commit('shop/setSpecialListData', [...])
    setSpecialListData(state, data) {
      state.specialList = data
    },
    // 修改商城专题项信息数据对象 this.$store.commit('shop/setSpecialInfoData', {})
    setSpecialInfoData(state, data) {
      state.specialInfo = data
    },
    // 修改商品精选最好列表数组对象 this.$store.commit('shop/setProductBestListData', [...])
    setProductBestListData(state, data) {
      state.productBestList = data
    },
    // 修改商品最新发布列表数组对象 this.$store.commit('shop/setProductNewestListData', [...])
    setProductNewestListData(state, data) {
      state.productNewestList = data
    },
    // 修改商品热卖对象数组对象 this.$store.commit('shop/setProductHottestListData', [...])
    setProductHottestListData(state, data) {
      state.productHottestList = data
    },
    //
  } 
}

export default shop

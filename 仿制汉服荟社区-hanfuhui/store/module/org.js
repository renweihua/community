/**
 * ==========
 * 组织活动状态数据
 * ==========
 */
const org = {
  namespaced: true,

  state: {
    // 组织省市列表数据 this.$store.state.org.orgProvinceList
    orgProvinceList: [],
    // 组织活动列表数据 this.$store.state.org.orgList
    orgList: [],
    // 组织活动项信息数据
    orgInfo: {},
    // 组织活动用户报名列表数据
    orgUserList: [],
    //
  },
  getters: {
    // 组织省市列表数据对象  this.$store.getters['org/getOrgProvinceListData']
    getOrgProvinceListData: state => state.orgProvinceList,
    // 组织活动列表数据条数
    getOrgListCount: state => state.orgList.length,
    // 组织活动列表数组对象
    getOrgListData: state => state.orgList,
    // 组织活动项数据对象
    getOrgInfoData: state => state.orgInfo,
    // 组织活动用户报名列表数组对象
    getOrgUserListData: state => state.orgUserList,
    //
  },
  mutations: {
    // 修改组织省市列表数据对象 this.$store.commit('org/setOrgProvinceListData', [...])
    setOrgProvinceListData(state, data) {
      state.orgProvinceList = data
    },
    // 修改组织活动列表数组对象 this.$store.commit('org/setOrgListData', [...])
    setOrgListData(state, data) {
      state.orgList = data
    },
    // 修改组织活动项数据对象 this.$store.commit('org/setOrgInfoData', [...])
    setOrgInfoData(state, data) {
      state.orgInfo = data
    },
    // 修改组织活动榜封面对象 this.$store.commit('org/setOrgUserListData', [...])
    setOrgUserListData(state, data) {
      state.orgUserList = data
    },
    //
  }

}

export default org

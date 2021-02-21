/**
 * ==========
 * 用户状态数据
 * ==========
 */
const user = {
  namespaced: true,

  state: {
    // 登录会员信息数据
    loginUserInfo: {},
    // 登录会员的关注人列表数据 this.$store.state.user.followsList
    followsList: [],
    // 用户的粉丝列表 this.$store.state.user.userFansList
    userFansList: [],
    // 用户拉黑名单列表数据
    userBlackList: [],
    // 缓存用户信息数据
    tempUserInfo: {},
    // 账户信息数据
    accountInfo: {},
    // 用户点赞过动态列表
    userTopList: [],
    // 用户发布所有类型列表
    userPublishList: [],
    //
  },
  getters: {
    // 登录会员信息数据对象 this.$store.getters['user/getLoginUserInfoData']
    getLoginUserInfoData: state => state.loginUserInfo,
    // 用户关注用户列表对象  this.$store.getters['user/getFollowsListData']
    getFollowsListData: state => state.followsList,
    // 用户的粉丝列表  this.$store.getters['user/getUserFansListData']
    getUserFansListData: state => state.userFansList,
    // 用户拉黑名单列表数据  this.$store.getters['user/getUserBlackListData']
    getUserBlackListData: state => state.userBlackList,
    // 用户信息数据对象 this.$store.getters['user/getUserInfoData']
    getUserInfoData: state => state.userInfo,
    // 账户信息数据对象
    getAccountInfoData: state => state.accountInfo,
    // 临时用户信息数据
    getTempUserInfoData: state => state.tempUserInfo,
    // 用户点赞过动态列表对象  this.$store.getters['user/getUserTopListData']
    getUserTopListData: state => state.userTopList,
    // 用户发布所有类型列表对象
    getUserPublishListData: state => state.userPublishList,
    //
  },
  mutations: {
    // 登录会员信息数据对象  this.$store.commit('user/setLoginUserInfoData', {...})
    setLoginUserInfoData(state, data) {
      state.loginUserInfo = data
    },
    // 修改用户关注用户列表数组对象 this.$store.commit('user/setFollowsListData', [...])
    setFollowsListData(state, data) {
      state.followsList = data
    },
    // 修改用户粉丝列表数组对象 this.$store.commit('user/setUserFansListData', [...])
    setUserFansListData(state, data) {
      state.userFansList = data
    },
    // 修改用户拉黑名单列表数据 this.$store.commit('user/setUserBlackListData', [...])
    setUserBlackListData(state, data) {
      state.userBlackList = data
    },
    // 修改临时用户信息数据对象 this.$store.commit('user/setTempUserInfoData', {...})
    setTempUserInfoData(state, data) {
      state.tempUserInfo = data
    },
    // 修改账户信息数据对象 this.$store.commit('user/setAccountInfoData', {...})
    setAccountInfoData(state, data) {
      state.accountInfo = data
    },
    // 修改账户信息数据对象 this.$store.commit('user/setAccountInfoMainBgPicData', 'url')
    setAccountInfoMainBgPicData(state, url) {
      state.accountInfo.User.MainBgPic = url
    },
    // 修改用户点赞过动态列表  this.$store.commit('user/setUserTopListData', [...])
    setUserTopListData(state, data) {
      state.userTopList = data
    },
    // 修改用户发布所有类型列表 this.$store.commit('user/setUserPublishListData', [...])
    setUserPublishListData(state, data) {
      state.userPublishList = data
    },
    //
  }
}

export default user

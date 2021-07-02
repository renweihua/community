/**
 * ==========
 * 文章状态数据
 * ==========
 */
const word = {
  namespaced: true,

  state: {
    // 文章列表数据 this.$store.state.word.wordList
    wordList: [],
    // 文章基本信息数据
    wordInfo: {},
    // 文章内容数据
    wordContent: "",
    //
  },
  getters: {
    // 文章列表数组对象  this.$store.getters['word/getWordListData']
    getWordListData: state => state.wordList,
    // 文章基本信息数据对象  this.$store.getters['word/getWordInfoData']
    getWordInfoData: state => state.wordInfo,
    // 文章列表数组对象  this.$store.getters['word/getWordContentData']
    getWordContentData: state => state.wordContent,
    //
  },
  mutations: {
    // 修改文章列表数组对象 this.$store.commit('word/setWordListData', [...])
    setWordListData(state, data) {
      state.wordList = data
    },
    // 修改文章项数据对象
    setWordInfoData(state, data) {
      state.wordInfo = data
    },
    // 修改文章列表数组对象
    setWordContentData(state, data) {
      state.wordContent = data
    },
    //
  } 

}

export default word

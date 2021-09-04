<template>
  <threads-list :threads="threads" @page-changed="handlePageChanged" />
</template>

<script>
import ThreadsList from '$components/threads-list'

export default {
  name: 'user-threads',
  components: { ThreadsList },
  data () {
    return {
      threads: {},
    }
  },
  methods: {
    loadThreads (page = 1) {
      this.$http
        .get(`dynamics?user_id=${this.$parent.user.user_id}&page=${page}`)
        .then(threads => {
          this.threads = threads.data;
        })
    },
    handlePageChanged (page) {
      this.loadThreads(page)
    }
  },
  watch:{
    // 父级标识变动，重新拉取动态信息
    '$parent.user.user_id'(new_id, old){
        this.loadThreads();
    },
  },
  mounted () {
    this.loadThreads()
  }
}
</script>

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
      threads: {}
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
  mounted () {
    this.loadThreads()
  }
}
</script>

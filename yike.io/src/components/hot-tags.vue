<template>
  <div class="box text-gray-50">
    <div class="box-heading">
      <div class="text-13">热门话题</div>
    </div>
    <ul class="plan-list text-13">
      <template v-for="item in nodes">
        <router-link tag="li" :key="item.topic_id" :to="{name: 'nodes.node', params:{topic_id: item.topic_id}}" class="py-1 cursor-pointer">
          #{{ item.topic_name }} <span class="float-right">{{item.dynamic_count}}</span>
        </router-link>
      </template>
    </ul>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'HotTags',
  data () {
    return {
      nodes: []
    }
  },
  computed: {
    ...mapGetters(['isLogged'])
  },
  methods: {
    loadNodes () {
      // 登录的会员，查看10个置顶话题；未登录会员查看6个【页面布局】
      this.$http
        .get('topics?hot=5&limit=' + (this.isLogged == true ? 10 : 6))
        .then(nodes => (this.nodes = nodes.data))
    }
  },
  created () {
    this.loadNodes()
  }
}
</script>

<style scoped>
</style>

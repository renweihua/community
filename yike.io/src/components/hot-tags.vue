<template>
  <div class="box text-gray-50">
    <div class="box-heading">
      <div class="text-13">热门话题</div>
    </div>
    <ul class="plan-list text-13">
      <template v-for="item in nodes">
        <router-link tag="li" :key="item.topic_id" :to="{name: 'nodes.item', params:{topic_id: item.topic_id}}" class="py-1 cursor-pointer">
          #{{ item.topic_name }} <span class="float-right">{{item.dynamic_count}}</span>
        </router-link>
      </template>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'HotTags',
  data () {
    return {
      nodes: []
    }
  },
  methods: {
    loadNodes () {
      this.$http
        .get('topics?hot=5&limit=5')
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

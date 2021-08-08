<template>
  <notification :notification="notification">
    <template slot="title">
      您订阅了话题
      <router-link :to="{name: 'nodes.node', params: {topic_id: notification.relation.topic_id}}">
        《{{ notification.relation.topic_name }}》
      </router-link>
    </template>
  </notification>
</template>

<script>
import Notification from './notification'

export default {
  components: { Notification },
  props: {
    notification: {
      type: Object,
      default: null
    }
  }
}
</script>

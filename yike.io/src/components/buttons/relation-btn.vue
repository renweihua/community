<template>
  <div @click="toggle">
    <template v-if="!item[actions[action]]">
      <slot name="on"></slot>
    </template>
    <template v-else>
      <slot name="off"></slot>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    relation: {
      type: String,
      required: true
    },
    action: {
      type: String,
      required: true
    },
    item: {
      type: Object,
      required: true,
      default () {
        return {}
      }
    }
  },
  data () {
    return {
      types: {
        thread: 'App\\Thread',
        user: 'App\\User',
        node: 'App\\Node'
      },
      actions: {
        like: 'has_liked',
        follow: 'has_followed',
        subscribe: 'has_subscribed'
      }
    }
  },
  methods: {
    toggle () {
      // let action = !this.item[this.actions[this.action]] ? this.action : `${this.action}`

      console.log(this.item);
      console.log(this.action);

      this.$http.post(this.action == 'like' ? 'dynamic/praise' : `relations/${this.action}`, {
        followable_type: this.types[this.relation],
        dynamic_id: this.item.dynamic_id
      }).then((res) => {
        if(res.status == 1){
          this.item[this.actions[this.action]] = !this.item[
            this.actions[this.action]
          ]
          this.$emit('after-toggle', this.item[this.actions[this.action]])
        }
      })
    }
  }
}
</script>

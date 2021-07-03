<template>
  <div class="threads-items mb-2">
    <ul class="list-group list-group-flush">
      <li
        class="list-group-item d-md-flex d-block align-items-center justify-content-between cursor-pointer"
        v-for="item in threads.data"
        :key="item.dynamic_id"
        @click="$router.push({name: 'threads.show', params:{id: item.dynamic_id}})"
      >
        <div class="d-flex align-items-center w-70">
          <a href="#" class="mr-2">
            <img :src="item.user_info.user_avatar" alt class="avatar-30">
          </a>
          <div class="text-gray-50">
            <span v-if="item.excellent_at" class="badge badge-success">精华</span>
            <span v-if="item.pinned_at" class="badge badge-danger">置顶</span>
            {{ item.dynamic_title }}
          </div>
        </div>
        <div class="ml-auto d-flex align-items-center justify-content-md-end">
          <div class="text-gray-60 d-flex justify-content-between align-items-center">
            <a class="p-1">
              <like-icon></like-icon>
              {{ item.praise_count }}
            </a>
            <a class="p-1">
              <comment-icon></comment-icon>
              {{ item.collection_count }}
            </a>
            <a class="p-1">
              <view-icon></view-icon>
              {{ item.read_num }}
            </a>
          </div>
          <div class="ml-1 text-gray-60">
            <small>{{ item.time_formatting }}</small>
          </div>
        </div>
      </li>
      <li
        class="list-group-item d-flex align-items-center justify-content-center p-5"
        v-if="threads['data'] && threads.data.length == 0"
      >
        <empty-state message="该分类下无相关讨论哦~"></empty-state>
      </li>
    </ul>
    <paginator :meta="threads.meta" @change="handleChange"></paginator>
  </div>
</template>

<script>
import Paginator from '$components/paginator'
import LikeIcon from '$icons/Heart'
import CommentIcon from '$icons/Comment'
import ViewIcon from '$icons/Eye'
import EmptyState from '$components/empty-state'
import MedalIcon from '$icons/Medal'
import TopIcon from '$icons/FormatVerticalAlignTop'

export default {
  name: 'threads-list',
  components: {
    LikeIcon,
    CommentIcon,
    ViewIcon,
    MedalIcon,
    TopIcon,
    Paginator,
    EmptyState
  },
  props: {
    threads: {
      type: Object,
      default () {
        return {
          data: [],
          meta: {
            total: 1,
            per_page: 1,
            from: 1,
            to: 1,
            last_page: 1,
            current_page: 1
          }
        }
      }
    }
  },
  methods: {
    handleChange (page) {
      this.$emit('page-changed', page)
    }
  }
}
</script>

<style scoped>
</style>

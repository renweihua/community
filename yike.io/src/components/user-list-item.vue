<template>
  <div class="user-media d-flex align-items-center">
    <router-link :to="{name: 'users.show', params:{user_uuid: user.user_uuid}}">
      <img :src="user.user_avatar" class="avatar-40" :alt="user.name">
    </router-link>
    <div class="ml-2">
      <div>
        <router-link tag="a" :to="{name: 'users.show', params: {user_uuid: user.user_uuid}}">
          <h5 class="mb-0 text-gray-50 d-inline">{{ user.name }}</h5>
        </router-link>
        <router-link
          tag="a"
          class="ml-1 text-muted text-12"
          :to="{name: 'users.show', params: {user_uuid: user.user_uuid}}"
        >@{{ user.nick_name }}</router-link>
      </div>
      <div class="text-12 text-gray-70">{{ user.basic_extends.user_introduction }}</div>
    </div>
    <slot name="appends" :data="user">
      <follow-btn :item="user" simple class="ml-auto"></follow-btn>
    </slot>
  </div>
</template>

<script>
import FollowBtn from '$components/buttons/follow-btn'
export default {
  name: 'user-list-item',
  components: { FollowBtn },
  props: {
    user: {
      type: Object
    }
  }
}
</script>

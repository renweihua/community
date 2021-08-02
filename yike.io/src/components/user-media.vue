<template>
  <div class="user-media d-flex" v-if="type == 'default'">
    <router-link :to="{name:'users.threads', params: {user_uuid: user.user_uuid ? user.user_uuid : user.user_id}}">
      <img :src="user.user_avatar ? user.user_avatar : ''" class="avatar-40" :alt="user.nick_name ? user.nick_name : ''" />
    </router-link>
    <div class="ml-2">
      <div>
        <router-link :to="{name:'users.threads', params: {user_uuid: user.user_uuid ? user.user_uuid : user.user_id}}">
          <slot name="name">
            <h6 class="mb-0 text-16 d-inline-block" :class="nameClasses">{{ user.nick_name ? user.nick_name : user.user_uuid}}</h6>
          </slot>
          <slot name="name-appends"></slot>
        </router-link>
      </div>
      <slot name="description">
        <div class="text-12 text-gray-70">{{ user.basic_extends.user_introduction ? user.basic_extends.user_introduction : '' }}</div>
      </slot>
    </div>
    <slot name="appends" :data="user"></slot>
  </div>

  <div class="user-media text-center d-inline-block p-1" v-else>
    <router-link :to="{name:'users.threads', params: {user_uuid: user.user_uuid ? user.user_uuid : user.user_id}}">
      <img :src="user.user_avatar ? user.user_avatar : ''" class="avatar-40" :alt="user.nick_name ? user.nick_name : ''">
      <slot></slot>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'UserMedia',
  props: {
    type: {
      type: String,
      default: 'default'
    },
    user: {
      type: Object
    },
    nameClasses: {
      type: String,
      default: 'text-black-50 text-14'
    }
  }
}
</script>

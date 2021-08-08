<template>
    <div class="notification-item">
        <div class="heading d-flex align-items-center flex-wrap">
            <router-link :to="{name: 'users.threads', params: {user_uuid: notification.sender.user_uuid}}" class="mr-1" v-if="notification.sender">
                <img class="rounded-circle mr-1" width="30" :src="notification.sender.user_avatar">
                {{ notification.sender.nick_name }}
            </router-link>
            <div class="d-inline">
                <slot name="title">评论了你的文章</slot>
            </div>
            <small class="date text-muted ml-lg-auto text-mini">{{ notification.time_formatting }}</small>
        </div>
        <div class="notification-body text-muted">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            notification: {
                type: Object,
                required: true
            }
        },
        name: 'notification'
    }
</script>

<style lang="scss" scoped>
    .notification-body {
        a {
            color: #6c757d !important;
        }
    }
</style>

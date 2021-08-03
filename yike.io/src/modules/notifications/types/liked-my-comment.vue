<template>
    <notification :notification="notification">
        <template slot="title">赞了你对文章
            <router-link :to="{name: 'threads.show', params: {dynamic_id: notification.relation.dynamic_id}}">
                《{{ notification.relation.dynamic_title }}》
            </router-link>
            的评论
        </template>
        <div class="pt-2 pl-md-4">
            <router-link v-if="notification.comment && notification.comment.comment_content"
                         :to="{name: 'threads.show', params: {dynamic_id: notification.relation.dynamic_id}}"
                         class="text-muted" v-html="notification.comment.comment_content">
            </router-link>
            <span v-else class="red"> 评论内容已删除！ </span>
        </div>
    </notification>
</template>

<script>
    import Notification from './notification';

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

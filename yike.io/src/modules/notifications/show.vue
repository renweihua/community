<template>
    <div class="page-notification-show">
        <div class="row">
            <div class="col-md-3">
                <div class="box mb-2">
                    <div class="box-heading">
                        <bell-icon class="mr-1 text-16"/>
                        通知
                    </div>
                    <div class="nav flex-lg-column nav-pills">
                        <a href="javascript:void(0);" class="nav-link" v-for="tab,id of tabs"
                           :class="{active: currentTab == id}" @click="currentTab = id">{{ tab }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-flush">
                    <ul class="list-group list-group-flush" v-if="notifications.length > 0">
                        <li class="list-group-item">
                            {{ tabs[currentTab] }}
                        </li>
                        <li class="list-group-item list-group-item-action" v-for="notification in notifications"
                            :key="notification.notify_id" :class="{'bg-gray-98': notification.is_read == 0}">
                            <keep-alive>
                                <component :is="getNotificationType(notification)"
                                           :notification="notification"></component>
                            </keep-alive>
                        </li>
                    </ul>
                    <div class="text-center text-gray-50" v-else>
                        <empty-state message="没有新的消息哦~">
                            <template slot="icon">
                                <inbox-icon></inbox-icon>
                            </template>
                        </empty-state>
                    </div>
                    <paginator :meta="paginator_data" @change="handlePageChanged"></paginator>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BellIcon from '$icons/Bell'
    import InboxIcon from '$icons/Inbox'
    import CheckIcon from '$icons/Check'
    import EmptyState from '$components/empty-state'

    import NewFollower from './types/new-follower'
    import CommentMyThread from './types/comment-my-thread'
    import LikedMyThread from './types/liked-my-thread'
    import LikedMyComment from './types/liked-my-comment'
    import MentionedMe from './types/mentioned-me'
    import SubscribedMyThread from './types/subscribed-my-thread'
    import SubscribedTopic from './types/subscribed-topic'
    import Welcome from './types/welcome';
    import Paginator from '$components/paginator';

    export default {
        components: {
            Paginator,
            EmptyState,
            MentionedMe,
            BellIcon,
            InboxIcon,
            CheckIcon,
            NewFollower,
            CommentMyThread,
            LikedMyThread,
            LikedMyComment,
            SubscribedMyThread,
            SubscribedTopic,
            Welcome
        },
        data () {
            return {
                tabs: {
                    all: '全部 ',
                    follow: '关注',
                    comment: '评论',
                    subscribe: '订阅',
                    like: '点赞'
                },
                currentTab: 'all',
                notifications: [],
                paginator_data: {
                }
            }
        },
        watch: {
            currentTab () {
                this.loadNotifications(1)
            }
        },
        created () {
            this.loadNotifications()
        },
        methods: {
            loadNotifications (page = 1) {
                this.$router.push({
                    name: 'notifications.show',
                    query: { tab: this.currentTab }
                })

                this.$http
                    .get(`/getNotify?tab=${this.currentTab}&page=${page}`)
                    .then(res => {
                        console.log(res.data.data);
                        this.paginator_data = res.data;
                        this.notifications = res.data.data;
                    })
            },
            handlePageChanged (page) {
                this.loadNotifications(page)
            },
            // 获取消息类型
            getNotificationType(notification){
                let type = '';
                switch(notification.target_type){
                    case 1: // 动态
                        switch(notification.dynamic_type){
                            case 0: // 点赞
                                type = 'liked-my-thread';
                                break;
                            case 2: // 评论
                                type = 'comment-my-thread';
                                break;
                            case 5: // 点赞评论
                                type = 'liked-my-comment';
                                break;
                        }
                        break;
                    case 2: // 关注会员
                        type = 'new-follower';
                        break;
                    case 3: // 订阅话题
                        type = 'subscribed-topic';
                        break;
                }
                return type;
            }
        }
    }
</script>

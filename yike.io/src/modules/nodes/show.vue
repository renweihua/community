<template>
    <div class="page-node-show">
        <header class="page-header bg-grey-blue py-3 text-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <h1>{{ node.topic_name }}</h1>
                        <p>{{ node.topic_description }}</p>
                    </div>
                    <div class="col-md-2 d-flex justify-content-end">
                        <subscribe-btn relation="node" :item="node"/>
                    </div>
                </div>
            </div>
        </header>
        <div class="container">
            <div class="row mt-3">
                <div class="col-md-9">
                    <div class="box box-flush">
                        <div class="box-body">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link" :class="{active: currentThreadsTab == 'default'}"
                                       href="javascript:;" @click="currentThreadsTab = 'default'">活跃</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" :class="{active: currentThreadsTab == 'featured'}"
                                       href="javascript:;" @click="currentThreadsTab = 'featured'">精选</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" :class="{active: currentThreadsTab == 'zeroComment'}"
                                       href="javascript:;" @click="currentThreadsTab = 'zeroComment'">零回复</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" :class="{active: currentThreadsTab == 'recent'}"
                                       href="javascript:;" @click="currentThreadsTab = 'recent'">最新发布</a>
                                </li>
                            </ul>
                        </div>

                        <threads-list :threads="threads[currentThreadsTab]"
                                      @page-changed="handlePageChanged"></threads-list>
                    </div>
                </div>
                <div class="col-md-3">
                    <hot-tags></hot-tags>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import HotTags from '$components/hot-tags'
    import ThreadsList from '$components/threads-list'
    import SubscribeBtn from '$components/buttons/subscribe-btn'

    export default {
        components: { SubscribeBtn, HotTags, ThreadsList },
        data () {
            return {
                node: {},
                threads: {
                    default: {},
                    featured: {},
                    zeroComment: {},
                    recent: {}
                },
                currentThreadsTab: 'default'
            }
        },
        computed: {
            ...mapGetters(['currentUser'])
        },
        beforeRouteUpdate (to, from, next) {
            if (to.params.topic_id != from.params.topic_id) {
                this.getNode(to.params.topic_id)
                this.loadThreads(to.params.topic_id)
            }

            next()
        },
        created () {
            this.getNode(this.$route.params.topic_id)
            this.loadThreads(this.$route.params.topic_id)
        },
        watch: {
            currentThreadsTab () {
                this.loadThreads(this.$route.params.topic_id, 1)
            }
        },
        methods: {
            loadThreads (topic_id, page = 1) {
                this.$http
                    .get(`topic/dynamics?topic_id=${topic_id}&tab=${this.currentThreadsTab}&page=${page}`)
                    .then(({ data }) => {
                        console.log(data)
                        this.threads[this.currentThreadsTab] = data
                    })
            },
            handlePageChanged (page) {
                this.loadThreads(this.node.topic_id, page)
            },
            getNode (topic_id) {
                this.$http.get(`topic/detail?topic_id=${topic_id}`).then(data => {
                    this.node = data.data
                })
            }
        }
    }
</script>

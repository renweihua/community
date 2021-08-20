<template>
    <div class="page-threads-show pb-4" v-if="thread">
        <div class="row">
            <div class="col-md-9">
                <article class="box box-flush">
                    <header class="thread-header box-body d-flex justify-content-between align-items-center">
                        <user-media :user="thread.user_info">
                            <small class="text-muted" slot="description">发布于 {{ thread.time_formatting }}</small>
                        </user-media>
                    </header>
                    <div class="thread-content box-body text-gray-40 text-16">
                        <header>
                            <h2 class="mb-3 pb-2 border-bottom">{{ thread.dynamic_title }}</h2>
                        </header>

                        <!-- 多图轮播展示 -->
                        <div class="album" v-if="thread.dynamic_type != 2">
                            <slider ref="slider" :options="sliderinit">
                                <slideritem v-for="(item,index) in albumData" :key="index" v-html="item.html"></slideritem>
                            </slider>
                        </div>

                        <!-- 视频动态  -->
                        <video v-if="thread.dynamic_type == 2" :preload="preload" :poster="thread.dynamic_images[0]" :height="height" align="center" :controls="controls" :autoplay="autoplay">
                            <source :src="thread.video_path" type="video/mp4">
                        </video>
                        <!-- 图文或动态 -->
                        <markdown-body v-else v-model="thread.dynamic_content"></markdown-body>
                    </div>
                    <div class="thread-stats-bar bg-white border-top py-1">
                        <div class="container">
                            <ul class="nav align-items-center">
                                <li class="nav-item">
                                    <like-btn relation="thread" :item="thread"></like-btn>
                                </li>
                                <li class="nav-item">
                                    <a class="text-gray-50 btn btn-sm btn-link" href="#comments">
                                        <comment-icon></comment-icon>
                                        {{ thread.cache_extends.comments_count }} 条评论
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <share-dropdown>
                                        <a class="text-gray-50 btn btn-sm btn-link">
                                            <share-icon></share-icon>
                                            分享
                                        </a>
                                    </share-dropdown>
                                </li>
                                <li class="nav-item">
                                    <button
                                        type="button"
                                        class="text-gray-50 btn btn-sm btn-link"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        <more-icon></more-icon>
                                    </button>
                                    <div class="dropdown-menu">
                                        <template v-if="canEdit">
                                            <template v-if="currentUser.is_admin">
                                                <button
                                                    class="dropdown-item"
                                                    type="button"
                                                    @click="toggleStatus('excellent_at')"
                                                >
                                                    <medal-icon class="mr-1"></medal-icon>
                                                    {{ thread.excellent_at ? '取消精华' : '精华' }}
                                                </button>
                                                <button
                                                    class="dropdown-item"
                                                    type="button"
                                                    @click="toggleStatus('pinned_at')"
                                                >
                                                    <top-icon class="mr-1"></top-icon>
                                                    {{ thread.pinned_at ? '取消置顶' : '置顶' }}
                                                </button>
                                                <button
                                                    class="dropdown-item"
                                                    type="button"
                                                    @click="toggleStatus('banned_at')"
                                                >
                                                    <lock-icon class="mr-1"></lock-icon>
                                                    {{ thread.banned_at ? '取消冻结' : '冻结' }}
                                                </button>
                                            </template>
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                @click="$router.push({name:'threads.edit', params:{dynamic_id: thread.dynamic_id}})"
                                            >
                                                <pencil-icon class="mr-1"></pencil-icon>
                                                编辑
                                            </button>
                                            <button
                                                class="dropdown-item text-danger"
                                                type="button"
                                                @click="handleDelete(thread)"
                                            >
                                                <delete-icon class="mr-1"></delete-icon>
                                                删除
                                            </button>
                                        </template>
                                        <button
                                            class="dropdown-item cursor-pointer"
                                            type="button"
                                            @click="showReportForm = true"
                                        >
                                            <alert-box-icon class="mr-1"></alert-box-icon>
                                            举报
                                        </button>
                                    </div>
                                </li>
                                <!-- 动态订阅，暂无此功能
                                <li class="nav-item ml-auto">
                                  <subscribe-btn relation="thread" :item="thread"/>
                                </li>
                                -->
                            </ul>
                        </div>
                    </div>
                    <div class="thread-author-card border-top p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="user-info d-flex align-items-center">
                                <router-link
                                    :to="{name: 'users.threads', params: {user_uuid: thread.user_info ? thread.user_info.user_uuid : ''}}">
                                    <img :src="thread.user_info.user_avatar" alt="User avatar" class="avatar-60">
                                </router-link>
                                <div class="p-2">
                                    <router-link
                                        :to="{name: 'users.threads', params: {user_uuid: thread.user_info ? thread.user_info.user_uuid : ''}}">
                                        <h3 class="text-gray-50 text-14">{{ thread.user_info.nick_name }}</h3>
                                    </router-link>
                                    <div class="text-12 text-muted">{{ thread.user_info.basic_extends.user_introduction
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div class="right-action">
                                <follow-btn :item="thread.user_info"></follow-btn>
                            </div>
                        </div>
                    </div>
                </article>
                <div class="thread-comments mt-3">
                    <comments object-type="App\Thread" :object-id="thread.dynamic_id" @created="loadThread"></comments>
                </div>
                <div class="thread-toolbar">
                    <animate-action :item="thread"/>
                    <share-action class="mt-3" :item="thread"/>
                </div>
            </div>
            <div class="col-md-3 position-relative">
                <user-profile-card class="user-profile-card" :user="thread.user_info"></user-profile-card>
                <user-list-card title="他们觉得很赞" :users="praise_users" class="mt-2"/>
                <hot-tags class="mt-2"></hot-tags>
            </div>
        </div>
        <wechat-qrcode/>
        <report-form :visible="showReportForm" @close="showReportForm = false"/>
    </div>
</template>

<script>
    // 视频播放器
    import Video from 'video.js';
    import 'video.js/dist/video-js.css';

    import { slider, slideritem } from 'vue-concise-slider'; // 引入slider组件

    import moment from 'moment'
    import MedalIcon from '$icons/Medal'
    import LockIcon from '$icons/LockAlert'
    import TopIcon from '$icons/FormatVerticalAlignTop'
    import PencilIcon from '$icons/Pencil'
    import DeleteIcon from '$icons/Delete'
    import AlertBoxIcon from '$icons/AlertBox'
    import UserMedia from '$components/user-media'
    import HotTags from '$components/hot-tags'
    import UserListCard from '$components/user-list-card'
    import WechatQrcode from '$components/wechat-qrcode'
    import Comments from '$components/comments'
    import StarIcon from '$icons/Star'
    import MoreIcon from '$icons/DotsHorizontal'
    import ShareIcon from '$icons/Share'
    import SubscribeBtn from '$components/buttons/subscribe-btn'
    import FollowBtn from '$components/buttons/follow-btn'
    import LikeBtn from '$components/buttons/like-btn'
    import MarkdownBody from '$components/markdown-body'
    import ReportForm from './report-form'
    import AnimateAction from '$components/buttons/animate-action'
    import ShareAction from '$components/buttons/share-action'
    import UserProfileCard from '$components/user-profile-card'
    import ShareDropdown from '$components/share-dropdown'

    import CommentIcon from '$icons/Comment'
    import ViewIcon from '$icons/Eye'

    import { mapGetters } from 'vuex'

    export default {
        components: {
            UserListCard,
            LikeBtn,
            AnimateAction,
            ShareAction,
            ReportForm,
            SubscribeBtn,
            CommentIcon,
            ViewIcon,
            FollowBtn,
            UserMedia,
            HotTags,
            PencilIcon,
            AlertBoxIcon,
            DeleteIcon,
            ShareIcon,
            StarIcon,
            MoreIcon,
            MedalIcon,
            TopIcon,
            LockIcon,
            MarkdownBody,
            Comments,
            UserProfileCard,
            ShareDropdown,
            WechatQrcode,

            // 相册轮播
            slider,
            slideritem
        },
        data () {
            return {
                thread: null,
                showReportForm: false,
                praise_users: [],

                // 视频播放器
                playStatus: '',
                muteStatus: '',
                isMute: true,
                isPlay: false,
                // width: '820', // 设置视频播放器的显示宽度（以像素为单位）
                height: '500', // 设置视频播放器的显示高度（以像素为单位）
                preload: 'auto', //  建议浏览器是否应在<video>加载元素后立即开始下载视频数据。
                controls: true, // 确定播放器是否具有用户可以与之交互的控件。没有控件，启动视频播放的唯一方法是使用autoplay属性或通过Player API。
                autoplay: '',

                //相册多图：滑动配置[obj]
                albumData:[],
                sliderinit: {
                    currentPage: 0,//当前页码
                    // thresholdDistance: 500,//滑动判定距离
                    thresholdTime: 1000,//滑动判定时间
                    autoplay:3000,//自动滚动[ms]
                    loop:true,//循环滚动
                    direction:'horizontal',//方向设置，水平滚动
                    // direction:'vertical',//方向设置，垂直滚动
                    infinite:1,//无限滚动前后遍历数
                    slidesToScroll:1,//每次滑动项数
                }
            }
        },
        computed: {
            ...mapGetters(['currentUser']),
            canEdit () {
                return this.thread.user_id === this.$user().user_id || this.$user().is_admin
            }
        },
        beforeRouteUpdate (to, from, next) {
            if (to.params.dynamic_id !== from.params.dynamic_id) {
                this.loadThread()
            }

            next()
        },
        methods: {
            loadThread () {
                let that = this;
                this.$http
                    .get(`dynamic/detail?dynamic_id=${this.$route.params.dynamic_id}&include=user,likers`)
                    .then(response => {
                        that.thread = response.data;
                        // 多图轮播展示
                        that.thread.dynamic_images.map(function(item){
                            that.albumData.push({
                                title: item,
                                html: '<div class="slide1"><img src="' + item + '" /></div>'
                            });
                        });
                    })
                    .then(this.registerEventListener)
                    .catch(response => {
                        if (response.status === 404) {
                            this.$message.error('该主题已被删除或锁定！')
                            setTimeout(() => {
                                this.$router.go(-1)
                            }, 1500);
                        }
                    })
                    .then(() => {
                        console.log(this.thread);
                        window.pageUsers = [this.thread.user_info];

                        this.getPraiseUsers(this.thread.dynamic_id);
                    })
            },
            // 点赞的会员列表
            getPraiseUsers (dynamic_id) {
                this.$http
                    .get(`dynamic/getPraises?dynamic_id=${this.$route.params.dynamic_id}`)
                    .then(({ data }) => {
                        this.praise_users = data.data;
                    })
            },
            handleDelete (thread) {
                this.$http.delete(`threads/${thread.dynamic_id}`).then(() => {
                    this.$message.success('已删除！');
                    this.$router.go(-1);
                })
            },
            toggleStatus (timestamp) {
                this.thread[timestamp] = this.thread[timestamp]
                    ? null
                    : moment().format('YYYY-MM-DD HH:mm:ss')
                this.$http.patch(`threads/${this.thread.dynamic_id}`, this.thread).then(() => {
                    this.$message.success('搞定！');
                    this.loadThread();
                })
            }
        },
        mounted () {
            this.loadThread()
        }
    }
</script>


<!-- 相册的图片 -->
<style lang="scss">
    .album img{
        width:100%;
        margin:20px auto;
        max-height: 650px;
    }
    video{
        width: 100%;
    }
    .thread-toolbar {
        position: fixed;
        top: 150px;
        margin-left: -80px;
    }

    .thread-stats-bar {
        position: sticky;
        bottom: 0;
        width: 100%;
        left: 0;

        .material-design-icon {
            font-size: 1.2em;
            bottom: -0.06em;
        }
    }

    .user-profile-card {
        position: sticky;
        top: 20px;
    }

    @media screen and (min-width: 1200px) and (max-width: 1350px) {
        .page-threads-show {
            margin-left: 65px;
        }
    }
</style>

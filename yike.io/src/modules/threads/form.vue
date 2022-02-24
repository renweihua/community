<template>
    <div class="page-threads-show" v-if="ready">
        <div v-if="currentUser.has_banned">
            <user-locked/>
        </div>
        <!-- $user() && !$user().user_info.auth_email -->
        <div v-else-if="false">
            <div class="box-body py-4 text-center">
                <h1 class="display-4 text-gray-40">
                    <alert/>
                </h1>
                您需要先激活`邮箱`以使用此功能，
                <router-link :to="{ name: 'user.account' }" class="nav-item">
                    <a href="javascript:void(0);" class="nav-link"> 立即激活`邮箱` </a>
                </router-link>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-md-9 m-auto">
                <div class="box box-flush">
                    <form>
                        <div class="card">
                            <div class="card-header pt-3 border-bottom-0">
                                <div class="input-group input-group">
                                    <input type="text" ref="title_input" class="form-control form-control-lg"
                                           v-model="form.dynamic_title" placeholder="请在这里输入标题（请精准表达）">
                                </div>
                            </div>
                            <editor v-model="form.dynamic_markdown" :toolbar="false" :options="{maxLines: Infinity}"
                                    placeholder="请使用 Markdown 格式详细并精准的表达，不得少于10个字符~"></editor>
                            <div class="card-footer border-top p-2 d-flex justify-content-between">
                                <div class="left-actions d-flex align-items-center">
                                    <span class="text-muted">公开状态</span>
                                    <div class="dropdown ml-1">
                                        <el-select filterable v-model="form.is_public">
                                            <el-option v-for="item in publics" :key="item.value" :value="item.value"
                                                       :label="item.name"></el-option>
                                        </el-select>
                                    </div>
                                </div>
                                <div class="left-actions d-flex align-items-center">
                                    <span class="text-muted">发布到</span>
                                    <div class="dropdown ml-1">
                                        <el-select filterable v-model="form.topic_id">
                                            <el-option v-for="item in nodes" :key="item.topic_id" :value="item.topic_id"
                                                       :label="item.topic_name"></el-option>
                                        </el-select>
                                    </div>
                                </div>
                                <div class="right-actions">
                                    <button type="button" class="btn btn-primary" :disabled="!formReady"
                                            @click="showCaptcha(false)">立即发布
                                    </button>
                                    <!--
                                    <button type="button" class="btn btn-secondary ml-1" :disabled="!formReady"
                                            @click="submit(true)">保存为草稿
                                    </button>
                                    -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    a.nav-link{
        color: blue;
    }
    .editor-container {
        overflow: auto;
        height: calc(100vh - 370px);
    }
</style>


<script>
    import {mapGetters} from 'vuex'
    import Editor from '$components/editor'
    import UserLocked from '$components/user-locked'
    import localforage from 'localforage'
    import Alert from '$icons/Alert'
    import {Select as ElSelect, Option as ElOption} from 'element-ui'
    import 'element-ui/lib/theme-chalk/select.css'

    export default {
        name: 'thread-form',
        components: {
            Editor,
            Alert,
            ElSelect,
            ElOption,
            UserLocked
        },
        data() {
            return {
                ready: false,
                nodes: [],
                publics: [
                    {
                        'value': 1,
                        'name':'公开',
                    },
                    {
                        'value': 0,
                        'name': '私密',
                    },
                ],
                busing: false,
                form: {
                    dynamic_type: 1,
                    is_public: 1,
                    topic_id: 10,
                    is_draft: true,
                    dynamic_title: '',
                    content_type: 'markdown',
                    dynamic_markdown: '',
                    dynamic_content: '',
                }
            }
        },
        watch: {
            form: {
                deep: true,
                handler() {
                    localforage.setItem('thread.form', this.form)
                }
            }
        },
        computed: {
            ...mapGetters(['currentUser']),
            formReady() {
                return (
                    !this.busing &&
                    this.form.dynamic_title.length >= 3 &&
                    this.form.topic_id > 0 &&
                    this.form.dynamic_markdown &&
                    this.form.dynamic_markdown.length >= 10
                )
            }
        },
        mounted() {
            this.loadNodes();
            if (this.$route.name == 'threads.edit') {
                this.loadThread(this.$route.params.dynamic_id)
                    .then(this.syncFromCache)
                    .then(() => {
                        this.ready = true;
                    })
            } else {
                this.syncFromCache();
                this.ready = true;
            }
            this.$nextTick(() => {
                if (this.ready && this.$refs['title_input']) {
                    this.$refs['title_input'].focus();
                }
            })
        },
        methods: {
            syncFromCache() {
                localforage.getItem('thread.form', (err, form) => {
                    if (!err && typeof form === 'object') {
                        this.form = Object.assign(this.form, form);
                    }
                })
            },
            clearCache() {
                localforage.removeItem('thread.form');
            },
            loadNodes() {
                this.busing = true;
                return this.$http
                    .get('topics')
                    .then(response => {
                        this.nodes = response.data;
                        this.busing = false;
                    })
                    .finally(() => (this.busing = false));
            },
            loadThread(dynamic_id) {
                localforage.removeItem('thread.form');
                return this.$http
                    .get(`dynamic/detail?dynamic_id=${dynamic_id}`)
                    .then(thread => (this.form = Object.assign(this.form, thread.data)));
            },
            showCaptcha(draft) {
                this.submit(draft);
            },
            submit(draft = true) {
                this.form.is_draft = draft;
                this.busing = true;
                let promise = null;
                let isEdit = this.$route.name == 'threads.edit';

                if (isEdit) {
                    promise = this.$http
                        .patch(`dynamic/update/${this.$route.params.dynamic_id}`, this.form);
                } else {
                    promise = this.$http.post('dynamic/push', this.form);
                }

                promise
                    .then(response => {
                        this.$message.success(response.msg);

                        this.$router.replace({
                            name: 'threads.show',
                            params: {dynamic_id: response.data}
                        });

                        this.clearCache();
                    })
                    .catch((e) => {
                        // this.$message.error(e);
                    })
                    .finally(() => (this.busing = false));
            }
        }
    }
</script>

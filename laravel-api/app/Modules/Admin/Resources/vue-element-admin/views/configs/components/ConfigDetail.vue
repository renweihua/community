<template>
    <div class="createPost-container">
        <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container" label-width="90px">
            <sticky :z-index="10" :class-name="'sub-navbar publish'">
                <el-button v-loading="loading" style="margin-left: 10px;" type="success" @click="submitForm">
                    Publish
                </el-button>
            </sticky>

            <div class="createPost-main-container">
                <el-form-item label="配置标题:">
                    <MDinput v-model="postForm.config_title" :maxlength="100" required>
                        Title
                    </MDinput>
                    <span><i class="el-icon-info" />唯一标识</span>
                </el-form-item>

                <el-form-item label="配置名称:">
                    <el-input v-model="postForm.config_name" required placeholder="Please enter the 关键字搜索"/>
                    <span><i class="el-icon-info" />英文字符串</span>
                </el-form-item>

                <el-form-item label="配置分组：">
                    <el-select
                        v-model="postForm.config_group"
                        placeholder="请选择配置分组">
                        <el-option
                            v-for="(item, key) in config_group_list"
                            :key="item.value"
                            :checked="item.value == postForm.config_group"
                            :label="item.name+'('+item.value+')'"
                            :value="item.value"
                        />
                    </el-select>
                    <span><i class="el-icon-info" /> 用于区分展示列表</span>
                </el-form-item>

                <el-form-item label="配置类型：">
                    <el-select
                        v-model="postForm.config_type"
                        placeholder="请选择配置类型">
                        <el-option
                            v-for="(item, key) in config_type_list"
                            :key="item.value"
                            :checked="item.value == postForm.config_type"
                            :label="item.name+'('+item.value+')'"
                            :value="item.value"
                        />
                    </el-select>
                    <span><i class="el-icon-info" /> 系统会根据不同类型解析配置值</span>
                </el-form-item>

                <el-form-item label="配置值（数字）:" v-if="postForm.config_type == 2">
                    <el-input v-model="config_value_number" :rows="1" type="number"
                              autosize placeholder="Please enter the 配置值"/>
                </el-form-item>

                <el-form-item label="配置值（图片）" v-else-if="postForm.config_type == 5">
                    <pan-thumb :image="config_value_image" @click="show=true"/>

                    <el-button
                        type="primary"
                        icon="el-icon-upload"
                        style="position: absolute;bottom: 15px;margin-left: 40px;"
                        @click="openSelectFiles"
                    >
                        选择图标
                    </el-button>
                </el-form-item>

                <el-form-item label="配置值（富文本）:" v-else-if="postForm.config_type == 6">
                    <markdown-editor v-model="config_value_markdown" height="400px" />
                </el-form-item>

                <el-form-item label="配置值（文本）:" v-else>
                    <el-input v-model="config_value_text" :rows="1" type="textarea"
                              autosize placeholder="Please enter the 配置值"/>
                </el-form-item>

                <el-form-item label="配置项:">
                    <el-input v-model="postForm.config_extra" :rows="1" type="textarea"
                              autosize placeholder="Please enter the 配置项"/>
                    <span><i class="el-icon-info" /> 如果是枚举型，需要配置该项！</span>
                </el-form-item>

                <el-form-item label="排序">
                    <el-input v-model.trim="postForm.config_sort" type="number" autocomplete="off"/>
                    <span><i class="el-icon-info" /> 从小到大，升序 </span>
                </el-form-item>

                <el-form-item label="说明/备注">
                    <el-input
                        v-model="postForm.config_remark"
                        :autosize="{maxRows: 200}"
                        type="textarea"
                        placeholder="备注"/>
                    <span><i class="el-icon-info" /> 配置项的具体说明详情！</span>
                </el-form-item>

                <el-form-item label="是否启用：">
                    <el-radio-group v-model="postForm.is_check">
                        <el-radio :label="0" :checked="postForm.is_check == 0 ? 'checked' : ''">禁用</el-radio>
                        <el-radio :label="1" :checked="postForm.is_check == 1 ? 'checked' : ''">启用</el-radio>
                    </el-radio-group>
                </el-form-item>
            </div>
        </el-form>
        <file-select v-if="show_files" ref="file" :batch_select="false" @handleSubmit="selectImageSubmit" />
    </div>
</template>

<script>
    import MarkdownEditor from '@/components/MarkdownEditor'
    import PanThumb from '@/components/PanThumb';
    import MDinput from '@/components/MDinput'
    import Sticky from '@/components/Sticky' // 粘性header组件
    import {getUploadUrl} from '@/api/common';
    import {detail, create, update, getConfigGroupType} from '@/api/configs';
    import FileSelect from '@/components/FilesSelect/index'

    const defaultForm = {
        config_id: 0,
        config_title: '', // 配置标题
        config_name: '', // 配置名称（英文）
        config_group: 0, // 配置分组
        config_type: 0, // 配置类型
        config_value: '', // 配置值
        config_extra: '', // 配置项
        config_sort: 99, // 排序
        config_remark: '', // 备注，说明
        is_check: 0, // 是否弃用
    };

    export default {
        name: 'ConfigDetail',
        components: {
            MDinput, Sticky,
            PanThumb,
            MarkdownEditor,
            FileSelect
        },
        props: {
            isEdit: {
                type: Boolean,
                default: false
            }
        },
        data() {
            const validateRequire = (rule, value, callback) => {
                if (value === '') {
                    this.$message({
                        message: rule.field + '为必传项',
                        type: 'error'
                    })
                    callback(new Error(rule.field + '为必传项'))
                } else {
                    callback();
                }
                return;
            };
            return {
                postForm: Object.assign({}, defaultForm),
                loading: false,
                userListOptions: [],
                rules: {
                    config_title: [{validator: validateRequire}],
                    config_name: [{validator: validateRequire}],
                    config_value: [{validator: validateRequire}],
                },
                tempRoute: {},

                // 图片上传
                upload_url: '',
                show_files: false,

                // 分组与类型
                config_group_list: [],
                config_type_list:[],

                // 配置值的几种情况
                config_value_text: '',
                config_value_number: '',
                config_value_image: '',
                config_value_markdown: '',
            }
        },
        computed: {
            lang() {
                return this.$store.getters.language;
            },
        },
        created() {
            if (this.isEdit) {
                const config_id = this.$route.query && this.$route.query.config_id;
                if (config_id > 0) this.getDetail(config_id);
            }

            // Why need to make a copy of this.$route here?
            // https://github.com/PanJiaChen/vue-element-admin/issues/1221
            this.tempRoute = Object.assign({}, this.$route);

            // 图片上传路径
            this.upload_url = getUploadUrl();
            // 获取分组与类型
            this.getConfigGroupType();
        },
        methods: {
            // 打开文件选择器
            openSelectFiles(){
                this.show_files = true;
                this.$nextTick(() => {
                    this.$refs.file.init();
                });
            },
            // 选择指定文件之后，点击’确认‘，获取到的文件信息
            selectImageSubmit(e){
                this.config_value_image = e.file_url;
            },
            // 获取 配置分组与类型
            async getConfigGroupType(){
                const {data} = await getConfigGroupType();
                this.config_group_list = data.config_group_list;
                this.config_type_list = data.config_type_list;
            },
            // 获取文章详情
            getDetail(config_id) {
                detail(config_id).then(response => {

                    this.postForm = response.data;
                    // 默认展示的封面图、文本、数字、富文本
                    this.config_value_text =
                    this.config_value_number =
                    this.config_value_markdown =
                    this.config_value_image = this.postForm.config_value;

                    // set page title
                    this.setPageTitle();
                }).catch(err => {
                    console.log(err);
                })
            },
            setPageTitle() {
                const title = 'Edit Config';
                document.title = `${title} - ${this.postForm.config_id}`;
            },
            submitForm() {
                this.$refs.postForm.validate(async valid => {
                    if (valid) {
                        this.loading = true;

                        // 按照对应的配置类型对配置值进行赋值
                        switch (this.postForm.config_type) {
                            case 2: // 数字
                                this.postForm.config_value = this.config_value_number;
                                break;
                            case 5: // 图片
                                this.postForm.config_value = this.config_value_image;
                                break;
                            case 6: // 富文本
                                this.postForm.config_value = this.config_value_markdown;
                                break;
                            default:
                                this.postForm.config_value = this.config_value_text;
                                break;
                        }

                        const {msg, status} = this.postForm.config_id > 0 ? await update(this.postForm) : await create(this.postForm);
                        if (status == 1){
                            this.$notify({
                                title: this.postForm.config_id > 0 ? '编辑配置成功' : '新增配置成功',
                                message: this.postForm.config_id > 0 ? '编辑配置成功' : '新增配置成功',
                                type: 'success',
                                duration: 2000,
                            });

                            // 返回文章列表
                            this.$router.push(`/configs`);
                        }else{
                            this.$message({
                                message: msg,
                                type: 'error',
                            });
                        }

                        this.loading = false;
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                })
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import "~@/styles/mixin.scss";

    .createPost-container {
        position: relative;

        .el-form {
            .el-form-item{
                margin-bottom: 40px;
            }
        }

        .createPost-main-container {
            padding: 40px 45px 20px 50px;

            .postInfo-container {
                position: relative;
                @include clearfix;
                margin-bottom: 10px;

                .postInfo-container-item {
                    float: left;
                }
            }
        }

        .word-counter {
            width: 40px;
            position: absolute;
            right: 10px;
            top: 0px;
        }
    }

    .article-textarea ::v-deep {
        textarea {
            padding-right: 40px;
            resize: none;
            border: none;
            border-radius: 0px;
            border-bottom: 1px solid #bfcbd9;
        }
    }

    // 封面图
    .pan-item, .pan-info, .pan-thumb{
        border-radius: 0;
    }
</style>

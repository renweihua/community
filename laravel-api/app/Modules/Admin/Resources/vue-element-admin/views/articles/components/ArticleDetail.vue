<template>
    <div class="createPost-container">
        <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container" label-width="90px">
            <sticky :z-index="10" :class-name="'sub-navbar publish'">
                <SourceUrlDropdown v-model="postForm.article_link"/>
                <el-button v-loading="loading" style="margin-left: 10px;" type="success" @click="submitForm">
                    Publish
                </el-button>
            </sticky>

            <div class="createPost-main-container">
                <el-form-item prop="article_title" label="文章标题">
                    <MDinput v-model="postForm.article_title" :maxlength="100" required>
                        Title
                    </MDinput>
                </el-form-item>

                <el-form-item label="所属栏目">
                    <el-select
                        v-model="postForm.menu_id"
                        clearable
                        :placeholder="select_menu_name"
                        @clear="handleClear"
                        ref="selectUpMenuId"
                    >
                        <el-option hidden key="MenuId" :value="postForm.menu_id" :label="select_menu_name"></el-option>
                        <!-- show-checkbox -->
                        <el-tree
                            node-key="menu_id"
                            :default-checked-keys="[postForm.menu_id]"
                            :data="menus"
                            :props="defaultProps"
                            @node-click="handleNodeClick"
                        >
                        </el-tree>
                    </el-select>
                </el-form-item>

                <el-form-item label="文章标签">
                    <el-select v-model="postForm.label_ids" placeholder="请选择文章标签" multiple>
                        <el-option
                            v-for="item in labels"
                            :key="item.label_id"
                            :label="item.label_name"
                            :value="item.label_id"
                        />
                    </el-select>
                </el-form-item>

                <el-form-item label="文章多图">
                    <el-upload
                        class="upload-demo"
                        action="false"
                        :multiple="true"
                        list-type="picture-card"
                        :auto-upload="false"
                        :file-list="filesList"
                        drag
                        :on-preview="handlePictureCardPreview"
                        :on-remove="handleRemove"
                    >
                    </el-upload>
                    <el-dialog :visible.sync="dialogVisible">
                        <img width="100%" :src="dialogImageUrl" alt="">
                    </el-dialog>

                    <el-button
                        class="margin-top-20"
                        type="primary"
                        icon="el-icon-upload"
                        @click="openSelectFiles"
                    >
                        选择图标
                    </el-button>
                </el-form-item>

                <el-form-item label-width="70px" label="关键字:">
                    <el-input v-model="postForm.article_keywords" :rows="1" type="textarea" class="article-textarea"
                              autosize placeholder="Please enter the 关键字搜索"/>
                </el-form-item>

                <el-form-item label-width="70px" label="描述:">
                    <el-input v-model="postForm.article_description" :rows="1" type="textarea" class="article-textarea"
                              autosize placeholder="Please enter the 文章描述"/>
                    <span v-show="contentShortLength" class="word-counter">{{ contentShortLength }}words</span>
                </el-form-item>

                <el-form-item prop="article_content">
                    <el-tag class="tag-title">
                        文章内容:
                    </el-tag>
                    <markdown-editor v-model="postForm.article_content" height="400px" />
                </el-form-item>

                <el-form-item label="排序" prop="article_sort">
                    <el-input v-model.trim="postForm.article_sort" type="number" autocomplete="off"/>
                </el-form-item>

                <el-form-item label="是否置顶" prop="set_top">
                    <el-radio-group v-model="postForm.set_top">
                        <el-radio :label="0" :checked="postForm.set_top == 0 ? 'checked' : ''">否</el-radio>
                        <el-radio :label="1" :checked="postForm.set_top == 1 ? 'checked' : ''">是</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="是否推荐" prop="is_recommend">
                    <el-radio-group v-model="postForm.is_recommend">
                        <el-radio :label="0" :checked="postForm.is_recommend == 0 ? 'checked' : ''">否</el-radio>
                        <el-radio :label="1" :checked="postForm.is_recommend == 1 ? 'checked' : ''">是</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="是否公开" prop="is_public">
                    <el-radio-group v-model="postForm.is_public">
                        <el-radio :label="0" :checked="postForm.is_public == 0 ? 'checked' : ''">否</el-radio>
                        <el-radio :label="1" :checked="postForm.is_public == 1 ? 'checked' : ''">是</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="是否启用" prop="is_check">
                    <el-radio-group v-model="postForm.is_check">
                        <el-radio :label="0" :checked="postForm.is_check == 0 ? 'checked' : ''">否</el-radio>
                        <el-radio :label="1" :checked="postForm.is_check == 1 ? 'checked' : ''">是</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="文章来源" prop="article_origin">
                    <el-input v-model.trim="postForm.article_origin" type="url" autocomplete="off"/>
                </el-form-item>

                <el-form-item label="文章作者" prop="article_author">
                    <el-input v-model.trim="postForm.article_author" autocomplete="off"/>
                </el-form-item>

            </div>
        </el-form>
    </div>
</template>

<script>
    import MarkdownEditor from '@/components/MarkdownEditor';
    import myUpload from '@/components/Uploads/image/index';
    import batchUploads from '@/components/Uploads/image/batchUploads';
    import PanThumb from '@/components/PanThumb';
    import MDinput from '@/components/MDinput'
    import Sticky from '@/components/Sticky' // 粘性header组件
    import {validURL} from '@/utils/validate'
    import {SourceUrlDropdown} from './Dropdown';
    import {getUploadUrl, uploads} from '@/api/common';

    import {detail, create, update} from '@/api/articles'
    import {getArticleLabelSelect} from "@/api/article_labels";
    import {getMenusSelect} from "@/api/menus";

    import FileSelect from '@/components/FilesSelect/index'
    const defaultForm = {
        article_id: 0,
        article_title: '', // 文章题目
        menu_id: 0, // 分类
        article_content: '', // 文章内容
        article_keywords: '', // 关键字
        article_description: '', // 文章摘要
        article_images: [], // 多图
        article_link: '', // 文章外链
        article_sort: 99, // 排序
        set_top:0, // 是否置顶
        is_recommend:0, // 是否推荐
        is_public:1, // 是否公开
        is_check:1, // 是否启用
        article_origin:'', // 文章来源
        article_author:'', // 文章作者
        label_ids: [], // 文章标签
    };

    export default {
        name: 'ArticleDetail',
        components: {MDinput, Sticky, SourceUrlDropdown,
            'my-upload': myUpload,
            batchUploads,
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
                        message: rule.field + '为必填项',
                        type: 'error'
                    })
                    callback(new Error(rule.field + '为必填项'))
                } else {
                    callback();
                }
                return;
            }
            const validateSourceUri = (rule, value, callback) => {
                if (value) {
                    if (validURL(value)) {
                        callback()
                    } else {
                        this.$message({
                            message: '外链url填写不正确',
                            type: 'error'
                        })
                        callback(new Error('外链url填写不正确'))
                    }
                } else {
                    callback()
                }
            }
            return {
                postForm: Object.assign({}, defaultForm),
                loading: false,
                rules: {
                    article_title: [{validator: validateRequire}],
                    menu_id: [{validator: validateRequire}],
                    article_content: [{validator: validateRequire}],
                    article_link: [{validator: validateSourceUri, trigger: 'blur'}]
                },
                tempRoute: {},

                // 图片上传
                upload_url: '',

                labels: [], // 标签

                checkCategories:[], // 默认选中的分类
                // 菜单栏目
                menus:[],
                default_select_menu_name: '请选择所属栏目',
                select_menu_name: '请选择所属栏目',
                defaultProps: {
                    children: '_child',
                    label: 'menu_name'
                },

                // 多图的数量
                image_limit:3,
                // 测试
                filesList: [],
                // 预览图片
                dialogImageUrl: '',
                dialogVisible: false,
                disabled: false
            }
        },
        computed: {
            contentShortLength() {
                return this.postForm.article_description?.length;
            },
            lang() {
                return this.$store.getters.language;
            },
        },
        created() {
            let _this = this;
            // 因为下拉tree是需要先获取数据，进行数据对比，拿到当前选中的值，所以需要：获取文章详情之后，再去获取文章分类。
            if (this.isEdit) {
                const article_id = this.$route.query && this.$route.query.article_id;
                if (article_id > 0){
                    this.getDetail(article_id, function () {
                        // 菜单栏目列表
                        _this.getMenusSelect(function () {
                            try{
                                _this.menus.forEach(item => {
                                    if(item.menu_id == _this.postForm.menu_id){
                                        _this.select_menu_name = item.menu_name;
                                        throw new Error("end……对比成功");//报错，就跳出循环
                                    }else{
                                        item._child && item._child.forEach(child => {
                                            if(child.menu_id == _this.postForm.menu_id){
                                                _this.select_menu_name = child.menu_name;
                                                throw new Error("end……对比成功");//报错，就跳出循环
                                            }
                                        });
                                    }
                                });
                            }catch (e) {
                                // console.log(e);
                            }
                        });
                    });
                }else{
                    // 菜单栏目列表
                    this.getMenusSelect();
                }
            }else{
                // 菜单栏目列表
                this.getMenusSelect();
            }

            // Why need to make a copy of this.$route here?
            // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
            // https://github.com/PanJiaChen/vue-element-admin/issues/1221
            this.tempRoute = Object.assign({}, this.$route);

            // 图片上传路径
            this.upload_url = getUploadUrl();
            // 文章标签列表
            this.getArticleLabelSelect();
        },
        methods: {
            handleRemove(file) {
                let that = this;
                that.postForm.article_images = [];
                that.filesList.forEach(function (val, index) {
                    if (val.url == file.url){
                        // 再次点击则移除选中
                        that.filesList.splice(index, 1);
                        throw new Error('移除完毕');
                    }
                });
                that.filesList.forEach(function (val, index) {
                    that.postForm.article_images.push(val.url);
                });
            },
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            // 打开文件选择器
            openSelectFiles() {
                this.show_files = true;
                this.$nextTick(() => {
                    this.$refs.file.init();
                });
            },
            // 选择指定文件之后，点击’确认‘，获取到的文件信息
            selectImageSubmit(e) {
                let val;
                for (var i = 0; i < e.length; i++) {
                    val = e[i];
                    this.postForm.article_images.push(val.file_url);
                    this.filesList.push({
                        'name': val.file_name,
                        'url': val.file_url,
                    });
                }

                // 验证文件数量
                if (this.postForm.article_images.length > this.image_limit){
                    this.postForm.article_images = this.postForm.article_images.slice(this.postForm.article_images.length - this.image_limit);
                    this.filesList = this.filesList.slice(this.filesList.length - this.image_limit);
                }
            },
            // 获取文章标签列表
            async getArticleLabelSelect(){
                const res = await getArticleLabelSelect();
                this.labels = res.data;
            },
            // 获取文章详情
            getDetail(article_id, callback) {
                detail(article_id).then(response => {
                    this.postForm = Object.assign(this.postForm, response.data);

                    // 文章内容
                    this.postForm.article_content = this.postForm.content.article_content;

                    if (callback) callback();

                    // 多图
                    for (var i = 0; i < this.postForm.article_images.length; i++){
                        this.filesList.push({
                            'name': this.postForm.article_images[i],
                            'url': this.postForm.article_images[i],
                        });
                    }

                    if (this.postForm.labels){
                        this.postForm.label_ids = [];
                        for (const key in this.postForm.labels) {
                            this.postForm.label_ids.push(this.postForm.labels[key].label_id);
                        }
                    }

                    // 选中的分类
                    this.checkCategories.push(this.postForm.menu_id);

                    // set page title
                    this.setPageTitle();
                }).catch(err => {
                    console.log(err);
                })
            },
            // 获取菜单栏目列表
            async getMenusSelect(callback) {
                console.log('getMenusSelect')
                const res = await getMenusSelect();
                this.menus = res.data;
                if (callback) callback();
            },
            // 节点点击事件
            handleNodeClick(data) {
                // 这里主要配置树形组件点击节点后，设置选择器的值；自己配置的数据，仅供参考
                this.select_menu_name = data.menu_name;
                this.postForm.menu_id = data.menu_id;

                // 选择器执行完成后，使其失去焦点隐藏下拉框的效果
                this.$refs.selectUpMenuId.blur();
            },
            // 选择器配置可以清空选项，用户点击清空按钮时触发
            handleClear() {
                // 将选择器的值置空
                this.select_menu_name = this.default_select_menu_name;
                this.postForm.menu_id = -1;
            },
            setTagsViewTitle() {
                const title = this.lang === 'zh' ? '编辑文章' : 'Edit Article';
                const route = Object.assign({}, this.tempRoute, {title: `${title}-${this.postForm.article_id}`});
                this.$store.dispatch('tagsView/updateVisitedView', route);
            },
            setPageTitle() {
                const title = 'Edit Article';
                document.title = `${title} - ${this.postForm.article_id}`;
            },
            submitForm() {
                this.$refs.postForm.validate(async valid => {
                    if (valid) {
                        this.loading = true;

                        const {msg, status} = this.postForm.article_id > 0 ? await update(this.postForm) : await create(this.postForm);
                        if (status == 1){
                            this.$notify({
                                title: '成功',
                                message: this.postForm.article_id > 0 ? '编辑文章成功' : '发布文章成功',
                                type: 'success',
                                duration: 2000,
                            });

                            // 返回文章列表
                            this.$router.push(`/articles`);
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
</style>

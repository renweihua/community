<template>
    <el-dialog
            :title="title"
            :visible.sync="dialogFormVisible"
            width="800px"
            @close="close"
    >
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
            <el-form-item label="版本名称" prop="version_name">
                <el-input v-model.trim="form.version_name" autocomplete="off"/>
            </el-form-item>

            <el-form-item label="版本号" prop="version_number">
                <el-input v-model.trim="form.version_number" autocomplete="off"/>
            </el-form-item>

            <el-form-item label="发布时间" class="postInfo-container-item">
                <el-date-picker v-model="form.publish_date" type="datetime" format="yyyy-MM-dd HH:mm:ss" placeholder="Select date and time" @change="changetime" />
            </el-form-item>

            <el-form-item label="版本内容" prop="version_content">
                <markdown-editor v-model="form.version_content" height="400px" />
            </el-form-item>

            <el-form-item label="排序" prop="version_sort">
                <el-input v-model.trim="form.version_sort" type="number" autocomplete="off"/>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="close">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import {create, update} from '@/api/versions';
    import MarkdownEditor from '@/components/MarkdownEditor'

    export default {
        name: 'VersionDetail',
        components: {
            MarkdownEditor
        },
        data() {
            return {
                form: {
                    version_name: '',
                    version_number: '',
                    version_content: '',
                    publish_date: '',
                    version_sort: 0,
                },
                rules: {
                    version_name: [
                        {required: true, trigger: 'blur', message: '请输入版本名称'},
                        {
                            min: 2,
                            max: 20,
                            message: '长度在 2 到 20 个字符',
                            trigger: 'blur'
                        }
                    ],
                    version_number: [
                        {required: true, trigger: 'blur', message: '请输入版本号'},
                        {
                            min: 2,
                            max: 20,
                            message: '长度在 2 到 20 个字符',
                            trigger: 'blur'
                        }
                    ],
                },
                title: '',
                dialogFormVisible: false,
            }
        },
        created() {

        },
        methods: {
            changetime(){
                this.form.publish_date = this.moment(this.form.publish_date).format("YYYY-MM-DD HH:mm:ss");
            },
            toggleShow() {
                this.show = !this.show;
            },
            showEdit(row) {
                const detail = Object.assign({}, row);
                if (!detail) {
                    this.title = '添加';
                } else {
                    this.title = '编辑';
                    this.form = Object.assign(this.form, detail);
                }
                this.dialogFormVisible = true
            },
            close() {
                this.$refs['form'].resetFields();
                this.form = this.$options.data().form;
                this.dialogFormVisible = false;
            },
            save() {
                this.$refs['form'].validate(async (valid) => {
                    if (valid) {
                        const {msg, status} = this.form.version_id ? await update(this.form) : await create(this.form);
                        this.$message({
                            message: msg,
                            type: status == 1 ? 'success' : 'error',
                        });
                        this.$emit('fetchData');
                        this.close();
                    } else {
                        return false;
                    }
                })
            }
        }
    }
</script>

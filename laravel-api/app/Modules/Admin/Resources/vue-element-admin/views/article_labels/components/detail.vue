<template>
    <el-dialog
            :title="title"
            :visible.sync="dialogFormVisible"
            width="500px"
            @close="close"
    >
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
            <el-form-item label="标签名称">
                <el-input v-model.trim="form.label_name" autocomplete="off"/>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="close">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import {create, update} from '@/api/article_labels';

    export default {
        name: 'articleLabelDetail',
        components: {},
        data() {
            return {
                form: {
                    label_id: 0,
                    label_name: '',
                },
                rules: {
                    label_name: [
                        {required: true, trigger: 'blur', message: '请输入标签名称'},
                        {
                            min: 2,
                            max: 200,
                            message: '长度在 2 到 200 个字符',
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
            toggleShow() {
                this.show = !this.show
            },
            showEdit(row) {
                const detail = Object.assign({}, row);
                if (!detail) {
                    this.title = '添加';
                } else {
                    this.title = '编辑';
                    this.form = Object.assign(this.form, detail);
                }
                this.dialogFormVisible = true;
            },
            close() {
                this.$refs['form'].resetFields();
                this.form = this.$options.data().form;
                this.dialogFormVisible = false;
            },
            save() {
                this.$refs['form'].validate(async (valid) => {
                    if (valid) {
                        const {msg, status} = this.form.label_id ? await update(this.form) : await create(this.form);
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

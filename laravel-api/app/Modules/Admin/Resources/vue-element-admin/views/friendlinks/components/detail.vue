<template>
    <el-dialog
            :title="title"
            :visible.sync="dialogFormVisible"
            width="500px"
            @close="close"
    >
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
            <el-form-item label="站点名称" prop="link_name">
                <el-input v-model.trim="form.link_name" autocomplete="off"/>
            </el-form-item>
            <el-form-item label="站点图标" prop="link_cover">
                <pan-thumb :image="form.link_cover" />

                <el-button
                    type="primary"
                    icon="el-icon-upload"
                    style="position: absolute;bottom: 15px;margin-left: 40px;"
                    @click="openSelectFiles"
                >
                    选择图标
                </el-button>
            </el-form-item>
            <el-form-item label="站点网址" prop="link_url">
                <el-input v-model.trim="form.link_url" autocomplete="off"/>
            </el-form-item>
            <el-form-item label="排序" prop="link_sort">
                <el-input v-model.trim="form.link_sort" type="number" autocomplete="off"/>
            </el-form-item>
            <el-form-item label="是否启用" prop="is_check">
                <el-radio-group v-model="form.is_check">
                    <el-radio :label="0" :checked="form.is_check == 0 ? 'checked' : ''">禁用</el-radio>
                    <el-radio :label="1" :checked="form.is_check == 1 ? 'checked' : ''">启用</el-radio>
                </el-radio-group>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="close">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
        </div>
        <file-select v-if="show_files" ref="file" :batch_select="false" @handleSubmit="selectImageSubmit" />
    </el-dialog>
</template>

<script>
    import {create, update} from '@/api/friendlinks'
    import {getUploadUrl} from '@/api/common'
    import {isUrl} from '@/utils/validate'

    import PanThumb from '@/components/PanThumb'
    import FileSelect from '@/components/FilesSelect/index'

    // 定义一个全局的变量，谁用谁知道
    var validUrl = (rule, value, callback) => {
        if (!isUrl(value)) {
            callback(new Error('请输入正确的网址'))
        } else {
            callback()
        }
    }

    export default {
        name: '',
        components: {
            PanThumb,
            FileSelect
        },
        data() {
            return {
                form: {
                    link_name: '',
                    link_cover: '',
                    link_url: '',
                    link_sort: 100,
                    is_check: 0
                },
                rules: {
                    link_name: [
                        {required: true, trigger: 'blur', message: '请输入站点名称'},
                        {
                            min: 2,
                            max: 20,
                            message: '长度在 2 到 20 个字符',
                            trigger: 'blur'
                        }
                    ],
                    link_url: [
                        {required: false, trigger: 'blur', message: '请输入站点网址'},
                        {required: false, trigger: 'blur', validator: validUrl, message: '请输入正确的网址'}
                    ]
                },
                title: '',
                dialogFormVisible: false,

                // 图片上传
                upload_url: '',
                show_files: false,
            }
        },
        created() {
            this.upload_url = getUploadUrl();
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
                this.form.link_cover = e.file_url;
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
                        const {msg, status} = this.form.link_id ? await update(this.form) : await create(this.form);
                        this.$message({
                            message: msg,
                            type: status == 1 ? 'success' : 'error',
                        });
                        this.$emit('fetchData');
                        this.close();
                    } else {
                        return false;
                    }
                });
            }
        }
    }
</script>

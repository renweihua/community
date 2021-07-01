<template>
    <el-dialog
            :title="title"
            :visible.sync="dialogFormVisible"
            @close="close"
    >
        <el-form ref="form" :model="form" :rules="rules" label-width="90px">
            <el-form-item label="账户：">
                <el-input
                        v-model.trim="form.admin_name"
                        :autosize="{ minRows: 2, maxRows: 30}"
                        placeholder="管理员账户"
                />
            </el-form-item>
            <el-form-item label="邮箱：">
                <el-input
                    v-model="form.admin_email"
                    :autosize="{ minRows: 2, maxRows: 20}"
                    type="email"
                    placeholder="邮箱"
                />
            </el-form-item>
            <el-form-item label="头像：" prop="admin_head">
                <pan-thumb :image="form.admin_head"/>

                <el-button
                    type="primary"
                    icon="el-icon-upload"
                    style="position: absolute;bottom: 15px;margin-left: 40px;"
                    @click="openSelectFiles"
                >
                    选择图标
                </el-button>
            </el-form-item>
            <el-form-item label="密码：">
                <el-input v-model="form.password" placeholder="登录密码"/>
                <span>
                    已设置密码【再次输入，默认会更改；不输入，则不变动】
                    <br>
                    请设置密码【默认为：123456】
                </span>
            </el-form-item>
            <el-form-item label="授权角色：" prop="role_id" v-if="form.admin_id != 1">
                <el-select
                    v-model="form.role_ids"
                    multiple
                    placeholder="请选择">
                    <el-option
                        v-for="role in roles"
                        :key="role.role_id"
                        :label="role.role_name"
                        :value="role.role_id">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="授权角色：" prop="role_id" v-else>
                <span class="danger-text">超管无需设置角色</span>
            </el-form-item>
            <el-form-item label="是否启用：" prop="is_check">
                <el-radio-group v-model="form.is_check">
                    <el-radio :label="0" :checked="form.is_check == 0 ? 'checked' : ''">禁用</el-radio>
                    <el-radio :label="1" :checked="form.is_check == 1 ? 'checked' : ''">启用</el-radio>
                </el-radio-group>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button type="danger" @click="close">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
        </div>
        <file-select v-if="show_files" ref="file" :batch_select="false" @handleSubmit="selectImageSubmit" />
    </el-dialog>
</template>

<script>
    import {create, update} from '@/api/admins';
    import {validEmail} from '@/utils/validate';
    import {getRolesSelect} from '@/api/admin_roles';

    import PanThumb from '@/components/PanThumb';
    import FileSelect from '@/components/FilesSelect/index';

    // 定义一个全局的变量，谁用谁知道
    var _validEmail = (rule, value, callback) => {
        if (!validEmail(value)) {
            callback(new Error('请输入正确的邮箱'))
        } else {
            callback()
        }
    };

    export default {
        name: '',
        components: {
            PanThumb,
            FileSelect
        },
        data() {
            return {
                form: {
                    admin_name: '',
                    admin_email: '',
                    admin_head: '',
                    password: '',
                    role_id: '',
                    is_check: 0,
                    role_ids: [],
                },
                rules: {
                    admin_name: [
                        {required: true, trigger: 'blur', message: '请输入管理员账户'},
                        {
                            min: 2,
                            max: 20,
                            message: '长度在 2 到 20 个字符',
                            trigger: 'blur'
                        }
                    ],
                    admin_email: [
                        {required: true, trigger: 'blur', message: '请输入管理员邮箱'},
                        {
                            min: 2,
                            max: 20,
                            message: '长度在 2 到 20 个字符',
                            trigger: 'blur'
                        },
                        {required: false, trigger: 'blur', validator: _validEmail, message: '请输入正确的邮箱'}
                    ],
                },
                title: '',
                dialogFormVisible: false,

                // 角色列表
                roles: [],

                // 是否展示图片选择器
                show_files:false,
            }
        },
        created() {
            // 获取菜单列表
            this.getRolesSelect();
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
                this.form.admin_head = e.file_url;
            },
            // 获取菜单列表
            async getRolesSelect() {
                const res = await getRolesSelect();
                this.roles = res.data;
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
                    if (detail.roles){
                        for (const key in detail.roles) {
                            this.form.role_ids.push(detail.roles[key].role_id);
                        }
                    }
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
                        const {msg, status} = this.form.admin_id ? await update(this.form) : await create(this.form);
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


<style scoped>
    .el-form-item>labe{
        width: 100px;
    }
    label.el-checkbox {
        display: block;
    }
    #img-btn{
        position: absolute;bottom: 15px;margin-left: 40px;
    }
</style>

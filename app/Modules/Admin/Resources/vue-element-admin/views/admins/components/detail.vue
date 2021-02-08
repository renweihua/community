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
                <pan-thumb :image="image_url"/>

                <el-button
                    id="img-btn"
                    type="primary"
                    icon="el-icon-upload"
                    @click="show=true"
                >
                    头像
                </el-button>

                <my-upload
                        v-model="show"
                        img-format="png"
                        :size="size"
                        :width="50"
                        :height="50"
                        lang-type="zh"
                        :no-rotate="false"
                        field="file"
                        :url="upload_url"
                        @crop-success="cropSuccess"
                        @crop-upload-success="cropUploadSuccess"
                        @crop-upload-fail="cropUploadFail"
                />
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
    </el-dialog>
</template>

<script>
    import {create, update} from '@/api/admins';
    import {getUploadUrl} from '@/api/common';
    import {validEmail} from '@/utils/validate';
    import {getRolesSelect} from '@/api/admin_roles';

    import myUpload from '@/components/Uploads/image/index';
    import PanThumb from '@/components/PanThumb';

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
            'my-upload': myUpload,
            PanThumb
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

                // 图片上传
                show: false,
                size: 2.1,

                // 图片上传
                upload_url: '',
                image_url: '',
            }
        },
        created() {
            this.upload_url = getUploadUrl();
            // 获取菜单列表
            this.getRolesSelect();
        },
        methods: {
            // 获取菜单列表
            async getRolesSelect() {
                const res = await getRolesSelect();
                this.roles = res.data;
            },
            toggleShow() {
                this.show = !this.show
            },
            cropSuccess(imgDataUrl, field) {
                // console.log('-------- crop success --------', imgDataUrl, field)
            },
            // 上传成功回调
            cropUploadSuccess(result, field) {
                this.image_url = result.path_url;
                this.form.admin_head = result.data;
            },
            // 上传失败回调
            cropUploadFail(status, field) {
                // console.log('-------- upload fail --------');
                console.log('上传失败状态' + status);
                console.log('field: ' + field)
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
                    // 设置展示的图标
                    this.image_url = this.form.admin_head;
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

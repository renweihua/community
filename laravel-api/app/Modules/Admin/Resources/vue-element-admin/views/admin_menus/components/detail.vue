<template>
    <el-dialog
        :title="title"
        :visible.sync="dialogFormVisible"
        @close="close"
    >
        <el-form ref="form" :model="form" :rules="rules" label-width="105px">
            <el-form-item label="父级菜单" prop="parent_id">
                <el-select v-model="form.parent_id" placeholder="请选择父级" autocomplete="off">
                    <el-option
                        key="0"
                        :checked="0 == form.parent_id || undefined == form.parent_id"
                        label="默认顶级"
                        value="0"
                    />
                    <el-option
                        v-for="item in menus"
                        :key="item.menu_id"
                        :checked="item.menu_id == form.parent_id"
                        :label="item.menu_name"
                        :value="item.menu_id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="菜单名称" prop="menu_name">
                <el-input v-model="form.menu_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Vue路由路径" prop="vue_path">
                <el-input v-model="form.vue_path" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Vue的redirect" prop="vue_redirect">
                <el-input v-model="form.vue_redirect" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="图标" prop="vue_icon">
                <el-input v-model="form.vue_icon" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Vue文件路径" prop="vue_component">
                <el-input v-model="form.vue_component" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Vue的meta" prop="vue_meta">
                <el-input v-model="form.vue_meta" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="外链" prop="external_links">
                <el-input v-model="form.external_links" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="接口路由" prop="api_url">
                <el-input v-model="form.api_url" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="排序" prop="menu_sort">
                <el-input v-model="form.menu_sort" type="number" autocomplete="off" value="99"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="close">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import {create, update, getMenusSelect} from "@/api/admin_menus";

    export default {
        name: "",
        data() {
            return {
                form: {},
                menus:[], // 父级
                rules: {
                    id: [{required: true, trigger: "blur", message: "请输入路径"}],
                },
                title: "",
                dialogFormVisible: false,
            };
        },
        created() {
            this.getMenusSelect();
        },
        methods: {
            // 获取菜单列表
            async getMenusSelect() {
                const res = await getMenusSelect();
                this.menus = res.data;
            },
            showEdit(row) {
                if (!row) {
                    this.title = "添加";
                } else {
                    this.title = "编辑";
                    this.form = Object.assign({}, row);
                }
                this.dialogFormVisible = true;
            },
            close() {
                this.$refs["form"].resetFields();
                this.form = this.$options.data().form;
                this.dialogFormVisible = false;
            },
            save() {
                this.$refs["form"].validate(async (valid) => {
                    if (valid) {
                        const {msg, status} = this.form.menu_id ? await update(this.form) : await create(this.form);

                        switch (status) {
                            case 1:
                                this.$message({
                                    message: msg,
                                    type: 'success'
                                });

                                this.$emit("fetchData");
                                this.close();
                                break;
                            default:
                                this.$message({
                                    message: msg,
                                    type: 'error'
                                });
                                break;
                        }
                    } else {
                        return false;
                    }
                });
            },
        },
    };
</script>

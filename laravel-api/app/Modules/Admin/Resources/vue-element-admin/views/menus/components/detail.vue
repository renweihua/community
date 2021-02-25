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
            <el-form-item label="菜单名称">
                <el-input v-model="form.menu_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="路由">
                <el-input v-model="form.menu_url" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="图标">
                <el-input v-model="form.menu_icon" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="模板类型">
                <el-select v-model="form.menu_tpltype" placeholder="请选择模板类型" autocomplete="off">
                    <el-option
                        key=""
                        :checked="0 == form.menu_tpltype || undefined == form.menu_tpltype"
                        label="---请选择模板类型---"
                        value=""
                    />
                    <el-option
                        v-for="(type, key) in menu_type_list"
                        :key="key"
                        :checked="key == form.menu_tpltype"
                        :label="type"
                        :value="key"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="列表页模板">
                <el-select v-model="form.menu_listtpl" placeholder="请选择列表页模板" autocomplete="off">
                    <el-option
                        key=""
                        :checked="'' == form.menu_listtpl || undefined == form.menu_listtpl"
                        label="---请选择列表页模板---"
                        value=""
                    />
                    <el-option
                        v-for="(view_name, key) in view_lists"
                        :key="view_name"
                        :checked="view_name == form.menu_listtpl"
                        :label="view_name"
                        :value="view_name"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="详情页模板">
                <el-select v-model="form.menu_detailtpl" placeholder="请选择详情页模板" autocomplete="off">
                    <el-option
                        key=""
                        :checked="'' == form.menu_detailtpl || undefined == form.menu_detailtpl"
                        label="---请选择详情页模板---"
                        value=""
                    />
                    <el-option
                        v-for="(view_name, key) in view_lists"
                        :key="view_name"
                        :checked="view_name == form.menu_detailtpl"
                        :label="view_name"
                        :value="view_name"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="关键字:">
                <el-input v-model="form.menu_keywords" :rows="1" type="textarea" class="article-textarea"
                          autosize placeholder="Please enter the 关键字搜索"/>
            </el-form-item>
            <el-form-item label="描述:">
                <el-input v-model="form.menu_description" :rows="1" type="textarea" class="article-textarea"
                          autosize placeholder="Please enter the 文章描述"/>
            </el-form-item>
            <el-form-item label="文章内容:">
                <markdown-editor v-model="form.menu_content" height="400px" />
            </el-form-item>
            <el-form-item label="排序" prop="menu_sort">
                <el-input v-model="form.menu_sort" type="number" autocomplete="off" value="99"></el-input>
            </el-form-item>
            <el-form-item label="是否首页展示">
                <el-radio-group v-model="form.is_show">
                    <el-radio :label="1" :checked="form.is_show == 1 ? 'checked' : ''">展示</el-radio>
                    <el-radio :label="0" :checked="form.is_show == 0 ? 'checked' : ''">隐藏</el-radio>
                </el-radio-group>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="close">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import MarkdownEditor from '@/components/MarkdownEditor';
    import {create, update, getMenusSelect, getTplTypeAndViews} from "@/api/menus";

    export default {
        name: "menuDetail",
        components: {
            MarkdownEditor
        },
        data() {
            return {
                form: {},
                menus:[], // 父级
                rules: {},
                title: "",
                dialogFormVisible: false,
                menu_type_list:[], // 模板类型列表
                view_lists:[], // 模板列表
            };
        },
        created() {
            this.getMenusSelect();
            this.getTplTypeAndViews();
        },
        methods: {
            // 获取菜单列表
            async getMenusSelect() {
                const res = await getMenusSelect();
                this.menus = res.data;
            },
            // 获取模板列表列表
            async getTplTypeAndViews() {
                const res = await getTplTypeAndViews();
                this.menu_type_list = res.data.menu_type_list;
                console.log(this.menu_type_list);
                this.view_lists = res.data.view_lists;
                console.log(this.view_lists);
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

<style>
    .el-dialog{
        width: 1000px!important;
    }
</style>

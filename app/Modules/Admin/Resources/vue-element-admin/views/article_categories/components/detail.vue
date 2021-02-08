<template>
    <el-dialog
        :title="title"
        :visible.sync="dialogFormVisible"
        @close="close"
    >
        <el-form ref="form" :model="form" :rules="rules" label-width="90px">
            <el-form-item label="父级菜单" prop="parent_id">
                <el-select v-model="form.parent_id" placeholder="请选择父级" autocomplete="off">
                    <el-option
                        key="0"
                        :checked="0 == form.parent_id"
                        label="默认顶级"
                        value="0"
                    />
                    <el-option
                        v-for="item in category"
                        :key="item.category_id"
                        :checked="item.category_id == form.parent_id"
                        :label="item.category_name"
                        :value="item.category_id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="分类名称" prop="category_name">
                <el-input v-model="form.category_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="排序" prop="category_sort">
                <el-input v-model="form.category_sort" autocomplete="off" type="number"></el-input>
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
    </el-dialog>
</template>

<script>
    import {create, update, getCategorySelect} from "@/api/article_categories";

    export default {
        name: "",
        data() {
            return {
                title: '',
                category:[], // 分类组
                form: {
                    parent_id: 0,
                    category_name:'',
                    category_sort: 99,
                    is_check:1,
                },
                rules: {
                    category_name: [{required: true, trigger: "blur", message: "请输入分类名称"}],
                },
                dialogFormVisible: false,
            };
        },
        created() {
            this.getCategorySelect();
        },
        methods: {
            // 获取菜单列表
            async getCategorySelect() {
                const res = await getCategorySelect();
                this.category = res.data;
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
                        const {msg, status} = this.form.category_id ? await update(this.form) : await create(this.form);

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

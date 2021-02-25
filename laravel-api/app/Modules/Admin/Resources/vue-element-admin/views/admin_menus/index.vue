<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button
                class="filter-item"
                type="primary"
                icon="el-icon-plus"
                @click="handleEdit"
            >
                {{ $t('table.add') }}
            </el-button>
        </div>

        <el-table
            v-loading="listLoading"
            :data="list"
            :element-loading-text="elementLoadingText"
            row-key="menu_id"
            border
            :tree-props="{ children: '_child', hasChildren: true }"
        >
            <el-table-column
                show-overflow-tooltip
                prop="menu_name"
                label="菜单名称"
            ></el-table-column>

            <el-table-column
                show-overflow-tooltip
                prop="vue_path"
                label="Vue路由"
            ></el-table-column>

            <el-table-column
                show-overflow-tooltip
                prop="vue_component"
                label="vue文件路径"
            ></el-table-column>

            <el-table-column
                show-overflow-tooltip
                prop="api_url"
                label="API路由"
                align="center"
            ></el-table-column>

            <el-table-column show-overflow-tooltip label="是否隐藏" align="center">
                <template slot-scope="{row}">
                    <el-tag :type="row.is_hidden == 1 ? 'danger' : 'success'">
                        <svg-icon :icon-class="row.is_hidden == 1 ? 'eye' : 'eye-open'" />
                        {{ row.is_hidden == 1 ? "隐藏" : "展示" }}
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column
                show-overflow-tooltip
                prop="external_links"
                label="重定向（外链）"
            ></el-table-column>

            <el-table-column
                show-overflow-tooltip
                prop="menu_sort"
                label="排序"
                align="center"
            ></el-table-column>

            <el-table-column
                show-overflow-tooltip
                label="图标"
                align="center"
            >
                <template slot-scope="{row}">
                    <i :class="row.vue_icon"></i>
                </template>
            </el-table-column>

            <el-table-column
                align="center"
                label="操作"
            >
                <template v-slot="{row}">
                    <!-- 状态变更 -->
                    <el-button v-if="row.is_hidden == 0" type="text"
                               @click="changeStatus(row, 1, 'is_hidden')">
                        <el-tag :type="0 | statusFilter">
                            <svg-icon icon-class="eye" />
                            隐藏
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.is_hidden == 1" type="text"
                               @click="changeStatus(row, 0, 'is_hidden')">
                        <el-tag :type="1 | statusFilter">
                            <svg-icon icon-class="eye-open" />
                            展示
                        </el-tag>
                    </el-button>
                    <!-- 编辑与删除 -->
                    <el-button type="text" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <edit ref="edit" @fetchData="getMenus"></edit>
    </div>
</template>

<script>
    import {getList as getMenus, setDel, changeFiledStatus} from "@/api/admin_menus";
    import Edit from "./components/detail";

    export default {
        name: "MenuManagement",
        components: {Edit},
        filters: {
            statusFilter(status) {
                const statusMap = {
                    1: 'success',
                    0: 'danger'
                };
                return statusMap[status];
            },
        },
        data() {
            return {
                defaultProps: {
                    children: "children",
                    label: "label",
                },
                list: [],
                listLoading: true,
                elementLoadingText: "正在加载...",
            };
        },
        async created() {
            this.getMenus();
        },
        methods: {
            handleEdit(row) {
                if (row.menu_id) {
                    this.$refs["edit"].showEdit(row);
                } else {
                    this.$refs["edit"].showEdit();
                }
            },
            handleDelete(row) {
                if (row.menu_id) {
                    this.$confirm(
                        '你确定要删除操作吗？删除之后将无法恢复，请谨慎操作',
                        'Warning',
                        {
                            confirmButtonText: 'Confirm',
                            cancelButtonText: 'Cancel',
                            type: 'warning'
                        })
                        .then(async () => {
                            const {msg} = await setDel({menu_id: row.menu_id});
                            this.$message({
                                message: msg,
                                type: 'success'
                            });
                            this.getMenus();
                        })
                        .catch(err => {
                            console.error(err);
                        });
                }
            },
            async getMenus() {
                this.listLoading = true;
                const {data} = await getMenus();

                this.list = data;
                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            // 状态变更
            async changeStatus(row, value, filed) {
                const {data, msg, status} = await changeFiledStatus({
                    'menu_id': row.menu_id,
                    'change_field': filed,
                    'change_value': value
                });

                // 设置成功之后，同步到当前列表数据
                if (status == 1){
                    row[filed] = value;
                }
                this.$message({
                    message: msg,
                    type: status == 1 ? 'success' : 'error',
                });
            },
        },
    };
</script>

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
            row-key="category_id"
            border
            :tree-props="{ children: '_child', hasChildren: true }"
        >
            <el-table-column
                show-overflow-tooltip
                prop="category_name"
                label="分类名称"
            ></el-table-column>
            <el-table-column
                show-overflow-tooltip
                prop="category_sort"
                label="排序"
                align="center"
            ></el-table-column>
            <el-table-column align="center" prop="is_check" label="启用状态">
                <template slot-scope="{row}">
                    <el-tag :type="row.is_check | statusFilter">
                        <i :class="row.is_check == 1 ? 'el-icon-unlock' : 'el-icon-lock'" />
                        {{ row.is_check | checkFilter }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="创建时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.created_time | parseTime("{y}-{m}-{d} {h}:{i}") }}
                </template>
            </el-table-column>
            <el-table-column
                align="center"
                label="操作"
            >
                <template v-slot="{row}">
                    <!-- 状态变更 -->
                    <el-button v-if="row.is_check == 0" type="text"
                               @click="changeStatus(row, 1)">
                        <el-tag :type="1 | statusFilter">
                            <i class="el-icon-unlock" />
                            启用
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.is_check == 1" type="text"
                               @click="changeStatus(row, 0)">
                        <el-tag :type="0 | statusFilter">
                            <i class="el-icon-lock" />
                            禁用
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
    import {getList as getMenus, changeFiledStatus, setDel} from "@/api/article_categories";
    import Edit from "./components/detail";
    import {parseTime} from "@/utils";

    const calendarCheckOptions = [
        {key: '-1', display_name: '全部'},
        {key: '1', display_name: '启用'},
        {key: '0', display_name: '禁用'}
    ];

    const calendarCheckKeyValue = calendarCheckOptions.reduce((acc, cur) => {
        acc[cur.key] = cur.display_name;
        return acc;
    }, {})

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
            checkFilter(type) {
                return calendarCheckKeyValue[type] || '';
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
                if (row.category_id) {
                    this.$refs["edit"].showEdit(row);
                } else {
                    this.$refs["edit"].showEdit();
                }
            },
            handleDelete(row) {
                if (row.category_id) {
                    this.$confirm(
                        '你确定要删除操作吗？删除之后将无法恢复，请谨慎操作',
                        'Warning',
                        {
                            confirmButtonText: 'Confirm',
                            cancelButtonText: 'Cancel',
                            type: 'warning'
                        })
                        .then(async () => {
                            const {msg, status} = await setDel({category_id: row.category_id});
                            this.$message({
                                message: msg,
                                type: status == 1 ? 'success' : 'error',
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
            async changeStatus(row, value) {
                const {data, msg, status} = await changeFiledStatus({
                    'category_id': row.category_id,
                    'change_field': 'is_check',
                    'change_value': value
                });

                // 设置成功之后，同步到当前列表数据
                if (status == 1) row.is_check = value;
                this.$message({
                    message: msg,
                    type: status == 1 ? 'success' : 'error',
                });
            },
        },
    };
</script>

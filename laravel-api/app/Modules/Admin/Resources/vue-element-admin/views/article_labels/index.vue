<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                    v-model="listQuery.search"
                    placeholder="请输入标签名称"
                    style="width: 200px;"
                    class="filter-item"
                    @keyup.enter.native="handleFilter"
            />
            <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
                {{ $t('table.search') }}
            </el-button>
            <el-button v-waves class="filter-item" type="danger" icon="el-icon-delete" @click="handleDelete">
                {{ $t('table.batchDelete') }}
            </el-button>
            <el-button
                    class="filter-item"
                    style="margin-left: 10px;"
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
                @selection-change="setSelectRows"
                border
                class="margin-buttom-10"
        >
            <el-table-column show-overflow-tooltip type="selection"/>
            <el-table-column
                    show-overflow-tooltip
                    prop="label_id"
                    label="Id"
                    align="center"
            />
            <el-table-column
                    show-overflow-tooltip
                    prop="label_name"
                    label="标签名称"
                    align="center"
            />
            <el-table-column label="创建时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.created_time | parseTime("{y}-{m}-{d} {h}:{i}") }}
                </template>
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="操作"
                    align="center"
            >
                <template v-slot="{row}">
                    <!-- 编辑与删除 -->
                    <el-button type="text" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <!-- 分页 -->
        <el-pagination
                background
                :current-page="listQuery.page"
                :page-size="listQuery.limit"
                :layout="layout"
                :total="total"
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
        />
        <!-- 详情 -->
        <edit ref="edit" @fetchData="getList"/>
    </div>
</template>

<script>
    import {getList, setDel} from '@/api/article_labels';
    import waves from '@/directive/waves' // waves directive
    import Edit from './components/detail'
    import {parseTime, getFormatDate} from '@/utils/index';

    export default {
        name: 'articleLabels',
        components: {Edit},
        directives: {waves},
        filters: {
            parseTime: parseTime,
            getFormatDate: getFormatDate,
        },
        data() {
            return {
                is_batch: 0, // 默认不开启批量删除
                list: [],
                listLoading: true,
                layout: 'total, sizes, prev, pager, next, jumper',
                total: 0,
                selectRows: '',
                elementLoadingText: '正在加载...',
                listQuery: {
                    page: 1,
                    limit: 10,
                    search: '',
                    is_check: ''
                },
                downloadLoading: false,
            }
        },
        created() {
            this.getList();
        },
        methods: {
            setSelectRows(val) {
                this.selectRows = val;
                this.is_batch = 1;
            },
            handleEdit(row) {
                if (row) {
                    this.$refs['edit'].showEdit(row);
                } else {
                    this.$refs['edit'].showEdit({});
                }
            },
            handleDelete(row) {
                var ids = '';
                if (row.label_id) {
                    ids = row.label_id;
                } else {
                    if (this.selectRows.length > 0) {
                        ids = this.selectRows.map((item) => item.label_id).join();
                    } else {
                        this.$message('未选中任何行', 'error');
                        return false
                    }
                }

                // 删除流程
                this.$confirm(
                    '你确定要删除操作吗？删除之后将无法恢复，请谨慎操作',
                    'Warning',
                    {
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                    })
                    .then(async () => {
                        const {status, msg} = await setDel({label_id: ids, 'is_batch' : this.is_batch});

                        switch (status) {
                            case 1:
                                this.getList();

                                this.$message({
                                    type: 'success',
                                    message: msg
                                });
                                break;
                            default:
                                this.$message({
                                    type: 'error',
                                    message: msg
                                });
                                break;
                        }

                    })
                    .catch(err => {
                        console.error(err);
                    })
            },
            handleSizeChange(val) {
                this.listQuery.limit = val;
                this.getList();
            },
            handleCurrentChange(val) {
                this.listQuery.page = val
                this.getList()
            },
            handleFilter() {
                this.listQuery.page = 1
                this.getList()
            },
            async getList() {
                this.listLoading = true;
                const {data} = await getList(this.listQuery);
                this.list = data.data;
                this.total = data.total;
                this.listQuery.limit = data.per_page || 10;
                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
        }
    }
</script>

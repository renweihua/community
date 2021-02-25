<template>
    <div class="app-container">
        <div class="filter-container">
            <el-select class="filter-item width-200-px" v-model="listQuery.admin_id" :remote-method="getSearchAdmins" filterable default-first-option remote placeholder="管理员昵称搜索">
                <el-option
                    key="0"
                    :checked="0 == listQuery.admin_id"
                    label="请指定管理员筛选"
                    value="0"
                />
                <el-option v-for="(item,index) in admins_list" :key="item.admin_name+index" :label="item.admin_name" :value="item.admin_id" />
            </el-select>
            <el-select v-model="listQuery.log_status" placeholder="请选择登录状态" clearable class="filter-item">
                <el-option
                    v-for="item in calendarCheckOptions"
                    :key="item.key"
                    :checked="item.key == listQuery.log_status"
                    :label="item.display_name+'('+item.key+')'"
                    :value="item.key"
                />
            </el-select>
            <el-select v-model="listQuery.search_month" placeholder="请选择指定月份的日志" clearable class="filter-item">
                <el-option
                    v-for="item in month_lists"
                    :key="item"
                    :checked="item == listQuery.search_month"
                    :label="item + '( 的日志)'"
                    :value="item"
                />
            </el-select>
            <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
                {{ $t('table.search') }}
            </el-button>
            <el-button v-waves class="filter-item" type="danger" icon="el-icon-delete" @click="handleDelete">
                {{ $t('table.batchDelete') }}
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
                    prop="log_id"
                    label="Id"
                    align="center"
            />
            <el-table-column align="center" prop="admin" label="管理员">
                <template slot-scope="{ row }">
                    <span v-if="row.admin">
                        Id：{{ row.admin.admin_id }}
                        <br>
                        账户：{{ row.admin.admin_name }}
                    </span>
                </template>
            </el-table-column>
            <el-table-column
                show-overflow-tooltip
                prop="log_method"
                label="请求方式"
                align="center"
            />
            <el-table-column
                show-overflow-tooltip
                prop="log_action"
                label="请求地址"
                align="center"
            />
            <el-table-column
                show-overflow-tooltip
                prop="log_duration"
                label="耗时"
                align="center"
            >
                <template slot-scope="{ row }">
                    {{ row.log_duration }} s
                </template>
            </el-table-column>
            <el-table-column
                show-overflow-tooltip
                prop="created_ip"
                label="IP"
                align="center"
            />
            <el-table-column align="center" prop="log_status" label="请求状态">
                <template slot-scope="{row}">
                    <el-tag :type="row.log_status | statusFilter">
                        <i :class="row.log_status == 1 ? 'el-icon-success' : 'el-icon-error'" />
                        {{ row.log_status | checkFilter }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="登录时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.created_time | parseTime("{y}-{m}-{d} {h}:{i}") }}
                </template>
            </el-table-column>
            <el-table-column
                    label="操作"
                    align="center"
            >
                <template v-slot="{row}">
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
    </div>
</template>

<script>
    import {getList, setDel} from '@/api/adminlogs';
    import {getAdminsSelect} from '@/api/admins';
    import {getMonthLists} from '@/api/common';
    import waves from '@/directive/waves' // waves directive
    import {parseTime, getFormatDate} from '@/utils/index';

    const calendarCheckOptions = [
        {key: -1, display_name: '全部'},
        {key: 1, display_name: '成功'},
        {key: 0, display_name: '失败'}
    ]

    const calendarCheckKeyValue = calendarCheckOptions.reduce((acc, cur) => {
        acc[cur.key] = cur.display_name
        return acc
    }, {})

    export default {
        name: 'UserManagement',
        directives: {waves},
        filters: {
            parseTime: parseTime,
            getFormatDate: getFormatDate,
            statusFilter(status) {
                const statusMap = {
                    1: 'success',
                    0: 'danger'
                }
                return statusMap[status]
            },
            checkFilter(type) {
                return calendarCheckKeyValue[type] || ''
            }
        },
        data() {
            return {
                is_batch: 0, // 默认不开启批量删除
                list: [],
                month_lists:[],
                listLoading: true,
                layout: 'total, sizes, prev, pager, next, jumper',
                total: 0,
                selectRows: '',
                elementLoadingText: '正在加载...',
                listQuery: {
                    page: 1,
                    limit: 10,
                    search: '',
                    log_status: -1,
                    search_month:'',
                    admin_id: '',
                },
                downloadLoading: false,
                calendarCheckOptions,

                admins_list : [], // 管理员列表
            }
        },
        created() {
            this.getList();

            this.getMonthLists();
        },
        methods: {
            // 搜索管理员
            getSearchAdmins(query) {
                getAdminsSelect({'search':query}).then(response => {
                    if (!response.data) return;
                    this.admins_list = response.data;
                })
            },
            checkFilter(val) {
                return calendarCheckKeyValue[val] || '';
            },
            setSelectRows(val) {
                this.selectRows = val;
                this.is_batch = 1;
            },
            handleDelete(row) {
                var ids = '',
                month = '';
                if (row.log_id) {
                    ids = row.log_id;
                    month = parseTime(row.created_time, "{y}-{m}");
                } else {
                    if (this.selectRows.length > 0) {
                        month = parseTime(this.selectRows[0].created_time, "{y}-{m}");
                        ids = this.selectRows.map((item) => item.log_id).join();
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
                        const {status, msg} = await setDel({log_id: ids, 'month' : month, 'is_batch' : this.is_batch});

                        switch (status) {
                            case 1:
                                // this.list.splice($index, 1);
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
                        console.error(err)
                    })
            },
            handleSizeChange(val) {
                this.listQuery.limit = val
                this.getList()
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
                this.listLoading = true
                const {data} = await getList(this.listQuery)
                this.list = data.data
                this.total = data.total
                setTimeout(() => {
                    this.listLoading = false
                }, 300)
            },
            async getMonthLists() {
                const {data} = await getMonthLists();
                this.month_lists = data;
            }
        }
    }
</script>

<template>
    <div class="app-container">
        <aside>
            共计：{{total}}条备份记录

            <el-button
                class="filter-item"
                style="margin-left: 10px;"
                icon="el-icon-right"
                @click="returnDatabase"
            >
                数据表管理
            </el-button>
        </aside>

        <el-table
            v-loading="listLoading"
            :data="list"
            :element-loading-text="elementLoadingText"
            border
            class="margin-buttom-10"
        >
            <el-table-column label="备份的表" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    <span v-if="row.tables_total > 1">（{{row.tables_total}} 个表）</span>
                    {{ row.tables_name }}
                    <!--
                    <div class="table-list" slot="content">
                        <span v-for="(item,key) in row.tables_name" :key="key">
                            <el-tag type="success" v-if="key%2 ==0">
                                {{ item }}
                            </el-tag>
                            <el-tag type="info" v-else>
                                {{ item }}
                            </el-tag>
                        </span>
                    </div>
                    -->
                </template>
            </el-table-column>

            <el-table-column
                show-overflow-tooltip
                prop="file_num"
                label="文件数量"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="file_size"
                label="文件大小"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="created_ip"
                label="操作IP"
                align="center"
            />

            <el-table-column label="创建时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.created_time | parseTime("{y}-{m}-{d} {h}:{i}") }}
                </template>
            </el-table-column>

            <el-table-column align="center" label="操作">
                <template slot-scope="{row}">
                    <!-- 是否下载
                    <el-button type="text" @click="changeStatus(row, 1, 'set_top')">
                        <el-tag type="success">
                            <i class="el-icon-top"/>
                            下载
                        </el-tag>
                    </el-button> -->
                    <!-- 立即回滚
                    <el-button type="text" @click="changeStatus(row, 1, 'is_recommend')">
                        <el-tag type="danger">
                            <i class="el-icon-info"/>
                            立即恢复
                        </el-tag>
                    </el-button> -->
                    <!-- 删除 -->
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(row)"> 删除</el-button>
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
    import {getBackupsList, deleteBackup} from '@/api/databases';
    import waves from '@/directive/waves';
    import {parseTime} from '@/utils/index';

    export default {
        name: 'backupsManage',
        components: {},
        directives: {waves},
        filters: {
            parseTime: parseTime,
        },
        data() {
            return {
                list: [],
                layout: 'total, sizes, prev, pager, next, jumper',
                listLoading: true,
                total: 0,
                elementLoadingText: '正在加载...',
                listQuery: {
                    page: 1,
                    limit: 10,
                    search: '',
                },
            }
        },
        created() {
            this.getList();
        },
        methods: {
            // 返回数据表列表
            returnDatabase(){
                this.$router.push({
                    'path':`/databases`,
                });
            },
            // 列表
            async getList(callback) {
                this.listLoading = true;
                const {data, status, msg} = await getBackupsList(this.listQuery);

                this.list = data.data;
                this.total = data.total;

                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            handleSizeChange(val) {
                this.listQuery.limit = val;
                this.getList();
            },
            handleCurrentChange(val) {
                this.listQuery.page = val;
                this.getList();
            },
            // 删除
            handleDelete(row) {
                var backup_id = 0;
                if (row.backup_id) {
                    backup_id = row.backup_id;
                } else {
                    this.$message('请指定备份记录', 'error');
                    return false;
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
                        const {status, msg} = await deleteBackup({backup_id: backup_id});

                        this.$message({
                            message: msg,
                            type: status == 1 ? 'success' : 'error',
                        });
                        if (status) this.getList();
                    })
                    .catch(err => {
                        console.error(err);
                    })
            },
        }
    }
</script>
<style>
    aside {
        text-align: center;
        background-color: #F0FBFF;
    }
    .table-list span{
        display: flex;
    }
</style>

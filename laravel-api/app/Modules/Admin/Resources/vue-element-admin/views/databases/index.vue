<template>
    <div class="app-container">
        <aside>
            共计：{{backups_total}}条备份日志

            <el-button
                class="filter-item"
                style="margin-left: 10px;"
                icon="el-icon-eye"
                @click="goBackups"
            >
                <svg-icon icon-class="eye-open" />
                立即查看
            </el-button>
        </aside>

        <div class="filter-container">
            <el-input
                v-model="listQuery.search"
                placeholder="请输入 数据表名"
                style="width: 200px;"
                class="filter-item"
                @keyup.enter.native="handleFilter"
            />
            <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
                {{ $t('table.search') }}
            </el-button>

            <el-button
                v-waves
                :loading="backupsLoading"
                class="filter-item"
                type="success"
                icon="el-icon-folder"
                @click="handleBackups"
            >
                {{ $t('table.backups') }}
            </el-button>
        </div>

        <div class="filter-container tagging">
            {{total}} 张表，大小：{{tables_size}}
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
                prop="Name"
                label="表名"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Comment"
                label="表注释"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Engine"
                label="存储引擎"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Rows"
                label="行数"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Data_length"
                label="数据"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Index_length"
                label="索引"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Total_length"
                label="全部"
                align="center"
            />

            <el-table-column label="创建时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.Create_time }}
                </template>
            </el-table-column>

            <el-table-column label="修改时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.Update_time }}
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import {getList, backupsTables} from '@/api/databases';
    import waves from '@/directive/waves'; // waves directive

    export default {
        name: 'databaseManage',
        components: {},
        directives: {waves},
        filters: {},
        data() {
            return {
                list: [],
                listLoading: true,
                // 备份文件数量
                backups_total:0,
                // 表的总量
                total: 0,
                // 表的大小
                tables_size:0,
                selectRows: '',
                elementLoadingText: '正在加载...',
                listQuery: {
                    search: '',
                },
                // 备份的加载动画
                backupsLoading: false
            }
        },
        created() {
            this.getList();
        },
        methods: {
            // 进入备份页面
            goBackups() {
                this.$router.push({
                    'path':`/backups`,
                });
            },
            setSelectRows(val) {
                this.selectRows = val;
            },
            handleFilter() {
                this.getList();
            },
            async getList(callback) {
                this.listLoading = true;
                const {data, status, msg} = await getList(this.listQuery);

                this.list = data.data;
                this.total = data.table_total;
                this.backups_total = data.backups_total;
                this.tables_size = data.tables_size;

                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            // 备份数据表
            async handleBackups() {
                this.listLoading = true;
                this.backupsLoading = true;

                let ids = '';
                if (this.selectRows.length > 0) {
                    ids = this.selectRows.map((item) => item.Name).join();
                }

                const {data, msg, status} = await backupsTables({
                    tables_list: ids
                });

                this.$message({
                    message: msg,
                    type: status == 1 ? 'success' : 'error',
                });

                setTimeout(() => {
                    this.backupsLoading = false;
                    this.listLoading = false;
                }, 300);
            },
        }
    }
</script>
<style>
    aside{
        text-align: center;
        background-color: #F0FBFF;
    }
    .tagging{
        position: absolute;
        top: 117px;
        right: 20px;
        color: #6B6C77;
    }
</style>

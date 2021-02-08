<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="listQuery.search"
                placeholder="请输入 文章标题"
                style="width: 200px;"
                class="filter-item"
                @keyup.enter.native="handleFilter"
            />

            <el-select
                class="filter-item"
                v-model="listQuery.category_id"
                clearable
                :placeholder="select_category_name"
                @clear="handleClear"
                ref="selectUpCategoryId"
            >
                <el-option hidden key="CategoryId" :value="listQuery.category_id" :label="select_category_name"></el-option>
                <el-tree
                    :data="category"
                    :props="defaultProps"
                    :expand-on-click-node="false"
                    :check-on-click-node="true"
                    @node-click="handleNodeClick"
                >
                </el-tree>
            </el-select>

            <el-select v-model="listQuery.is_check" placeholder="请选择启用状态" clearable class="filter-item">
                <el-option
                    v-for="item in calendarCheckOptions"
                    :key="item.key"
                    :checked="item.key == listQuery.is_check"
                    :label="item.display_name+'('+item.key+')'"
                    :value="item.key"
                />
            </el-select>

            <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
                {{ $t('table.search') }}
            </el-button>
            <el-button v-waves class="filter-item" type="danger" icon="el-icon-delete" @click="handleDelete">
                {{ $t('table.batchDelete') }}
            </el-button>
            <el-button
                class="filter-item"
                type="primary"
                icon="el-icon-plus"
                @click="handleEdit"
            >
                {{ $t('table.add') }}
            </el-button>
            <el-button
                v-waves
                :loading="downloadLoading"
                class="filter-item"
                type="success"
                icon="el-icon-download"
                @click="handleDownload"
            >
                {{ $t('table.export') }}
            </el-button>
        </div>

        <el-table v-loading="listLoading" :data="list" border fit highlight-current-row>
            <el-table-column align="center" label="Id">
                <template slot-scope="{row}">
                    <span>{{ row.article_id }}</span>
                </template>
            </el-table-column>

            <el-table-column label="标题" align="center" min-width="150px" max-width="300px">
                <template slot-scope="{row}">
                    <router-link :to="'./edit/'+row.article_id" class="link-type">
                        <span>{{ row.article_title }}</span>
                    </router-link>
                </template>
            </el-table-column>

            <el-table-column align="center" label="所属栏目">
                <template slot-scope="{row}">
                    {{ row.menu? row.menu.menu_name : ('Id：' + row.menu_id) }}
                </template>
            </el-table-column>

            <el-table-column align="center" label="封面">
                <template slot-scope="{row}">
                    <img v-if="row.article_cover" :src="row.article_cover">
                </template>
            </el-table-column>

            <el-table-column align="center" label="排序">
                <template slot-scope="{row}">
                    {{ row.article_sort }}
                </template>
            </el-table-column>

            <el-table-column align="center" label="创建时间" width="150px">
                <template slot-scope="{row}">
                    {{ row.created_time | parseTime('{y}-{m}-{d} {h}:{i}') }}
                </template>
            </el-table-column>

            <el-table-column align="center" label="是否置顶">
                <template slot-scope="{row}">
                    <el-tag :type="row.set_top | statusFilter">
                        <i :class="row.set_top == 1 ? 'el-icon-top' : 'el-icon-bottom'" />
                        {{ row.set_top == 1 ? '是' : '否' }}
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column align="center" label="是否推荐">
                <template slot-scope="{row}">
                    <el-tag :type="row.is_recommend | statusFilter">
                        <i :class="row.is_recommend == 1 ? 'el-icon-info' : 'el-icon-remove'" />
                        {{ row.is_recommend == 1 ? '是' : '否' }}
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column align="center" label="是否公开">
                <template slot-scope="{row}">
                    <el-tag :type="row.is_public | statusFilter">
                        <i :class="row.is_public == 1 ? 'el-icon-unlock' : 'el-icon-lock'" />
                        {{ row.is_public == 1 ? '是' : '否' }}
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column align="center" label="是否启用">
                <template slot-scope="{row}">
                    <el-tag :type="row.is_check | statusFilter">
                        <i :class="row.is_check == 1 ? 'el-icon-unlock' : 'el-icon-lock'" />
                        {{ row.is_check == 1 ? '是' : '否' }}
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column align="center"
                             label="操作">
                <template slot-scope="{row}">
                    <!-- 是否置顶 -->
                    <el-button v-if="row.set_top == 0" type="text"
                               @click="changeStatus(row, 1, 'set_top')">
                        <el-tag :type="1 | statusFilter">
                            <i class="el-icon-top" />
                            置顶
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.set_top == 1" type="text"
                               @click="changeStatus(row, 0, 'set_top')">
                        <el-tag :type="0 | statusFilter">
                            <i class="el-icon-bottom" />
                            取消’置顶‘
                        </el-tag>
                    </el-button>
                    <!-- 是否推荐 -->
                    <el-button v-if="row.is_recommend == 0" type="text"
                               @click="changeStatus(row, 1, 'is_recommend')">
                        <el-tag :type="1 | statusFilter">
                            <i class="el-icon-info" />
                            推荐
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.is_recommend == 1" type="text"
                               @click="changeStatus(row, 0, 'is_recommend')">
                        <el-tag :type="0 | statusFilter">
                            <i class="el-icon-remove" />
                            取消’推荐‘
                        </el-tag>
                    </el-button>
                    <!-- 是否公开 -->
                    <el-button v-if="row.is_public == 0" type="text"
                               @click="changeStatus(row, 1, 'is_public')">
                        <el-tag :type="1 | statusFilter">
                            <i class="el-icon-unlock" />
                            公开
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.is_public == 1" type="text"
                               @click="changeStatus(row, 0, 'is_public')">
                        <el-tag :type="0 | statusFilter">
                            <i class="el-icon-lock" />
                            私密
                        </el-tag>
                    </el-button>
                    <!-- 是否启用 -->
                    <el-button v-if="row.is_check == 0" type="text"
                               @click="changeStatus(row, 1, 'is_check')">
                        <el-tag :type="1 | statusFilter">
                            <i class="el-icon-unlock" />
                            启用
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.is_check == 1" type="text"
                               @click="changeStatus(row, 0, 'is_check')">
                        <el-tag :type="0 | statusFilter">
                            <i class="el-icon-lock" />
                            禁用
                        </el-tag>
                    </el-button>
                    <!-- 编辑与删除 -->
                    <el-button type="text" icon="el-icon-edit" @click="handleEdit(row)"> 编辑 </el-button>
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(row)"> 删除 </el-button>
                </template>
            </el-table-column>
        </el-table>

        <pagination
                v-show="total>0"
                :total="total"
                :page.sync="listQuery.page"
                :limit.sync="listQuery.limit"
                @pagination="getList"
        />
    </div>
</template>

<script>
    import {getList, setDel, changeFiledStatus} from '@/api/articles';
    import {getCategorySelect} from "@/api/article_categories"; // waves directive
    import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
    import waves from '@/directive/waves';
    import {getFormatDate, parseTime} from "@/utils";

    const calendarCheckOptions = [
        {key: '-1', display_name: '全部'},
        {key: '1', display_name: '启用'},
        {key: '0', display_name: '禁用'}
    ];

    const calendarCheckKeyValue = calendarCheckOptions.reduce((acc, cur) => {
        acc[cur.key] = cur.display_name;
        return acc;
    }, {});

    export default {
        name: 'articleManage',
        components: {Pagination},
        directives: {waves},
        filters: {
            statusFilter(status) {
                const statusMap = {
                    1: 'success',
                    2: 'info',
                    0: 'danger',
                };
                return statusMap[status];
            },
            checkFilter(type) {
                return calendarCheckKeyValue[type] || '';
            },
        },
        data() {
            return {
                list: [],
                total: 0,
                listLoading: true,
                listQuery: {
                    search: '',
                    is_check: -1,
                    category_id: -1,
                    page: 1,
                    limit: 10,
                    is_download: 0, // 是否下载：1.是；默认0
                },
                downloadLoading: false,
                calendarCheckOptions,

                category:[], // 分类
                select_category_name: '请选择文章分类',
                defaultProps: {
                    children: '_child',
                    label: 'category_name'
                },
            }
        },
        created() {
            // 文章列表
            this.getList();
            // 文章分类列表
            this.getCategorySelect();
        },
        methods: {
            handleFilter() {
                this.listQuery.page = 1;
                this.getList();
            },
            // 文章列表
            async getList(callback) {
                this.listLoading = true;
                const {data, status, msg} = await getList(this.listQuery);
                if(this.listQuery.is_download == 1){
                    if (callback){
                        callback(data, status, msg);
                    }
                }else{
                    this.list = data.data;
                    this.total = data.total;
                    this.listQuery.limit = data.per_page || 10;
                }
                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            // 获取文章分类列表
            async getCategorySelect() {
                const res = await getCategorySelect();
                this.category = res.data;
            },
            // 节点点击事件
            handleNodeClick(data) {
                // 这里主要配置树形组件点击节点后，设置选择器的值；自己配置的数据，仅供参考
                this.select_category_name = data.category_name;
                this.listQuery.category_id = data.category_id;

                // 选择器执行完成后，使其失去焦点隐藏下拉框的效果
                this.$refs.selectUpCategoryId.blur();
            },
            // 选择器配置可以清空选项，用户点击清空按钮时触发
            handleClear() {
                // 将选择器的值置空
                this.select_category_name = '请选择文章分类';
                this.listQuery.category_id = -1;
            },
            // 删除
            handleDelete(row) {
                var ids = '';
                if (row.article_id) {
                    ids = row.article_id;
                } else {
                    if (this.selectRows.length > 0) {
                        ids = this.selectRows.map((item) => item.article_id).join();
                    } else {
                        this.$message('未选中任何行', 'error');
                        return false;
                    }
                }
                this.$confirm(
                    '你确定要删除操作吗？删除之后将无法恢复，请谨慎操作',
                    'Warning',
                    {
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                    })
                    .then(async () => {
                        const {status, msg} = await setDel({article_id: ids, 'is_batch' : this.is_batch});
                        this.$message({
                            message: msg,
                            type: 'success'
                        });
                        // 重新加载列表
                        this.handleFilter();
                    })
                    .catch(err => {
                        console.error(err);
                    });
            },
            // 新增与编辑
            handleEdit(row) {
                var query = {};
                if (row.article_id) query.article_id = row.article_id;
                this.$router.push({
                    'path':`/articles/detail`,
                    'query': query,
                });
            },
            // 状态变更
            async changeStatus(row, value, filed) {
                const {data, msg, status} = await changeFiledStatus({
                    'article_id': row.article_id,
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
            // 下载
            handleDownload() {
                this.downloadLoading = true;
                this.listQuery.page = 1;
                this.listQuery.is_download = 1;
                let _this = this;
                this.getList(function (data, status, msg) {
                    // 如果获取失败，那么无需进入下一步
                    if (status != 1){
                        _this.$message({
                            message: msg,
                            type: 'error',
                        });
                        return;
                    }
                    // 开始导出
                    import('@/vendor/Export2Excel').then((excel) => {
                        const tHeader = [
                            'Id',
                            '文章标题',
                            '所属分类',
                            '多图',
                            '关键字',
                            '描述',
                            '文章内容',
                            '详情外链',
                            '文章来源',
                            '文章作者',
                            '排序',
                            '创建时间',
                            '是否置顶',
                            '是否推荐',
                            '是否公开',
                        ];
                        const filterVal = [
                            'article_id',
                            'article_title',
                            'category_id',
                            'article_images',
                            'article_keywords',
                            'article_description',
                            'article_content',
                            'article_link',
                            'article_origin',
                            'article_author',
                            'article_sort',
                            'created_time',
                            'set_top',
                            'is_recommend',
                            'is_public',
                        ];
                        const download_list_data = _this.formatJson(data, filterVal);
                        excel.export_json_to_excel({
                            header: tHeader,
                            data:download_list_data,
                            filename: '文章列表-' + getFormatDate(),
                        });
                        _this.downloadLoading = false;
                    });
                })
            },
            formatJson(data, filterVal) {
                return data.map((v) =>
                    filterVal.map((j) => {
                        switch (j) {
                            case 'category_id':
                                return v.category ? v.category.category_name : '';
                                break;
                            case 'created_time':
                                return parseTime(v[j]);
                                break;
                            case 'set_top':
                                return v[j] == 1 ? '是' : '否';
                                break;
                            case 'is_recommend':
                                return v[j] == 1 ? '是' : '否';
                                break;
                            case 'is_public':
                                return v[j] == 1 ? '是' : '否';
                                break;
                            default:
                                return v[j];
                                break;
                        }
                    })
                )
            },
        }
    }
</script>

<style scoped>
    .edit-input {
        padding-right: 100px;
    }

    .cancel-btn {
        position: absolute;
        right: 15px;
        top: 10px;
    }

    img{
        max-height: 100px;
    }
</style>

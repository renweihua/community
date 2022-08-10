<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="listQuery.search"
                placeholder="请输入管理员账户/邮箱"
                style="width: 200px;"
                class="filter-item"
                @keyup.enter.native="handleFilter"
            />
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
                style="margin-left: 10px;"
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
                prop="role_id"
                label="Id"
                align="center"
            />
            <el-table-column
                show-overflow-tooltip
                prop="role_name"
                label="角色名称"
                align="center"
            />
            <el-table-column label="创建时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.created_time | parseTime("{y}-{m}-{d} {h}:{i}") }}
                </template>
            </el-table-column>
            <el-table-column align="center" prop="is_check" label="启用状态">
                <template slot-scope="{row}">
                    <el-tag :type="row.is_check | statusFilter">
                        <i :class="row.is_check == 1 ? 'el-icon-unlock' : 'el-icon-lock'" />
                        {{ row.is_check | checkFilter }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column
                fixed="right"
                label="操作"
                align="center"
            >
                <template v-slot="{row}">
                    <!-- 状态变更 -->
                    <el-button v-if="row.role_id > 1 && row.is_check == 0" type="text"
                               @click="changeStatus(row, 1)">
                        <el-tag :type="1 | statusFilter">
                            <i class="el-icon-unlock" />
                            启用
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.role_id > 1 && row.is_check == 1" type="text"
                               @click="changeStatus(row, 0)">
                        <el-tag :type="0 | statusFilter">
                            <i class="el-icon-lock" />
                            禁用
                        </el-tag>
                    </el-button>
                    <!-- 编辑与删除 -->
                    <el-button type="text" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
                    <el-button type="text" icon="el-icon-delete" v-if="row.role_id > 1" @click="handleDelete(row)">删除</el-button>
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
        <el-dialog :visible.sync="dialogVisible" :title="dialogType === 'edit' ? '编辑角色' : '新增角色'">
            <el-form ref="role" :model="role" label-width="80px">
                <el-form-item label="角色名称">
                    <el-input v-model="role.role_name"
                              :autosize="{ minRows: 2, maxRows: 20}"
                              placeholder="角色名称"/>
                </el-form-item>
                <el-form-item label="备注">
                    <el-input
                        v-model="role.role_remarks"
                        :autosize="{ minRows: 2, maxRows: 100}"
                        type="textarea"
                        placeholder="备注"/>
                </el-form-item>
                <el-form-item label="是否启用" prop="is_check">
                    <el-radio-group v-model="role.is_check">
                        <el-radio :label="0" :checked="role.is_check == 0 ? 'checked' : ''">禁用</el-radio>
                        <el-radio :label="1" :checked="role.is_check == 1 ? 'checked' : ''">启用</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="菜单权限">
                    <el-tree ref="tree" :check-strictly="checkStrictly" :data="menusData" :props="defaultProps"
                             show-checkbox node-key="menu_id"
                             class="permission-tree"/>
                </el-form-item>
            </el-form>
            <div style="text-align:right;">
                <el-button type="danger" @click="dialogVisible = false">{{ $t('permission.cancel') }}</el-button>
                <el-button type="primary" @click="confirmRole">{{ $t('permission.confirm') }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {getList, create, update, setDel, changeFiledStatus} from '@/api/admin_roles';
    import waves from '@/directive/waves' // waves directive
    import {parseTime, getFormatDate, deepClone} from '@/utils/index';
    import {getMenusSelect} from '@/api/admin_menus';

    const calendarCheckOptions = [
        {key: '-1', display_name: '全部'},
        {key: '1', display_name: '启用'},
        {key: '0', display_name: '禁用'}
    ];

    const calendarCheckKeyValue = calendarCheckOptions.reduce((acc, cur) => {
        acc[cur.key] = cur.display_name
        return acc
    }, {})

    const defaultRole = {
        role_name: '',
        role_remarks: '',
        is_check: 0,
        menus: [],
        menu_ids: [],
    };

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
                listLoading: true,
                layout: 'total, sizes, prev, pager, next, jumper',
                total: 0,
                selectRows: '',
                elementLoadingText: '正在加载...',
                listQuery: {
                    page: 1,
                    limit: 10,
                    search: '',
                    is_check: '',
                    is_download: 0, // 是否下载：1.是；默认0
                },
                downloadLoading: false,
                calendarCheckOptions,

                // 详情
                dialogType: 'add',
                checkStrictly: false,
                dialogVisible: false,
                role: defaultRole,
                defaultProps: {
                    children: '_child',
                    label: 'menu_name'
                },
                menus: [],
            }
        },
        computed: {
            menusData() {
                return this.menus;
            }
        },
        created() {
            // 获取角色列表
            this.getList();
            // 获取菜单列表
            this.getMenusSelect();
        },
        methods: {
            // 获取菜单列表
            async getMenusSelect() {
                const res = await getMenusSelect();
                this.menus = res.data;
            },
            // 获取角色列表
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
            checkFilter(val) {
                return calendarCheckKeyValue[val] || '';
            },
            setSelectRows(val) {
                this.selectRows = val;
                this.is_batch = 1;
            },
            handleEdit(row) {
                this.dialogVisible = true;
                this.checkStrictly = true;
                if (row) {
                    this.dialogType = 'edit';
                    this.role = deepClone(row);
                } else {
                    this.dialogType = 'add';
                    this.role = Object.assign({}, defaultRole);
                }
                this.$nextTick(() => {
                    const menus = this.role.menus;
                    this.$refs.tree.setCheckedNodes(this.generateArr(menus));
                    // set checked state of a node not affects its father and child nodes
                    this.checkStrictly = false;
                });
            },
            handleDelete(row) {
                var ids = '';
                if (row.role_id) {
                    ids = row.role_id;
                } else {
                    if (this.selectRows.length > 0) {
                        ids = this.selectRows.map((item) => item.role_id).join();
                    } else {
                        this.$message('未选中任何行', 'error');
                        return false;
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
                        const {status, msg} = await setDel({role_id: ids, 'is_batch' : this.is_batch});

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
                        console.error(err);
                    })
            },
            handleSizeChange(val) {
                this.listQuery.limit = val;
                this.listQuery.is_download = 0;
                this.getList();
            },
            handleCurrentChange(val) {
                this.listQuery.page = val;
                this.listQuery.is_download = 0;
                this.getList();
            },
            handleFilter() {
                this.listQuery.page = 1;
                this.listQuery.is_download = 0;
                this.getList();
            },
            generateTree(menus, menu_id = 0, checkedKeys) {
                const res = [];
                for (const menu of menus) {
                    const menu_id = menu.menu_id;

                    // recursive child menus
                    if (menu._child) {
                        res.concat(this.generateTree(menu._child, menu_id, checkedKeys));
                    }

                    if (checkedKeys.includes(menu_id) || (menu._child && menu._child.length >= 1)) {
                        res.push(menu_id);
                    }
                }
                return res;
            },
            generateArr(menus) {
                let data = [];
                menus.forEach(menu => {
                    data.push(menu);
                    if (menu._child) {
                        const temp = this.generateArr(menu._child);
                        if (temp.length > 0) {
                            data = [...data, ...temp];
                        }
                    }
                });
                return data;
            },
            async confirmRole() {
                // 当前角色选中的菜单栏目列表
                // this.$refs.tree.getHalfCheckedKeys() 返回选中子节点的父节点的key
                this.role.menu_ids = this.$refs.tree.getCheckedKeys().concat(this.$refs.tree.getHalfCheckedKeys());

                this.$refs['role'].validate(async (valid) => {
                    if (valid) {
                        const {msg, status} = this.role.role_id ? await update(this.role) : await create(this.role)
                        switch (status) {
                            case 1:
                                this.$message({
                                    message: msg,
                                    type: 'success'
                                });
                                // 单条数据，同步到列表数据，保证实时效果
                                for (let index = 0; index < this.list.length; index++) {
                                    if (this.list[index].role_id === this.role.role_id) {
                                        this.list.splice(index, 1, Object.assign({}, this.role));
                                        break;
                                    }
                                }

                                if (!this.role.role_id) this.getList();
                                break;
                            default:
                                this.$notify({
                                    title: msg,
                                    dangerouslyUseHTMLString: true,
                                    type: 'error'
                                });
                                break;
                        }
                        this.dialogVisible = false;
                    } else {
                        return false;
                    }
                });
            },
            // 状态变更
            async changeStatus(row, value) {
                const {data, msg, status} = await changeFiledStatus({
                    'role_id': row.role_id,
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
            // 下载
            handleDownload() {
                this.downloadLoading = true;
                this.listQuery.page = 1;
                this.listQuery.is_download = 1;
                let _this = this;
                this.getList(function (data, status, msg) {
                    // 如果获取失败，那么无需进入下一步
                    if (status != 1) {
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
                            '角色名称',
                            '备注',
                            '创建时间',
                            '启用状态'
                        ];
                        const filterVal = [
                            'role_id',
                            'role_name',
                            'role_remarks',
                            'created_time',
                            'is_check'
                        ];
                        const download_list_data = _this.formatJson(data, filterVal);
                        excel.export_json_to_excel({
                            header: tHeader,
                            data:download_list_data,
                            filename: '角色列表-' + getFormatDate()
                        });
                        _this.downloadLoading = false;
                    })
                });
            },
            formatJson(data, filterVal) {
                return data.map((v) =>
                    filterVal.map((j) => {
                        switch (j) {
                            case 'created_time':
                                return parseTime(v[j]);
                                break;
                            case 'is_check':
                                return this.checkFilter(v[j]);
                                break;
                            default:
                                return v[j];
                                break;
                        }
                    })
                )
            },
        },
    }
</script>

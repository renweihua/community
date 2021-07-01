<template>
    <el-dialog
        title="图片库"
        :close-on-click-modal="false"
        :show-close="false"
        :visible.sync="visible"
        width="870px"
        class="file-dialog"
        append-to-body
        @close="closeHandle"
    >
        <el-row class="group">
            <el-button type="success" @click="changeGroup(0, '')">新建分组</el-button>
            <el-button v-if="select_group_id > 0" type="warning" @click="changeGroup(select_group_id, select_group_name)">编辑分组</el-button>
            <el-button v-if="select_group_id > 0" type="danger" @click="deleteGroup()">删除分组</el-button>
        </el-row>
        <el-row>
            <el-col :span="4">
                <div class="file-group">
                    <el-tree
                        ref="treeBox"
                        node-key="group_id"
                        :highlight-current="true"
                        :data="groupData"
                        :props="defaultProps"
                        @node-click="onSelectGroup"
                    />
                </div>
            </el-col>
            <el-col :span="20">
                <el-form :inline="true" :model="listQuery" style="padding-right: 0px" @keyup.enter.native="initData()">
                    <el-form-item>
                        <el-input v-model="listQuery.search" size="small" placeholder="输入文件名查询" clearable/>
                    </el-form-item>
                    <el-form-item>
                        <el-button size="small" type="primary" icon="el-icon-search" @click="initData">搜索</el-button>
                    </el-form-item>
                    <el-form-item style="float: right">
                        <el-upload
                            :action="upload_url"
                            :headers="headers"
                            :before-upload="beforeUploadHandle"
                            :on-success="successHandle"
                            :show-file-list="false"
                            :multiple="true"
                            :file-list="fileList"
                        >
                            <el-button size="small" plain type="primary" icon="el-icon-upload">上传</el-button>
                        </el-upload>
                    </el-form-item>
                </el-form>
                <div v-loading="loading" class="file-block pointer">
                    <el-row :gutter="5">
                        <el-col v-for="item in tableData" :key="item.id" :span="4">
                            <div class="file-item" :class="{active: selectedIndexs.indexOf(item.file_id) !== -1}" @click="selected(item)">
                                <el-image v-if="item.extension == 'jpg' || item.extension == 'png' || item.extension == 'gif'"
                                          class="file-img" :src="item.file_url" :title="item.file_name"/>
                                <el-image v-else class="file-img" :src="setFileImg(item.extension)"
                                          :title="item.file_name" />
                                <p class="file-name">{{ item.file_name }}</p>
                            </div>
                        </el-col>
                    </el-row>
                </div>
                <!--底部操作栏-->
                <div class="file-edit" v-if="selectedIndexs.length">
                    <span class="file-edit-desc">已选择{{ selectedIndexs.length }}项</span>
                    <el-button-group>
                        <el-button size="small" @click="deleteHandle">删除</el-button>
                        <el-button size="small" @click="clickRemove">移动至：</el-button>
                        <el-select v-if="show_remove_select" class="remove-select" v-model="remove_group_id" size="small" filterable placeholder="指定分组" @change="removeToGroup">
                            <el-option
                                :key="-1"
                                label="指定分组"
                                value="请指定移动至分组"
                                :disabled="true" >
                            </el-option>
                            <el-option
                                v-for="item in groupData"
                                v-if="item.group_id > 0"
                                :key="item.group_id"
                                :label="item.group_name"
                                :value="item.group_id"
                                :disabled="item.disabled" >
                            </el-option>
                        </el-select>
                    </el-button-group>
                </div>
                <pagination
                    layout="total, prev, pager, next"
                    :total="total"
                    :page.sync="listQuery.page"
                    :limit.sync="listQuery.limit"
                    @pagination="initData"
                />
            </el-col>
        </el-row>
        <span slot="footer" class="dialog-footer">
            <el-button type="danger" size="small" @click="visible = false">取消</el-button>
            <el-button type="primary" size="small" @click="submit()">确定</el-button>
        </span>
    </el-dialog>
</template>

<script>
    import {getFileList, delFile, removeFileGroup} from '@/api/files';
    import {getFileGroup, create as addFileGroup, update as updateFileGroup, delFileGroup} from '@/api/file_groups';
    import Pagination from '@/components/Pagination';
    import {getToken} from '@/utils/auth';
    import {getUploadUrl} from '@/api/common';

    export default {
        props: {
            // 是否开启批量选择文件
            batch_select: {
                type: Boolean,
                'default': false
            },
            // 是否开启批量选择文件
            limit: {
                type: Number,
                'default': 1
            },
        },
        name: 'FileList',
        components: {
            Pagination
        },
        data() {
            return {
                upload_url:'',
                groupData: [],
                listQuery: {
                    search: '',
                    group_id: -1,
                    page: 1,
                    limit: 18,
                },
                total: 0,
                headers: {'Authorization': getToken()},
                num: 0,
                successNum: 0,
                fileList: [],
                loading: false,
                tableData: [],
                // 选中的文件Ids
                selectedIndexs: [],
                // 选中的文件
                activeItem: [],
                visible: false,
                //  可以识别的文件类型
                fileImgTypeList: [
                    'png',
                    'jpg',
                    'jpeg',
                    'docx',
                    'doc',
                    'ppt',
                    'pptx',
                    'xls',
                    'xlsx',
                    'avi',
                    'mp4',
                    'css',
                    'csv',
                    'rar',
                    'zip',
                    'dmg',
                    'mp3',
                    'open',
                    'pdf',
                    'rtf',
                    'txt',
                    'oa',
                    'js',
                    'html',
                    'sql',
                    'jar',
                    'svg',
                    'gif',
                    'json',
                    'exe',
                    'dll',
                    'apk',
                    'bat',
                    'php'
                ],
                //  文件图片Map映射
                fileImgMap: {
                    dll: require('@/assets/fileIcon/dll.jpg'),
                    apk: require('@/assets/fileIcon/apk.jpg'),
                    bat: require('@/assets/fileIcon/bat.jpg'),
                    css: require('@/assets/fileIcon/css.jpg'),
                    csv: require('@/assets/fileIcon/csv.jpg'),
                    png: require('@/assets/fileIcon/file.jpg'),
                    jpg: require('@/assets/fileIcon/file.jpg'),
                    jpeg: require('@/assets/fileIcon/file.jpg'),
                    docx: require('@/assets/fileIcon/doc.jpg'),
                    doc: require('@/assets/fileIcon/doc.jpg'),
                    ppt: require('@/assets/fileIcon/ppt.jpg'),
                    pptx: require('@/assets/fileIcon/ppt.jpg'),
                    xls: require('@/assets/fileIcon/xls.jpg'),
                    xlsx: require('@/assets/fileIcon/xls.jpg'),
                    mp4: require('@/assets/fileIcon/video.jpg'),
                    // avi: require('@/assets/file/file_avi.jpg'),
                    rar: require('@/assets/fileIcon/rar.jpg'),
                    zip: require('@/assets/fileIcon/zip.jpg'),
                    // dmg: require('@/assets/file/file_dmg.jpg'),
                    mp3: require('@/assets/fileIcon/mp3.jpg'),
                    // open: require('@/assets/file/file_open.jpg'),
                    pdf: require('@/assets/fileIcon/pdf.jpg'),
                    // rtf: require('@/assets/file/file_rtf.jpg'),
                    txt: require('@/assets/fileIcon/txt.jpg'),
                    // oa: require('@/assets/file/file_oa.jpg'),
                    unknown: require('@/assets/fileIcon/unknown.jpg'),
                    js: require('@/assets/fileIcon/js.jpg'),
                    html: require('@/assets/fileIcon/html.jpg'),
                    sql: require('@/assets/fileIcon/sql.jpg'),
                    jar: require('@/assets/fileIcon/jar.jpg'),
                    svg: require('@/assets/fileIcon/svg.jpg'),
                    json: require('@/assets/fileIcon/json.jpg'),
                    exe: require('@/assets/fileIcon/exe.jpg'),
                    php: require('@/assets/fileIcon/php.jpg'),
                },
                defaultProps: {
                    children: 'children',
                    label: 'group_name'
                },
                // // 是否开启批量选择文件
                // batch_select:false,

                // 当前选中的分组Id与名称：用于指定分组编辑时
                select_group_id:-1,
                select_group_name:'',
                // 移动文件时，选择的分组
                remove_group_id:0,
                // 是否展示移动至的选择框
                show_remove_select:false,
            }
        },
        computed: {
        },
        created() {
            // this.initData();
            this.upload_url = getUploadUrl();
        },
        methods: {
            // 新增文件分组
            changeGroup(group_id, group_name) {
                this.$prompt('请输入分组名称', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    inputValue: group_name,
                }).then(({ value }) => {
                    // 新增/编辑 文件分组
                    this.changeGroupName(group_id, value);
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '取消输入'
                    });
                });
            },
            async changeGroupName(group_id, group_name) {
                this.loading = true;

                const {status, msg} = group_id ? await updateFileGroup({group_id:group_id, group_name: group_name}) : await addFileGroup({group_name: group_name});

                this.$message({
                    message: msg,
                    type: status == 1 ? 'success' : 'error',
                });
                if (status === 1) {
                    this.getFileGroup();
                }
                this.loading = false;
            },
            init() {
                this.visible = true;
                this.getFileGroup();
                this.initData();
            },
            initData() {
                this.loading = true;
                getFileList(this.listQuery).then(res => {
                    this.tableData = res.data.data;
                    this.total = res.data.total;
                    this.loading = false;
                })
            },
            // 获取文件分组
            getFileGroup() {
                getFileGroup().then(res => {
                    this.groupData = [{group_id: -1, group_name: '全部'}, {group_id: 0, group_name: '未分组'}].concat(res.data)
                    this.$nextTick(() => {
                        this.$refs.treeBox.setCurrentKey(-1)
                    })
                })
            },
            onSelectGroup(val) {
                this.select_group_id = this.listQuery.group_id = val.group_id;
                this.select_group_name = val.group_name;
                this.selectedIndexs = [];
                this.initData();
            },
            setFileImg(suffix) {
                if (suffix === null) {
                    return this.fileImgMap.dir
                } else if (!this.fileImgTypeList.includes(suffix)) {
                    return this.fileImgMap.unknown
                } else {
                    return this.fileImgMap[suffix]
                }
            },
            // 上传之前
            beforeUploadHandle(file) {
                // if (file.type !== 'image/jpg' && file.type !== 'image/jpeg' && file.type !== 'image/png' && file.type !== 'image/gif') {
                //   this.msgError('只支持jpg、png、gif格式的图片！')
                //   return false
                // }
                this.num++;
            },
            // 上传成功
            successHandle(res, file, fileList) {
                this.fileList = fileList;
                this.successNum++;

                this.$message({
                    message: res.msg,
                    type: res.status == 1 ? 'success' : 'error',
                });

                if (res.status === 1) {
                    if (this.num === this.successNum) {
                        this.initData();
                    }
                }
            },
            // 选中文件的效果
            selected(item) {
                let that = this;
                if (!that.batch_select){
                    that.activeItem = item;
                }else{
                    let has = false;
                    that.activeItem.forEach(function (val, index) {
                        if (val.file_id == item.file_id){
                            // 再次点击则移除选中
                            that.activeItem.splice(index, 1);
                            has = true;
                        }
                    });
                    // 如果移动时，选择就不应该限制数量……
                    // console.log(that.activeItem.length);
                    // console.log(this.limit);
                    // if (has == false && that.activeItem.length == this.limit){
                    //     this.$message({
                    //         message: '最多可选' + limit + '个文件！',
                    //         type: 'error',
                    //     });
                    // }
                    if (!has){
                        that.activeItem.push(item);
                    }
                }

                // 当前选中的Id组
                if (this.selectedIndexs.indexOf(item.file_id) === -1) {
                    this.selectedIndexs.push(item.file_id);
                } else {
                    this.selectedIndexs.splice(this.selectedIndexs.indexOf(item.file_id), 1);
                }
            },
            submit() {
                // 如果设置了多图的数量，那么返回时，只返回指定数量的文件
                if (this.activeItem.length > this.limit){
                    this.activeItem = this.activeItem.slice(this.activeItem.length - this.limit - 1, this.limit);
                }
                this.$emit('handleSubmit', this.activeItem);
                this.visible = false;

                this.fileList = [];
                this.activeItem = [];
                this.selectedIndexs = [];
            },
            // 删除
            deleteHandle() {
                const ids = this.selectedIndexs;
                this.$confirm(`确定对 ${ids.length} 条数据进行 [ ${ids.length == 1 ? '删除' : '批量删除'} ] 操作吗?`).then(res => {
                    delFile({file_id: ids.join(), 'is_batch': ids.length == 0 ? 0 : 1}).then(res => {
                        this.$message({
                            message: res.msg,
                            type: res.status == 1 ? 'success' : 'error',
                        });

                        this.loading = true;
                        if (res.status === 1) {
                            this.loading = false;
                            this.initData();
                        }
                        // 清空选中
                        this.selectedIndexs = [];
                    });
                }).catch(() => {
                })
            },
            clickRemove(){
                this.show_remove_select = true;
            },
            // 删除文件分组
            deleteGroup() {
                this.$confirm(`确定对 ${this.select_group_name} 进行 '删除' 操作吗?`).then(res => {
                    delFileGroup({group_id: this.select_group_id}).then(res => {
                        this.$message({
                            message: res.msg,
                            type: res.status == 1 ? 'success' : 'error',
                        });

                        this.loading = true;
                        if (res.status === 1) {
                            this.loading = false;
                            this.getFileGroup();
                        }
                    });
                }).catch(() => {
                })
            },
            // 弹窗关闭时
            closeHandle() {
                // this.$emit('handleSubmit', []);
                this.visible = false;

                this.fileList = [];
                this.activeItem = [];
                this.selectedIndexs = [];
            },
            // 移动文件到指定分组
            removeToGroup(e){
                if (e < 0){
                    return false;
                }
                this.$confirm(`确定对进行 '移动分组' 操作吗?`).then(res => {
                    this.loading = true;
                    removeFileGroup({file_ids:this.selectedIndexs, group_id: this.remove_group_id}).then(res => {
                        this.$message({
                            message: res.msg,
                            type: res.status == 1 ? 'success' : 'error',
                        });
                        if (res.status === 1) {
                            this.remove_group_id = 0;
                            this.selectedIndexs = [];
                            this.initData();
                        }
                        this.loading = false;
                    });
                }).catch(() => {
                });
                this.show_remove_select = false;
            }
        }
    }
</script>
<style>
    .file-dialog .el-dialog__body {
        padding: 10px 20px 10px 10px;
    }

    .file-dialog .el-form-item {
        margin-bottom: 10px;
    }

    .file-group {
        border-right: 1px solid #ebeef5;
        height: 528px;
        margin-right: 10px;
        padding-right: 10px;
    }

    .file-group .el-tree--highlight-current .el-tree-node.is-current > .el-tree-node__content {
        background-color: #1890ff;
        color: white;
    }

    .file-block {
        /*border: 1px solid #dedede;*/
        height: 430px;
        /*padding: 5px 10px;*/
    }

    .file-item {
        position: relative;
        border-radius: 2px;
        border: 1px solid rgba(0, 0, 0, .09);
        width: 105px;
        height: 130px;
        margin: 7px auto;
        -webkit-transition: All .2s ease-in-out;
        transition: All .2s ease-in-out;
    }

    .file-img {
        padding: 5px;
        margin: 0 auto;
        height: 111px;
        display: -webkit-box;
        align-items: center;
    }

    .file-item:hover {
        border: 1px solid #2aa5ff;
    }

    .file-name {
        position: relative;
        width: 110px;
        bottom: 20px;
        height: 22px;
        line-height: 22px;
        padding-left: 5px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        word-break: break-all;
    }

    .active {
        background-color: #b4e5ff;
        border: 1px solid #198de3;
        color: #ffffff;
    }

    .group{
        top: -50px;
        height: 0;
        left: 130px;
    }

    .file-edit {
        float: left;
        margin-top: 10px;
    }

    .file-edit-desc {
        margin-right: 10px;
        color: #909090;
    }

    /* 隐藏上传组件的选择框，这里只需要展示数据的格式即可 */
    .el-upload--picture-card{
        display: none;
    }

    .remove-select{
        top: 0px;
        left: 5px;
    }
</style>

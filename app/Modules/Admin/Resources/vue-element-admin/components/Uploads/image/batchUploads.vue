<template>
    <div>
        <!--
        multiple：是否多图
        auto-upload：是否在选取文件后立即进行上传
        limit：最大允许上传个数
        drag：拖拽文件
        -->
        <el-upload
            class="upload-demo"
            action="https://blog.cnpscy.com"
            :accept="accept"
            :limit="limit"
            :multiple="true"
            list-type="picture-card"
            :auto-upload="false"
            :http-request="uploadFile"
            ref="upload"
            :file-list="filesList"
            drag
            :on-preview="handlePictureCardPreview"
            :on-remove="handleRemove"
            :on-change="handleChange"
        >
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <!--
            <div class="el-upload__tip" slot="tip">只能上传jpg/png文件，且不超过500kb</div>
            -->
        </el-upload>
        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
        <el-button class="margin-top-20" type="primary" @click="subPicForm"> {{submit_msg}} </el-button>
    </div>
</template>

<script>
    import {getToken} from '@/utils/auth'

    import {getBatchUploadUrl, uploads} from '@/api/common';

    export default {
        props: {
            // 是否批量上传
            multiple: {
                type: Boolean,
                'default': true
            },
            // 域，上传文件name，触发事件会带上（如果一个页面多个图片上传控件，可以做区分
            field: {
                type: String,
                'default': 'files'
            },
            // 上传地址
            url: {
                type: String,
                'default': getBatchUploadUrl()
            },
            // Add custom headers
            headers: {
                type: Object,
                default: function() {
                    return {
                        'Authorization' : getToken(),
                    }
                }
            },
            // 单文件大小限制
            maxSize: {
                type: Number,
                'default': 10240
            },
            limit: {
                type: Number,
                'default': 6
            },
            accept: {
                type: String,
                'default': 'image/*',
            },
            datas: {
                type: Array,
                default:function(){
                    return []
                }
            }
        },
        data() {
            return {
                dialogImageUrl: '',
                dialogVisible: false,
                submit_msg: '开始上传',
                formData:'',

                filesList: [],
            }
        },
        watch: {
            datas(newData, old) {
                console.log('watch');
                this.filesList = newData;
            }
        },
        created() {
            console.log(this.datas);
        },
        methods: {
            beforeAvatarUpload(file) {//文件上传之前调用做一些拦截限制
                console.log('beforeAvatarUpload');
                const isJPG = true;
                // const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size < maxSize;

                // if (!isJPG) {
                //   this.$message.error('上传头像图片只能是 JPG 格式!');
                // }
                if (!isLt2M) {
                    this.$message.error('上传图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            },
            // 预览指定图片
            handlePictureCardPreview(file) {//预览图片时调用
                console.log('handlePictureCardPreview');
                console.log(file);
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            // 移除指定图片
            handleRemove(file, fileList) {
                console.log('handleRemove');
                const that = this;
                fileList.forEach(function(e, key){
                    if (file.url == e.url){
                        fileList.splice(key,1);
                    }else{
                        that.postForm.article_images.push(e.url);
                    }
                })
                that.datas = fileList;
            },
            uploadFile(file){
                console.log('uploadFile');
                this.formData.append(this.field + '[]', file.file);
            },
            // 图片批量上传
            subPicForm(){
                console.log('subPicForm');
                this.submit_msg = '上传中……';
                const that = this;

                this.formData = new FormData();
                this.$refs.upload.submit();
                uploads(this.formData, this.url, this.headers).then(response => {
                    const {data, msg, status} = response;
                    switch (status) {
                        case 1:
                            this.datas.push.apply(this.datas, data);
                            that.$emit('upload-success', this.datas);
                            break;
                        default:
                            that.$emit('upload-fail', status, msg, data);
                            break;
                    }
                    console.log(response);
                }).then(error => {
                    console.log(error)
                });

                this.submit_msg = '开始上传';
            },
            handleChange(file, fileList) {
                console.log('handleChange');
                this.datas = fileList.slice(-3);
            },
            uploadSuccess(result){
                console.log('uploadSuccess');
                console.log(result)
            },
            uploadFail(status, msg, data){
                console.log('uploadFail');
                console.log(status)
                console.log(msg)
                console.log(data)
            },
        }
    }

</script>

<style>

</style>

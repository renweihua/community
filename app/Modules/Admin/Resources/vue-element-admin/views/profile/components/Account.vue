<template>
    <el-form>
        <el-form-item label="Name">
            <el-input v-model.trim="user.name"/>
        </el-form-item>
        <el-form-item label="Password">
            <el-input v-model="password"/>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="submit" v-loading="loading">Update</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    import {updateAdmin} from "@/api/indexs";
    export default {
        props: {
            user: {
                type: Object,
                default: () => {
                    return {
                        name: '',
                    }
                }
            }
        },
        data() {
            return {
                loading: false,
                admin_name:'',
                password: ''
            }
        },
        methods: {
            submit() {
                this.loading = true;

                this.updateAdmin();

                setTimeout(() => {
                    this.loading = false;
                }, 500);
            },
            async updateAdmin(){
                const {msg, status} = await updateAdmin({
                    'admin_name': this.user.name,
                    'password': this.password,
                });
                if (status == 1){
                    this.$message({
                        message: 'Admin information has been updated successfully.',
                        type: 'success',
                        duration: 5 * 1000
                    });
                    this.password = '';
                }else{
                    this.$message({
                        message: msg,
                        type: 'error',
                    });
                }
            }
        }
    }
</script>

<template>
    <div class="boxes">
        <div class="box" id="edit-account">
            <div class="box-heading border-bottom">
                <h5>登录账户</h5>
            </div>
            <form class="w-50" @submit.prevent="resetUser">
                <div class="form-group" v-if="currentUser.user_name">
                    <label>原始账户</label>
                    <input type="text" disabled class="form-control" :value="show_user_name">
                </div>
                <div v-if="changeAccount()">
                    <div class="form-group">
                        <label>新账户（仅可更改一次！）</label>
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="user_name">
                        </div>
                    </div>
                    <button class="btn btn-primary rounded">确定</button>
                </div>
            </form>
        </div>
        <div class="box" id="edit-password">
            <div class="box-heading border-bottom">
                <h5>修改密码</h5>
            </div>
            <form class="w-50" @submit.prevent="resetPassword">
                <div class="form-group">
                    <label>新密码</label>
                    <input type="password" v-model="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>确认新密码</label>
                    <input type="password" v-model="passwordConfirmation" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary rounded">修改密码</button>
                <router-link :to="{name: 'auth.forget-password'}" class="text-blue ml-2">忘记密码？</router-link>
            </form>
        </div>
        <div class="box" id="edit-email">
            <div class="box-heading border-bottom">
                <h5>修改邮箱地址</h5>
            </div>
            <form class="w-50" @submit.prevent="updateEmail">
                <div class="form-group">
                    <label>原邮箱</label>
                    <input type="text" disabled class="form-control" :value="currentUser.user_email">
                </div>
                <div class="form-group">
                    <label>新邮箱</label>
                    <input type="text" class="form-control" v-model="email">
                    <small class="form-text text-muted">修改后需要进行新的邮箱验证。</small>
                </div>
                <button type="submit" class="btn btn-primary rounded">确定</button>
            </form>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        data() {
            return {
                password: '',
                passwordConfirmation: '',
                email: '',
                user_name: '',
                show_user_name: '',
            }
        },
        computed: {
            ...mapGetters(['currentUser'])
        },
        created(){
            this.show_user_name = this.currentUser.user_name;
        },
        mounted() {
            if (this.$route.hash) {
                let hash = this.$route.hash;

                this.goAnchor(hash.slice(1));
            }
        },
        methods: {
            changeAccount() {
                return this.currentUser.user_otherlogin && this.currentUser.user_otherlogin.change_account == 1 ? true : false;
            },
            async updateEmail() {
                await this.$http.post('user/changeEmail', {
                    user_email: this.email
                }).then((res) => {
                    this.$message.success(res.msg)
                });
            },
            resetPassword() {
                this.$http.put('user/changePassword', {
                    password: this.password,
                    password_confirmation: this.passwordConfirmation
                })
                .then((res) => {
                    this.$message.success(res.msg)
                });

                this.password = '';
                this.passwordConfirmation = '';
            },
            async resetUser() {
                if(!this.changeAccount){
                    return false;
                }
                await this.$http.patch('user/change-username', {
                    user_name: this.user_name,
                })
                .then((res) => {
                    this.show_user_name = this.user_name;
                    this.$message.success(res.msg);
                    this.user_name = '';
                });
            },
            goAnchor(name) {
                let element = document.getElementById(name);

                if (element) {
                    element.scrollIntoView();
                }
            }
        }
    }
</script>

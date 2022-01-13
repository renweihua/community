<template>
    <div class="box guest-login-guide text-center" v-if="!isLogged">
        <div class="text-18 p-2">加入我们<br>互动开发者社区</div>
        <router-link class="btn btn-block btn-primary" :to="{ name: 'auth.login' }">登录</router-link>

        <router-link class="btn btn-block btn-outline-primary" :to="{ name: 'auth.login' }">立即签到</router-link>

        <router-link class="btn btn-block btn-outline-primary" :to="{ name: 'records' }">版本记录</router-link>
    </div>
    <div class="box guest-login-guide text-center" v-else>
        <button v-if="!this.user_info || !this.user_info.is_sign" @click="sign" class="btn btn-block btn-outline-blue">立即签到</button>
        <button v-else class="btn btn-block btn-outline-secondary">今日已签到成功！</button>

        <router-link class="btn btn-block btn-outline-primary" :to="{ name: 'records' }">版本记录</router-link>
    </div>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    
    export default {
        name: 'guest-login-guide',
        computed: {
            ...mapGetters(['isLogged'])
        },
        data() {
            return {
                user_info: {},
            }
        },
        created() {
            this.user_info = this.$user().user_info;
        },
        methods: {
            sign() {
                this.$http
                    .post(`user/signIn`)
                    .then((res) => {
                        if(res.status != 1){
                            this.$message.error(res.msg);
                        }
                        this.user_info.is_sign = true;
                        this.$message.success(res.msg);
                    });
            },
        }
    }
</script>

<style scoped>
    .guest-login-guide {
        background: url("/banners/technology.jpg");
        background-size: 160%;
        background-position: center;
    }
</style>

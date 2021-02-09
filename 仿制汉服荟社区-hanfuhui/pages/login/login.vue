<template>
    <view>
        <!-- 顶部封面 -->
        <image class="w100v mb64r" src="/static/default_header.png" mode="widthFix"></image>
        <!-- 表单 -->
        <view class="mlr64r">
            <view class="flexr-jsc flex-aic bbs2r h112r">
                <i-icon type="shouji" size="56" color="#FF6699"></i-icon>
                <input class="flex-fitem mlr18r" type="text" v-model="user_name" placeholder="请输入账户/手机号/邮箱"
                       :maxlength="20"/>
            </view>
            <view class="flexr-jsc flex-aic bbs2r h112r">
                <i-icon type="mima" size="56" color="#FF6699"></i-icon>
                <input class="flex-fitem mlr18r" type="text" :password="isPasswd" v-model="password" placeholder="请输入密码"
                       :maxlength="26"/>
                <i-icon :type="isPasswd ? 'mimabukejian' : 'mimakejian'" size="56" color="#8f8f94"
                        @click="isPasswd = !isPasswd"></i-icon>
            </view>
            <button class="btn-sub mt64r mb28r" hover-class="btn-hover" @tap="fnLogin" :disabled="isLogin"
                    :loading="isLogin">登录
            </button>
            <view class="flexr-jsb f28r cgray">
                <view class="funderline" @tap="fnPage('forget')">忘记密码</view>
                <view>没有账号？
                    <text class="ctheme funderline ml8r" @tap="fnPage('register')">注册</text>
                </view>
            </view>
        </view>
        <!-- 社交账号 -->
        <view class="social">
            <view class="flex flex-aic mb28r">
                <view class="flex-fitem line-gr-ctheme"></view>
                <view class="f32r mlr18r ctheme">社交账号登录</view>
                <view class="flex-fitem line-gl-ctheme"></view>
            </view>
            <view class="flexr-jsa">
                <image class="hw96r br50v" src="/static/icon_weixin.png" mode="aspectFit" @tap="fnWechat"></image>
                <image class="hw96r br50v" src="/static/icon_qq.png" mode="aspectFit" @tap="fnQQ"></image>
            </view>
        </view>
    </view>
</template>

<script>
    import {
        mobileReg
    } from "@/utils/CommonUtil.js"
    import {
        getRsaText
    } from "@/api/CommonServer.js"
    import {
        loginByPhone,
        getUserInfo,
        getNeteaseIMToken
    } from "@/api/UserServer.js"
    import {
        getSigninInfo,
    } from "@/api/HanbiServer.js"
    import {
        getMessageNoReadCount,
    } from "@/api/MessageServer.js"

    export default {
        data() {
            return {
                // 密码可见状态
                isPasswd: true,
                // 登录状态
                isLogin: false,
                // 账户
                user_name: '',
                // 密码
                password: ''
            };
        },
        onReady() {
            // 置空导航标题
            uni.setNavigationBarTitle({
                title: ''
            });
        },

        methods: {
            // 登录提交
            async fnLogin() {
                // 检查账户
                if (this.user_name == '') {
                    uni.showToast({
                        title: '请输入账户',
                        icon: 'none'
                    })
                    return
                }
                // 检查密码
                if (this.password == '') {
                    uni.showToast({
                        title: '请输入密码',
                        icon: 'none'
                    })
                    return
                }
                // 进行登录
                this.isLogin = true;
                try {
                    // 登录保存用户账户信息token
                    let accountRes = await loginByPhone({
                        'user_name': this.user_name,
                        'password': this.password,
                    });
                    console.log(accountRes);
                    if (accountRes.data.status != 1) {
                        uni.showToast({
                            title: accountRes.data.msg,
                            icon: 'none'
                        });
                        return;
                    }
                    if (accountRes.status == 401) {
                        uni.showToast({
                            title: accountRes.msg,
                            icon: 'none'
                        })
                        return;
                    }
                    // 服务器错误
                    if (res.statusCode == 500) {
                        uni.showToast({
                            title: '服务器错误',
                            icon: 'none'
                        })
                        return;
                    }
                    this.$store.commit('user/setAccountInfoData', accountRes.data.data);
                    uni.setStorageSync('TOKEN', accountRes.data.data.access_token);
                    // 保存登录用户信息
                    let userinfoRes = await getUserInfo(accountRes.data.data.user_id);
                    this.$store.commit('user/setUserInfoData', userinfoRes.data.data);
                    // 获取未读消息数
                    let mesRes = await getMessageNoReadCount()
                    this.$store.commit('setNewsCountData', mesRes.data.data)
                    // 获取签到信息
                    let signinRes = await getSigninInfo()
                    this.$store.commit('setSigninInfoData', signinRes.data.data)

                    // 开始调整主页
                    this.isLogin = false;
                    uni.reLaunch({
                        url: '/pages/index/index?current=0'
                    })
                } catch (e) {
                    this.isLogin = false;
                }
            },
            // 跳转页面
            fnPage(type) {
                if (this.isLogin) return
                uni.navigateTo({
                    url: `/pages/${type}/${type}`
                })
            },
            // 调起微信登录
            fnWechat() {
                if (this.isLogin) return
                console.log('微信登录');
                uni.showLoading({
                    title: '微信登录'
                })
                setTimeout(() => {
                    uni.hideLoading()
                }, 1200);
            },
            //调起QQ登录
            fnQQ() {
                if (this.isLogin) return
                console.log('QQ登录');
                uni.showLoading({
                    title: 'QQ登录'
                })
                setTimeout(() => {
                    uni.hideLoading()
                }, 1200);
            }
        }

    }
</script>

<style>
    page {
        background: #FFFFFF;
    }

    /*社交账号区域*/
    .social {
        position: absolute;
        left: 15%;
        right: 15%;
        bottom: 5%;
    }

    /* 线条 */
    .line-gr-ctheme {
        height: 4 rpx;
        min-width: 56 rpx;
        background: linear-gradient(to right, rgba(255, 102, 153, 0), #ff6699);
    }

    .line-gl-ctheme {
        height: 4 rpx;
        min-width: 56 rpx;
        background: linear-gradient(to left, rgba(255, 102, 153, 0), #ff6699);
    }
</style>

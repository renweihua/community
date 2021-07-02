<template>
  <view>
    <!-- 表单 -->
    <view class="mlr64r">
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <input class="flex-fitem mlr18r cgray" type="text" :value="loginMobile" :maxlength="11" :disabled="true" v-if="isLogin" />
        <input class="flex-fitem mlr18r" type="number" v-model="mobile" placeholder="请输入手机号" :maxlength="11" v-else />
        <view class="getcode" @tap="second >= 120 ? fnCode() : ''">{{second >= 120 ? '获取验证码': '剩余 '+second+' s'}}</view>
      </view>
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <input class="flex-fitem mlr18r" type="number" v-model="code" placeholder="请输入验证码" :maxlength="4" />
      </view>
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <input class="flex-fitem mlr18r" type="text" password v-model="password" placeholder="请输入密码" :maxlength="26" />
      </view>
    </view>
    <button class="btn-sub mt64r mlr64r" hover-class="btn-hover" @tap="fnForget" :disabled="isForget" :loading="isForget">确认修改</button>
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
    getPhoneCode,
    getUserAppToken,
    modifyPassword
  } from "@/api/UserServer.js"

  export default {
    data() {
      return {
        // 是否已登录跳转打开
        isLogin: false,
        // 是否修改提交
        isForget: false,
        // 登录手机号****
        loginMobile: '',
        // 手机号
        mobile: '',
        // 验证码
        code: '',
        // 密码
        password: '',
        // 定时器实例
        interval: null,
        // 倒计时秒
        second: 120
      };
    },

    onLoad(options) {
      if (options && options.mobile) {
        this.mobile = options.mobile;
        this.loginMobile = options.mobile.replace(/^(\d{3})\d{4}(\d+)/, "$1****$2")
        this.isLogin = true;
      }
      // 导航栏标题
      uni.setNavigationBarTitle({
        title: this.isLogin ? '重置密码':'忘记密码'
      }); 
    },

    methods: {
      /**
       * 获取验证码
       */
      async fnCode() {
        // 检查手机号格式
        if (this.mobile == '' || !mobileReg.test(this.mobile)) {
          uni.showToast({
            title: '请输入正确手机号',
            icon: 'none'
          })
          return
        }
        uni.showLoading({
          title: '发送中...'
        });
        // 提交获取
        try {
          let rsaRes = await getRsaText(`375fe0b80e7c40e9b462865a55a36156,${new Date().getTime()}`);
          let resToken = await getUserAppToken(rsaRes.data)
          await getPhoneCode({
            apptoken: resToken.data.Data,
            isnew: false,
            phone: this.mobile
          })
          // 倒计时
          uni.hideLoading()
          uni.showToast({
            title: '发送成功',
            success: () => {
              this.interval = setInterval(() => {
                if (this.second <= 0) {
                  clearInterval(this.interval);
                  this.second = 120;
                } else {
                  this.second--;
                }
              }, 1000)
            }
          })
        } catch (e) {
          clearInterval(this.interval);
          this.second = 120;
        }
      },
      /**
       * 修改密码提交
       */
      async fnForget() {
        // 检查手机号格式
        if (this.mobile == '' || !mobileReg.test(this.mobile)) {
          uni.showToast({
            title: '请输入正确手机号',
            icon: 'none'
          })
          return
        }
        // 验证码
        if (this.code == '' || this.code < 4) {
          uni.showToast({
            title: '请输入验证码',
            icon: 'none'
          })
          return
        }
        // 密码
        if (this.password == '' || this.password < 6) {
          uni.showToast({
            title: '请输入不少于6位数密码',
            icon: 'none'
          })
          return
        }
        // 进行修改
        this.isForget = true;
        try {
          let rsaRes = await getRsaText(`${this.mobile},${this.password}`);
          let modifyRes = await modifyPassword({
            code: this.code,
            phonesecret: rsaRes.data
          })
          if (modifyRes.data.Data) {
            this.isForget = false;
            uni.showToast({
              title: '修改成功，请重新登录',
              icon: 'none'
            })
            // 延迟2秒跳转
            setTimeout(() => {
              uni.reLaunch({
                url: '/pages/login/login',
              })
            }, 1800)
          }
        } catch (e) {
          this.isForget = false;
        }
      }
    },

    beforeDestroy() {
      clearInterval(this.interval);
    }
  }
</script>

<style>
  page {
    background: #FFFFFF;
  }
</style>

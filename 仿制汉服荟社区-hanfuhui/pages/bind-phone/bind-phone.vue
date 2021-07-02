<template>
  <view>
    <!-- 表单 -->
    <view class="mlr64r">
      <!-- 旧手机号和密码 -->
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <input class="flex-fitem mlr18r cgray" type="text" :value="loginMobile" :maxlength="11" :disabled="true" />
      </view>
      <!-- 绑定新手机号 -->
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <input class="flex-fitem mlr18r" type="number" v-model="mobileNew" placeholder="请输入新手机号" :maxlength="11" />
        <view class="getcode" @tap="second >= 120 ? fnCode() : ''">{{second >= 120 ? '获取验证码': '剩余 '+second+' s'}}</view>
      </view>
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <input class="flex-fitem mlr18r" type="number" v-model="code" placeholder="请输入验证码" :maxlength="4" />
      </view>
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <input class="flex-fitem mlr18r" type="text" password v-model="password" placeholder="请输入新密码" :maxlength="26" />
      </view>
    </view>
    <button class="btn-sub mt64r mlr64r" hover-class="btn-hover" @tap="fnBindMobile" :disabled="isBind" :loading="isBind">确认修改</button>
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
    modifyBindPhone
  } from "@/api/UserServer.js"

  export default {
    data() {
      return {
        // 是否提交
        isBind: false,
        // 登录手机号****
        loginMobile: '',
        // 手机号
        mobile: '',
        // 新手机号
        mobileNew: '',
        // 密码
        password: '',
        // 验证码
        code: '',
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
      }
    },

    methods: {
      /**
       * 获取验证码
       */
      async fnCode() {
        // 检查手机号格式
        if (this.mobileNew == '' || !mobileReg.test(this.mobileNew)) {
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
            isnew: true,
            phone: this.mobileNew
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
       * 绑定手机号提交
       */
      async fnBindMobile() {
        // 检查手机号格式
        if (this.mobileNew == '' || !mobileReg.test(this.mobileNew)) {
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
            title: '请输入密码',
            icon: 'none'
          })
          return
        }
        // 进行修改
        this.isBind = true;
        try {
          let rsaRes = await getRsaText(`${this.mobileNew},${this.password}`);
          let modifyRes = await modifyBindPhone({
            code: this.code,
            phonesecret: rsaRes.data
          })
          if (modifyRes.data.Data) {
            this.isBind = false;
            uni.showToast({
              title: '绑定成功，请重新登录',
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
          this.isBind = false;
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

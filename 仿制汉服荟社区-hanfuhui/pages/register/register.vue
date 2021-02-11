<template>
  <view>
    <!-- 顶部封面 -->
    <image class="w100v mb64r" src="/static/default_header.png" mode="widthFix"></image>
    <!-- 注册表单 -->
    <view class="mlr64r" v-if="!entryBind">
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <i-icon type="shouji" size="48" color="#FF6699"></i-icon>
        <input class="flex-fitem mlr18r" type="number" v-model="mobile" placeholder="请输入手机号" :maxlength="11" />
      </view>
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <i-icon type="yanzhengma" size="48" color="#FF6699"></i-icon>
        <input class="flex-fitem mlr18r" type="number" v-model="code" placeholder="请输入验证码" :maxlength="4" />
        <view class="getcode" @tap="second >= 120 ? fnCode() : ''">{{second >= 120 ? '获取验证码': '剩余 '+second+' s'}}</view>
      </view>
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <i-icon type="mima" size="48" color="#FF6699"></i-icon>
        <input class="flex-fitem mlr18r" type="text" :password="isPasswd" v-model="password" placeholder="请输入密码"
          :maxlength="26" />
        <i-icon :type="isPasswd ? 'mimabukejian' : 'mimakejian'" size="48" color="#8f8f94" @click="isPasswd = !isPasswd"></i-icon>
      </view>
      <button class="btn-sub mt64r" hover-class="btn-hover" @tap="fnRegister" :disabled="isRegister" :loading="isRegister">注册</button>
    </view>

    <!-- 绑定用户表单 -->
    <view class="mlr64r anima-out-in3" v-else>
      <image class="mautoblock br50v hw200r" :src="calAvater" mode="aspectFill" @tap="fnAvatar"></image>
      <input class="mt64r mb18r hl80r bbs2r fcenter" type="text" v-model="bindUser.nickname" placeholder="请输入昵称"
        :maxlength="10" />
      <view class="flex hl80r">
        <view class="flex-fitem fcenter" @tap="fnGender('男')">
          <i-icon :type="bindUser.gender == '男' ? 'quan-dui':'quan'" size="48" :color="bindUser.gender == '男' ? '#FF6699':'#8f8f94'"></i-icon>
          <text class="f32r ml8r" :class="[bindUser.gender == '男' ? 'ctheme':'cgray']">帅汉子</text>
        </view>
        <view class="flex-fitem fcenter" @tap="fnGender('女')">
          <i-icon :type="bindUser.gender == '女' ? 'quan-dui':'quan'" size="48" :color="bindUser.gender == '女' ? '#FF6699':'#8f8f94'"></i-icon>
          <text class="f32r ml8r" :class="[bindUser.gender == '女' ? 'ctheme':'cgray']">萌妹子</text>
        </view>
      </view>
      <button class="btn-sub mt64r" hover-class="btn-hover" @tap="fnBindUser" :disabled="isBind" :loading="isBind">完成注册</button>
    </view>

  </view>
</template>

<script>
  import {
    mobileReg
  } from "@/utils/CommonUtil.js"
  import {
    fnUploadUpyunPic
  } from "@/utils/UniUtil.js"
  import {
    getRsaText
  } from "@/api/CommonServer.js"
  import {
    getPhoneCode,
    getUserAppToken,
    registerByPhone,
    registerBindUser,
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
        // 注册状态
        isRegister: false,
        // 进入绑定
        entryBind: false,
        // 绑定状态
        isBind: false,
        // 绑定用户
        bindUser: {
          tempkey: '',
          nickname: '',
          headurl: '/pc/2015/12/3/21/b7a0c03d449e4110863b9f804bdf8c38.jpg',
          gender: '女'
        },
        // 手机号
        mobile: '',
        // 验证码
        code: '',
        // 密码
        password: '',
        // 定时器实例
        interval: null,
        // 倒计时秒
        second: 120,
      };
    },

    computed: {
      // 计算是否显示默认头像
      calAvater() {
        if (this.bindUser.headurl.indexOf('pc/2015') == 1) return '/static/default_avatar.png'
        return `https://pic.hanfugou.com${this.bindUser.headurl}_200x200.jpg`
      }
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
            isnew: true,
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
       *  注册提交
       */
      async fnRegister() {
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
        // 进行注册
        this.isRegister = true;
        try {
          let rsaRes = await getRsaText(`${this.mobile},${this.password}`);
          let registerRes = await registerByPhone({
            phonesecret: rsaRes.data,
            code: this.code
          });
          this.bindUser.tempkey = registerRes.data.Data
          clearInterval(this.interval);
          this.second = 120;
          this.isRegister = false;
          this.entryBind = true;
        } catch (e) {
          this.isRegister = false;
          this.entryBind = false;
        }
      },
      /**
       * 绑定用户
       */
      async fnBindUser() {
        // 检查用户
        if (this.bindUser.nickname == '' || this.bindUser.nickname.length <= 2) {
          uni.showToast({
            title: '请输入用户昵称',
            icon: 'none'
          })
          return
        }
        // 进行绑定
        this.isBind = true;
        try {
          // 注册成功后保存用户账户信息token
          let accountRes = await registerBindUser(this.bindUser);
          this.$store.commit('user/setAccountInfoData', accountRes.data.Data);
          uni.setStorageSync('TOKEN', accountRes.data.Data.AccessToken);
          // 保存登录用户信息
          let userinfoRes = await getUserInfo(accountRes.data.Data.UserID);
          this.$store.commit('user/setUserInfoData', userinfoRes.data.Data);
          // 获取未读消息数
          let mesRes = await getMessageNoReadCount()
          this.$store.commit('setNewsCountData', mesRes.data.Data)
          // 获取签到信息
          let signinRes = await getSigninInfo()
          this.$store.commit('setSigninInfoData', signinRes.data.Data)
           
          // 更新托管信息
          this.$store.getters['nim/getNim'].updateMyInfo({
            avatar: userinfoRes.HeadUrl,
            nick: userinfoRes.NickName,
            gender: userinfoRes.Gender,
            sign: userinfoRes.Describe,
            email: userinfoRes.UserName,
            tel: userinfoRes.ID,
            birth: new Date().toJSON()
          })
          // 开始跳转主页
          this.isBind = false;
          uni.reLaunch({
            url: '/pages/index/index?current=0'
          })
        } catch (e) {
          this.isBind = false;
        }
      },
      /**
       * 上传头像
       */
      fnAvatar() {
        if (this.isBind) return
        fnUploadUpyunPic(1).then(res => {
          if (res) this.bindUser.headurl = res.url
        })
      },
      // 性别选择
      fnGender(gender) {
        if (this.isBind) return
        this.bindUser.gender = gender;
      }
      //
    }

  }
</script>

<style>
  page {
    background: #FFFFFF;
  }
</style>

<template>
  <view>
    <!-- 头像 -->
    <image class="edit-avatar" :src="calInfoAvatar" mode="aspectFill" @tap="fnAvatar"></image>
    <view class="mlr64r">
      <!-- 昵称 -->
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <view class="f36r fbold c111">昵称</view>
        <input class="flex-fitem ml28r f32r c555" type="text" v-model="info.nickname" placeholder="请输入昵称" :maxlength="10" />
      </view>
      <!-- 性别 -->
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <view class="f36r fbold c111">性别</view>
        <view class="flex-fitem ml28r flex hl80r">
          <view class="pr28r mr28r" @tap="fnGender('男')">
            <i-icon :type="info.gender == '男' ? 'quan-dui':'quan'" size="42" :color="info.gender == '男' ? '#FF6699':'#555555'"></i-icon>
            <text class="f32r ml8r" :class="[info.gender == '男' ? 'ctheme':'c555']">帅汉子</text>
          </view>
          <view class="pr28r" @tap="fnGender('女')">
            <i-icon :type="info.gender == '女' ? 'quan-dui':'quan'" size="42" :color="info.gender == '女' ? '#FF6699':'#555555'"></i-icon>
            <text class="f32r ml8r" :class="[info.gender == '女' ? 'ctheme':'c555']">萌妹子</text>
          </view>
        </view>
      </view>
      <!-- 坐标 -->
      <view class="flexr-jsc flex-aic bbs2r h112r">
        <view class="f36r fbold c111">坐标</view>
        <view class="flex-fitem ml28r f32r c555" @tap="fnCityOpen">
          <text class="mr8r">{{cityNames[0]}}</text><text>{{cityNames[1]}}</text>
        </view>
      </view>
      <!-- 简介 -->
      <view class="flexr-jsc flex-aic bbs2r edit-hm112r" v-if="isShowArea">
        <view class="f36r fbold c111">简介</view>
        <textarea class="flex-fitem mlr28r ptb8r f32r c555 edit-hmo250r z5" placeholder="请输入简介" placeholder-style="font-size: 14px;color:#8F8F94"
          auto-height v-model="info.describe"></textarea>
      </view>
    </view>
    <!-- 提交按钮 -->
    <button class="btn-sub mt64r mlr64r" hover-class="btn-hover" :disabled="isModify" :loading="isModify" @tap="fnModify">提交修改</button>

    <!-- 城市选择弹出 -->
    <city-picker ref="city" @picker="fnCityPicker"></city-picker>
  </view>
</template>

<script>
  import {
    modifyUserInfo
  } from "@/api/UserServer.js"
  import {
    getCityList
  } from "@/api/CommonServer.js"

  // 城市选择弹出层组件
  import CityPicker from '@/components/city-picker/city-picker'

  export default {
    components: {
      CityPicker
    },
    data() {
      return {
        // 城市
        cityIDs: ['1', '2'],
        cityNames: ['北京', '东城'],
        // 用户信息
        info: {
          'cityid': '',
          'describe': '',
          'gender': '',
          'headurl': '',
          'nickname': '',
        },
        // 用户修改副本
        tempInfo: {},
        // 提交修改
        isModify: false,
        // 计算显隐原生输入框
        isShowArea: true
      };
    },

    computed: {
      // 计算头像信息显示
      calInfoAvatar() {
        if (this.info.headurl == '') return '/static/default_avatar.png'
        return this.info.headurl + '_200x200.jpg'
      }, 
    },
    onLoad(option) {
      if (option && option.id) {
        // 获取登录账户用户信息
        let tempUser = this.$store.getters['user/getAccountInfoData'].User
        if (typeof tempUser.CityIDs == 'string') this.cityIDs = tempUser.CityIDs.split(',')
        if (typeof tempUser.CityIDs == 'string') this.cityNames = tempUser.CityNames.split(',')
        this.info = {
          'cityid': this.cityIDs[1],
          'describe': typeof tempUser.Describe == 'string' ? tempUser.Describe : '该同袍还不知道怎么描述寄己 (╯▽╰)╭',
          'gender': tempUser.Gender,
          'headurl': tempUser.HeadUrl,
          'nickname': tempUser.NickName,
        }
        // 保存修改副本
        this.tempInfo = Object.assign({}, this.info)
        // 获取省列表
        getCityList(0).then(provinceRes => {
          this.$store.commit('common/setProvinceListData', provinceRes.data.Data)
        })
      }
    },

    methods: {
      /// 提交修改
      fnModify() {
        this.isModify = true;
        let paramArray = []
        let {
          cityid,
          nickname,
          gender,
          headurl,
          describe
        } = this.info;
        // 账户信息
        let accout = Object.assign({}, this.$store.getters['user/getAccountInfoData']);
        // 用户信息
        let userInfo = Object.assign({}, this.$store.getters['user/getUserInfoData']);
        // 修改地区
        if (cityid != this.tempInfo.cityid) {
          paramArray.push({
            'option': '4',
            'value': cityid
          }) 
        }
        // 修改昵称
        if (nickname != this.tempInfo.nickname) {
          paramArray.push({
            'option': '2',
            'value': nickname
          }) 
        }
        // 修改性别
        if (gender != this.tempInfo.gender) {
          paramArray.push({
            'option': '3',
            'value': gender
          }) 
        }
        // 修改头像
        if (headurl != this.tempInfo.headurl) {
          paramArray.push({
            'option': '1',
            'value': headurl
          }) 
        }
        // 修改简介
        if (describe != this.tempInfo.describe) {
          paramArray.push({
            'option': '5',
            'value': describe
          }) 
        }
        // 空修改
        if (paramArray.length == 0) {
          uni.showToast({
            title: '还没修改任何一项呢',
            icon: 'none'
          })
          this.isModify = false;
          return
        }
        // 提价修改
        try {
          // 遍历发送修改值
          paramArray.map(item => { 
            modifyUserInfo(item).then(res=>{
              if(res.data.Data != true) return
              // 修改地区
              if (item.option == '4') { 
                let cityNames = this.cityNames.toString();
                accout.User.CityNames = cityNames;
                accout.User.CityIDs = this.cityIDs.toString();
                userInfo.CityNames = cityNames;
              }
              // 修改昵称
              if (item.option == '2') {  
                accout.User.NickName = item.value;
                userInfo.NickName = item.value;
              }
              // 修改性别
              if (item.option == '3') {
                accout.User.Gender = item.value;
                userInfo.Gender = item.value;
              }
              // 修改头像
              if (item.option == '1') {
                accout.User.HeadUrl = item.value;
                userInfo.HeadUrl = item.value;
              }
              // 修改简介
              if (item.option == '5') { 
                accout.User.Describe = item.value;
                userInfo.Describe = item.value;
              }
            })
          })
          // 保存账户信息
          this.$store.commit('user/setAccountInfoData', accout)
          // 保存用户信息
          this.$store.commit('user/setLoginUserInfoData', userInfo)
          this.$store.commit('user/setTempUserInfoData', userInfo)
          this.isModify = false;
          uni.showToast({
            title: '修改成功'
          })
          setTimeout(() => {
            uni.navigateBack()
          }, 1800)
        } catch (e) {
          this.isModify = false;
        }
      },
      ///上传头像
      fnAvatar() {
        if (this.isModify) return
        upload(1).then(uploadRes => {
          if (uploadRes) this.info.headurl = 'https://pic.hanfugou.com' + uploadRes.url;
        })
      },
      /// 性别选择
      fnGender(gender) {
        if (this.isModify) return
        this.info.gender = gender;
      },
      /// 打开城市选择
      fnCityOpen() {
        if (this.isModify) return
        // 获取城市列表
        getCityList(this.cityIDs[0]).then(cityRes => {
          this.$store.commit('common/setCityListData', cityRes.data.Data)
          this.$refs.city.open(this.cityIDs)
          this.isShowArea = false
        })
      },
      /// 城市选择
      fnCityPicker(e) {
        this.isShowArea = true
        if (e.cityIDs[1] == 0) return
        this.cityIDs = e.cityIDs;
        this.cityNames = e.cityNames;
        this.info.cityid = e.cityIDs[1];
      }
    }
  }
</script>

<style>
  page {
    background: #FFFFFF;
  }

  .edit-avatar {
    width: 228rpx;
    height: 228rpx;
    border-radius: 50%;
    overflow: hidden;
    display: block;
    box-shadow: 0 8rpx 16rpx -8rpx #FF6699;
    margin: 32px auto;
  }

  .edit-hm112r {
    min-height: 112rpx;
  }

  .edit-hmo250r {
    max-height: 250rpx;
    overflow: hidden;
  }
</style>

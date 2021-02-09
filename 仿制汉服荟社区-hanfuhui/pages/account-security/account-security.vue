<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni :down="{use:false}" :up="{use:false}">
      <view class="mb18r bgwhite plr28r">
        <view class="flex flex-aic hl90r bbs2r" @tap="fnForget">
          <text class="f36r c111 flex-gitem">修改密码</text>
          <i-icon type="you" size="42" color="#8F8F94"></i-icon>
        </view>
        <view class="flex flex-aic hl90r bbs2r" @tap="fnInfoModify">
          <text class="f36r c111 flex-gitem">资料修改</text>
          <i-icon type="you" size="42" color="#8F8F94"></i-icon>
        </view>
        <view class="flex flex-aic hl90r" @tap="fnBindPhone">
          <text class="f36r c111 flex-gitem">修改绑定手机</text>
          <i-icon type="you" size="42" color="#8F8F94"></i-icon>
        </view>
      </view>
    </mescroll-uni>
  </view>
</template>

<script>
  export default {
    methods: {
      /// 跳转忘记密码
      fnForget() {
        uni.navigateTo({
          url: '/pages/forget/forget?mobile=' + this.$store.getters['user/getAccountInfoData'].User.Phone
        })
      },
      /// 跳转用户信息修改
      fnInfoModify() {
        uni.navigateTo({
          url: '/pages/user-info-modify/user-info-modify?id=' + this.$store.getters['user/getUserInfoData'].ID
        })
      },
      /// 跳转绑定手机
      fnBindPhone() {
        uni.navigateTo({
          url: '/pages/bind-phone/bind-phone?mobile=' + this.$store.getters['user/getAccountInfoData'].User.Phone
        })
      }
    }
  }
</script>

<style>
</style>

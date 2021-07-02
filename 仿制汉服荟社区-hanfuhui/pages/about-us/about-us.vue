<template>
  <view>
    <!-- 主logo -->
    <view class="mautoblock fcenter ptb64r">
      <image class="hw200r br8r " src="/static/logo.png" mode="aspectFit"></image>
      <view class="f32r c555 mt28r">汉服荟</view>
    </view>

    <!-- 规则 -->
    <view class="bgwhite plr28r">
      <view class="flex flex-aic hl90r bbs2r" @tap="fnOpenRule('hui_rule')">
        <text class="f36r c111 flex-gitem">社区公约</text>
        <i-icon type="you" size="42" color="#8F8F94"></i-icon>
      </view>
      <view class="flex flex-aic hl90r bbs2r" @tap="fnOpenRule('helper')">
        <text class="f36r c111 flex-gitem">帮助中心</text>
        <i-icon type="you" size="42" color="#8F8F94"></i-icon>
      </view>
      <view class="flex flex-aic hl90r">
        <text class="f36r c111 flex-gitem" @tap="fnOpenRule('hui_privacy')">隐私政策</text>
        <i-icon type="you" size="42" color="#8F8F94"></i-icon>
      </view>
    </view>

    <!-- 吐槽QQ群 -->
    <view class="f28r cgray fcenter mt64r">吐槽QQ群：464851545</view>

    <!-- 版权 -->
    <view class="f28r cgray fcenter posia posi-blr0 hl90r">
      Copyright ©2019 深圳市汉服荟网络科技有限公司
    </view>
  </view>
</template>

<script>
  export default {
    methods: {
      /// 跳转规则页
      fnOpenRule(key) {
        uni.navigateTo({
          url: '/pages/system-rule/system-rule?key=' + key
        })
      }
    }
  }
</script>

<style>
  .ptb64r{
    padding: 64rpx 0;
  }
</style>

<template>
  <view class="bgwhite ptb18r plr18r mb18r flex" @tap="$emit('click',infoData)">
    <image class="hw200r br8r mr18r" :src="infoData.FaceSrc ? infoData.FaceSrc+'_350x350.jpg/format/webp':'/static/default_image.png'"
      mode="scaleToFill" :lazy-load="true"></image>
    <view class="flex-fitem posir flexc-jsa">
      <view class="f32r fbold c555 h112r">{{infoData.Name}}</view>
      <view>
        <i-icon type="shijian" size="32" color="#8F8F94"></i-icon>
        <text class="f28r cgray ml8r">{{calDatetime}}</text>
      </view>
      <view v-if="infoData.city_info">
        <i-icon type="weizhi" size="32" color="#8F8F94"></i-icon>
        <text class="f28r cgray ml8r">{{calAddress}}</text>
      </view>
      <view class="posia posi-br0 f28r cwhite bgtheme br8r plr18r" v-if="infoData.State == 1">报名中</view>
      <view class="posia posi-br0 f28r cwhite bgred br8r plr18r" v-if="infoData.State == 2">进行中</view>
      <image class="org-state" v-if="infoData.State == 3" src="/static/icon_activityend.png" mode="scaleToFill"
        :lazy-load="true"></image>
    </view>
  </view>
</template>

<script>
  import {
    fnFormatLocalDate
  } from "@/utils/CommonUtil.js"

  /**  
   * 文章展示卡组件
   * @property {Object} infoData 信息数据  
   * @event {Function} click 展示卡 点击事件  
   */
  export default {
    name: 'org-card',
    props: {
      /**
       * 信息数据 
       */
      infoData: {
        type: Object,
        default: () => {
          return {
          }
        }
      }
    },
    computed: {
      /// 时间格式 2019-12-03 20:12 
      calDatetime() {
        let now = new Date(this.infoData.BeginDatetime || '2020-01-01 01:01');
        return fnFormatLocalDate(now);
      },
      /// 地址逗号换空格 
      calAddress() {
        let addr = this.infoData.AddressNames;
        return !!addr ? addr.split(',').join(' ') : '未知 未知'
      }
    },

  }
</script>

<style>
  /*活动结束状态*/
  .org-state {
    position: absolute;
    bottom: 0;
    right: 0;
    height: 120rpx;
    width: 150rpx;
  }
</style>

<template>
  <view class="flexc-jsc flex-aic bgf8 br8r ptb28r" @tap="$emit('click',infoData)">
    <image class="hw128r br18r" :src="calHuibaFaceUrl" mode="aspectFit"></image>
    <view class="f28r c555 fcenter ellipsis mt18r mb8r plr8r">{{infoData.Name || '#'}}</view>
    <view class="f24r cgray">{{infoData.TrendCount || 0}} 条动态</view>
  </view>
</template>

<script>
  /**  
   * 荟吧展示卡组件
   * @property {Object} infoData 信息数据  
   * @event {Function} click 展示卡 点击事件  
   */
  export default {
    name: 'huiba-card',

    props: {
      /**
       * 信息数据 
       */
      infoData: {
        type: Object,
        default: () => {
          return {
            "Describe": "秀穿搭图、穿搭教程，穿搭求教等",
            "FaceUrl": "http://pic.hanfugou.com/web/2019/7/27/16/de1a9a585fc84c8dabecdca751a2585c.jpeg",
            "FollowCount": 28572,
            "TrendCount": 4192,
            "HuibaFollow": false,
            "ID": 6524,
            "Name": "汉服穿搭",
            "PublicShow": true
          }
        }
      }
    },

    computed: {
      /// 计算荟吧Icon
      calHuibaFaceUrl() {
        let faceUrl = this.infoData.FaceUrl;
        return !!faceUrl ? faceUrl + '_200x200.jpg/format/webp' : '/static/default_avatar.png'
      },
    }

  }
</script>

<style></style>

<template>
  <view class="flexc-jsc flex-aic bgf8 br8r ptb28r" @tap="$emit('click',infoData)">
    <image class="hw128r br18r" :src="calHuibaFaceUrl" mode="aspectFit"></image>
    <view class="f28r c555 fcenter ellipsis mt18r mb8r plr8r">{{infoData.topic_name || '#'}}</view>
    <view class="f24r cgray">{{infoData.dynamic_count || 0}} 条动态</view>
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
          }
        }
      }
    },
    computed: {
      /// 计算荟吧Icon
      calHuibaFaceUrl() {
        let topic_cover = this.infoData.topic_cover;
        return !!topic_cover ? topic_cover : '/static/default_avatar.png'
      },
    }

  }
</script>

<style></style>

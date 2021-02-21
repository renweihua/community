<template>
  <view class="bgwhite br8r" @tap="$emit('click',infoData)">
    <image class="shop-cover" :src="infoData.FaceSrc ? infoData.FaceSrc+'_300x300.jpg':'/static/default_image.png'" mode="aspectFill" :lazy-load="true"></image>
    <view class="plr18r">
      <view class="ellipsis f28r c555 mtb18r">{{infoData.Name}}</view>
      <view class="flexr-jsb f24r cgray">
        <view>{{infoData.Shop.Name}}</view>
        <view>{{localtion}}</view>
      </view>
      <view>
        <text class="f24r cred">￥</text>
        <text class="f32r cred">{{infoData.BasePrice}}</text>
        <text class="f24r cgray ml18r">{{infoData.SaveCount}}人种草</text>
      </view>
    </view>
  </view>
</template>

<script>
  /**  
   * 商品展示卡组件
   * @property {Object} infoData 信息数据  
   * @event {Function} click 展示卡 点击事件  
   */
  export default {
    name: 'shop-card',
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
      /**
       * 省市地址拆分
       */
      localtion() {
        let shop = this.infoData.Shop;
        return !!shop ? shop.CityNames.split(',')[1] : '未知'
      }
    }

  }
</script>

<style>
  /* 商品封面 */
  .shop-cover {
    display: block;
    height: 360rpx;
    width: 100%;
    border-radius: 4rpx;
  }
</style>

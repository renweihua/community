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
            "TypeNames": "汉元素,上衣下裙",
            "Popularity": 4154,
            "HaveSku": true,
            "ID": 38814,
            "Name": "噗叽:樱华 池夏原创改良汉服汉元素齐腰齐胸双穿襦裙 樱花",
            "FaceSrc": "https://pic.hanfugou.com/web/2019/6/11/16/b445736a40ff4e5a9840cc98f5437d22.jpg",
            "BasePrice": 198.00,
            "Price": 198.00,
            "SaveCount": 4891,
            "Shop": {
              "ID": 994,
              "Name": "池夏",
              "User": {
                "ID": 596271,
                "NickName": null,
                "HeadUrl": null,
                "MainBgPic": null,
              },
              "LogoSrc": "https://pic.hanfugou.com/web/2019/2/26/22/833248872fcf419c9e7f4a8b1160cc51.jpeg",
              "CityNames": "安徽,合肥",
              "AtteCount": 51490,
            },
            "Stock": 2392,
            "SalesVolume": 338,
            "TaobaoID": 589433180483,
            "Datetime": "2019-03-26T00:10:45"
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

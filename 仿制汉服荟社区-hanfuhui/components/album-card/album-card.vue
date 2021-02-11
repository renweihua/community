<template>
  <view class="br8r" style="overflow: hidden;">
    <image class="album-cover" @tap="$emit('click',infoData)" :src="infoData.FaceSrc ? infoData.FaceSrc+'_350x350.jpg/format/webp':'/static/default_image.png'" mode="scaleToFill" :lazy-load="true"></image>
    <view class="bbs2r bls2r brs2r flex flex-aic plr18r ptb18r">
      <user-avatar @click="$emit('user',infoData.User)" :src="infoData.User.HeadUrl ? infoData.User.HeadUrl + '_100x100.jpg' : '/static/default_avatar.png'" :tag="infoData.User.AuthenticateCode" size="sm"></user-avatar>
      <view class="w128r flex-gitem f28r mlr18r c555 ellipsis" @tap="$emit('user',infoData.User)">{{infoData.User.NickName}}</view>
      <view class="flex flex-aic" @tap="$emit('click',infoData)">
        <i-icon type="remen" size="28" color="#FF6699"></i-icon>
        <text class="f24r cgray ml8r">{{infoData.TopCount}}</text>
      </view>
    </view>
  </view>
</template>

<script>
  /**  
   * 摄影展示卡组件
   * @property {Object} infoData 信息数据  
   * @event {Function} user 用户头像 点击事件  
   * @event {Function} click 展示卡 点击事件  
   */
  export default {
    name: 'album-card',

    props: {
      /**
       * 信息数据 
       */
      infoData: {
        type: Object,
        default: () => {
          return {
            "Name": "同色",
            "Describe": "--",
            "FaceSrc": "http://pic.hanfugou.com/android/2019/7/2/4941df80fb2d477b90b6c5b1eaa09c8c.jpg",
            "StaffData": {
              "model": [{
                "id": 18020,
                "name": "清修"
              }],
              "store": [{
                "id": 124120,
                "name": "砚滨纱汉服 "
              }]
            },
            "ImageCount": 9,
            "Images": null,
            "TrendShops": null,
            "Good": true,
            "GoodDatetime": "2019-08-04T22:43:58",
            "Datetime": "2019-08-02T10:13:17",
            "ID": 87586,
            "User": {
              "ID": 18020,
              "NickName": "清修",
              "HeadUrl": "http://pic.hanfugou.com/android/2019/7/2/2e8f4bfd3e4e46f8805fbd4e54c068a0.jpg",
              "MainBgPic": null,
              "AuthenticateCode": null,
              "AuthenticateName": null,
              "AuthenticateID": 0,
              "Gender": "女",
              "CityNames": null,
              "Describe": "--",
              "UserName": null,
              "AtteCount": 0,
              "ViolationCount": 0,
              "FansCount": 0,
              "UserAtte": false,
              "GoodAlbumCount": 0,
              "Popularity": 0,
              "UseHanbi": 0,
              "Close": false,
              "Black": false
            },
            "TopCount": 42,
            "CommCount": 21,
            "SaveCount": 1,
            "UserTop": false,
            "UserSave": false,
            "Huiba": null,
            "Huibas": null
          }
        }
      }
    },

  }
</script>

<style>
  /* 摄影封面 */
  .album-cover {
    height: 350rpx;
    width: 100%;
    display: block;
  }
</style>

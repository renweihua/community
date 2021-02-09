<template>
  <view class="flex bgwhite plr18r ptb18r bbs2r">
    <view class="flex-fitem w128r">
      <view class="f36r fbold c555 ellipsis" @tap="$emit('click',infoData)">{{infoData.Name}}</view>
      <view class="f28r cgray mtb18r" @tap="$emit('click',infoData)">{{calStringCut}}</view>
      <text class="huiba-tag w128r ellipsis" v-if="infoData.Huiba.Name" @tap="$emit('huiba', infoData.Huiba)">{{infoData.Huiba.Name}}</text>
    </view>
    <image class="hw200r br8r" v-if="infoData.ImageSrcs[0]" @tap="$emit('click',infoData)" :src="infoData.ImageSrcs[0]+'_200x200.jpg/format/webp'"
      mode="scaleToFill" :lazy-load="true"></image>
  </view>
</template>

<script>
  import {
    fnCutString
  } from "@/utils/CommonUtil.js"

  /**  
   * 摄影展示卡组件
   * @property {Object} infoData 信息数据  
   * @event {Function} huiba 荟吧标签 点击事件  
   * @event {Function} click 展示卡 点击事件  
   */
  export default {
    name: 'topic-card',

    props: {
      /**
       * 信息数据 
       */
      infoData: {
        type: Object,
        default: () => {
          return {
            "ID": 140912,
            "User": {
              "ID": 1096742,
              "NickName": "炽殇月落",
              "HeadUrl": "http://pic.hanfugou.com/android/2019/4/12/ca5d4903bda04b01968e4056b9667ecb.jpg",
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
            "Name": "我真的不想活了",
            "Describe": "--",
            "ImageSrcs": [
              "https://pic.hanfugou.com/android/2019/6/21/18ff2d0a992a4c629cd983a663b06d18.jpg"
            ],
            "UserCount": 4,
            "TopicFollow": false,
            "TrendCount": 20,
            "Reply": "--",
            "TrendShops": null,
            "LastDatetime": "2019-08-07T22:49:22",
            "Hot": true,
            "HotDatetime": "2019-07-22T17:12:50",
            "AdminDown": false,
            "Huiba": {
              "ID": 7262,
              "Name": "吃瓜",
              "PublicShow": false
            },
            "Datetime": "2019-07-21T08:48:37"
          }
        }
      }
    },

    computed: {
      /**
       * 内容截取字符长度65
       */
      calStringCut() {
        let content = this.infoData.Describe + ''; //转成字符串
        if (content == null || content == "" || content == "undefined" || content == "null") return;
        let {
          cutstring,
          cutflag
        } = fnCutString(content, 65);
        return cutflag ? cutstring + "..." : cutstring;
      }
    },
  }
</script>

<style></style>

<template>
  <view class="br8r mtb18r mlr18r bgwhite" style="overflow: hidden;">
    <image class="word-cover" @tap="$emit('click',infoData)" :src="infoData.FaceSrc ? infoData.FaceSrc + '_850x300.jpg/format/webp':'/static/default_image.png'"
      mode="scaleToFill" :lazy-load="true"></image>
    <view class="ptb18r plr18r">
      <view class="f36r fbold fcenter c555 ellipsis" @tap="$emit('click',infoData)">{{infoData.Title}}</view>
      <view class="f28r cgray mtb18r" @tap="$emit('click',infoData)">{{calStringCut}}</view>
      <view class="flex flex-aic">
        <user-avatar @click="$emit('user',infoData.User)" :src="infoData.User.HeadUrl ? infoData.User.HeadUrl + '_100x100.jpg' : '/static/default_avatar.png'"
          :tag="infoData.User.AuthenticateCode" size="sm"></user-avatar>
        <text class="f28r c555 ml18r flex-fitem" @tap="$emit('click',infoData)">{{infoData.User.NickName}}</text>
        <view @tap="$emit('click',infoData)">
          <i-icon type="shijian" size="32" color="#8F8F94"></i-icon>
          <text class="f24r cgray ml8r">{{calDatetime}}</text>
        </view>
      </view>
    </view>
  </view>
</template>

<script>
  import {
    fnCutString,
    fnFormatLocalDate
  } from "@/utils/CommonUtil.js"

  /**  
   * 文章展示卡组件
   * @property {Object} infoData 信息数据  
   * @event {Function} user 用户头像 点击事件  
   * @event {Function} click 展示卡 点击事件  
   */
  export default {
    name: 'word-card',

    props: {
      /**
       * 信息数据 
       */
      infoData: {
        type: Object,
        default: () => {
          return {
            "FaceSrc": "http://pic.hanfugou.com/pc/2018/12/3/20/592bac8615f6458eaadae9b99dbaa103.png",
            "Original": false,
            "Title": "【入门级知识 】 萌新入门的各种词汇解释",
            "Describe": "--",
            "Content": null,
            "TrendShops": null,
            "Good": true,
            "GoodDatetime": "2019-01-17T01:55:05",
            "Datetime": "2018-12-03T20:27:34",
            "ID": 3960,
            "User": {
              "ID": 213418,
              "NickName": "一叶无双",
              "HeadUrl": "http://pic.hanfugou.com/ios/2019/8/1/21/22/156465136216435.jpg",
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
            "TopCount": 2226,
            "CommCount": 575,
            "SaveCount": 4593,
            "UserTop": false,
            "UserSave": false,
            "Huiba": null,
            "Huibas": null
          }
        }
      }
    },

    computed: {
      /// 内容截取字符长度80 
      calStringCut() {
        let content = this.infoData.Describe + ''; //转成字符串
        if (content == null || content == "" || content == "undefined" || content == "null") return;
        let {
          cutstring,
          cutflag
        } = fnCutString(content, 80);
        return cutflag ? cutstring + "..." : cutstring;
      },
      /// 时间格式 2019-12-03 20:12 
      calDatetime() {
        let now = new Date(this.infoData.Datetime || '2020-01-01 01:01');
        return fnFormatLocalDate(now);
      }
    },

  }
</script>

<style lang="scss">
  /*封面图*/
  .word-cover {
    height: 300rpx;
    width: 100%;
    border-radius: 4rpx;
    display: block;
  }

  /*展示卡*/
  .word-card {
    border-radius: 8rpx;
    background: #FFFFFF;
    margin: 24rpx;



    /*标题*/
    .title {
      font-size: 36rpx;
      font-weight: bold;
      color: #333333;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      text-align: center;
      padding: 18rpx 24rpx;
    }

    /*内容*/
    .content {
      font-size: 28rpx;
      color: #999999;
      height: 80rpx;
      padding: 0 24rpx;
      overflow: hidden;
    }

    /*信息*/
    .info {
      display: flex;
      align-items: center;
      padding: 24rpx 24rpx;

      &-user {
        flex: 1;
        display: flex;
        align-items: center;

        .avatar {
          height: 48rpx;
          width: 48rpx;
          border-radius: 50%;
        }

        .nickname {
          margin-left: 12rpx;
          font-size: 28rpx;
          color: #666666;
        }
      }

      &-date {
        font-size: 24rpx;
        color: #999999;
      }
    }

  }
</style>

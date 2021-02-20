<template>
  <view class="bgwhite mb18r">
    <!-- 页头 -->
    <view class="flex plr18r ptb18r">
      <user-avatar @click="$emit('user', infoData.user_info)" :src="calUserAvater" tag="" size="md"></user-avatar>
      <view class="flexc-jsa ml18r mr28r flex-gitem w128r" @tap="$emit('user', infoData.user_info)">
        <view>
          <text class="f28r fbold mr18r">{{infoData.user_info.nick_name}}</text>
		  <i-icon v-if="[0, 1].indexOf(infoData.user_info.user_sex) > -1" :type="infoData.user_info.user_sex_text == '男' ? 'nan':'nv' "
		   size="28" :color="infoData.user_info.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
        </view>
        <view class="f24r cgray ellipsis">{{infoData.user_info.user_introduction || '该同袍还不知道怎么描述寄己 (╯▽╰)╭'}}</view>
      </view>
	  <!-- 如果登录会员就是发布者，那么不展示 -->
      <view v-if="!infoData.user_info.is_self" class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="$emit('follow', infoData.user_info)">{{ infoData.user_info.is_follow?'已关注':'关注'}}</view>
    </view>
    <!-- 容器 -->
    <view class="plr18r pb18r">
      <!-- 视频格 -->
      <view class="posir" @tap="$emit('click', infoData)">
        <image class="video-cover" :src="calVideoCover" :lazy-load="true" mode="aspectFill"></image>
        <image class="video-play z5" src="/static/video_play.png" :lazy-load="true" mode="aspectFit"></image>
        <view class="video-time">{{calTimeLong}}</view>
      </view>
      <!-- 标题 -->
      <view class="f32r fbold hl90r ellipsis" @tap="$emit('click', infoData)">{{infoData.dynamic_title}}</view>
      <!-- 荟吧标签 -->
      <text class="huiba-tag" @tap="$emit('huiba', infoData.Huiba)" v-if="infoData.Huiba">{{infoData.Huiba.Name}}</text>
    </view>
  </view>
  </view>
</template>

<script>
  /**  
   * 动态信息项卡片组件
   * @property {Object} infoData 项信息数据  
   * @event {Function} user 用户头像 点击事件   
   * @event {Function} follow 关注 点击事件   
   * @event {Function} huiba 荟吧标签 点击事件  
   * @event {Function} click 卡片 点击事件  
   */
  export default {
    name: 'video-card',

    props: {
      // 项信息数据
      infoData: {
        type: Object,
        default: () => {
          return {
            "Title": "【一款巨温婉的发型教程】温柔可人。百搭",
            "Describe": " -- ",
            "FaceSrc": "http://pic.hanfugou.com/ios/2019/7/31/20/40/156457682127548.jpg",
            "Source": null,
            "VideoUrl": "http://av.hanfugou.com/video/ios/2019/7/31/20/39/156457677864322.mp4",
            "WhenLong": 59,
            "Width": 1080,
            "Height": 608,
            "TrendShops": null,
            "Good": true,
            "GoodDatetime": "2019-08-07T18:53:24",
            "TaskId": "success",
            "Datetime": "2019-07-31T20:40:22",
            "ID": 33001,
            "User": {
              "ID": 317820,
              "NickName": "亦白姐姐",
              "HeadUrl": "http://pic.hanfugou.com/ios/2019/8/1/1/4/156459269928311.jpg",
              "MainBgPic": null,
              "AuthenticateCode": null,
              "AuthenticateName": null,
              "AuthenticateID": 0,
              "Gender": "女",
              "CityNames": "福建,龙岩",
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
            "TopCount": 23,
            "CommCount": 3,
            "SaveCount": 13,
            "UserTop": false,
            "UserSave": false,
            "Huiba": {
              "ID": 6921,
              "Name": "发型妆容",
              "PublicShow": false
            },
            "Huibas": null
          }
        }
      }
    },
    computed: {
      /// 计算显示用户头像
      calUserAvater() {
        let user_info = this.infoData.user_info;
        return !!user_info ? user_info.user_avatar : '/static/default_avatar.png'
      },
      /// 计算显示视频封面
      calVideoCover() {
		if (this.infoData.dynamic_type == 2){
			let cover = '/static/default_image.png';
			if (this.infoData.dynamic_images) cover = this.infoData.dynamic_images[0];
			return cover;
		}else{
			 return false;
		}
      },
      /**
       * 视频时长
       */
      calTimeLong() {
		  if(!this.infoData.video_info.times){
			  return '00:00';
		  }else return `${Math.floor(this.infoData.video_info.times / 60)}:${this.infoData.video_info.times % 60}`;
      },
    },
  }
</script>

<style>
  /*视频播放封面*/
  .video-cover {
    width: 100%;
    height: 450rpx;
    display: block;
  }

  /*视频播放icon位置大小*/
  .video-play {
    position: absolute;
    top: 40%;
    left: 40%;
    width: 100rpx;
    height: 100rpx;
  }

  /*视频播放时长*/
  .video-time {
    position: absolute;
    bottom: 18rpx;
    right: 18rpx;
    z-index: 10;
    background: rgba(0, 0, 0, .4);
    color: #FFFFFF;
    font-size: 28rpx;
    padding: 8rpx 18rpx;
    border-radius: 4rpx;
  }
</style>

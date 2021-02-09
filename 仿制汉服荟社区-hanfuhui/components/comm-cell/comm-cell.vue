<template>
  <view class="bgwhite bbs2r">
    <!-- 头部 -->
    <view class="flexr-jsb flex-aic plr18r ptb18r">
      <!-- 显示信息含时间 -->
      <view class="flex" @tap="$emit('user',infoData.user_info)">
        <user-avatar :src="calUserAvater" :tag="infoData.user_info.uuid" size="md"></user-avatar>
        <view class="flexc-jsa ml28r">
          <view>
            <text class="f28r fbold mr18r c111">{{infoData.user_info.nick_name || infoData.user_info.user_email || '#'}}</text>
            <i-icon :type="infoData.user_info.user_sex_text == '男' ?'nan':'nv' " size="28" :color="infoData.user_info.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
          </view>
          <view class="f24r cgray">{{calDatetime}}</view>
        </view>
      </view>
      <view class="w128r hl80r fcenter" @tap="$emit('top',infoData)">
        <i-icon type="dianzan" size="48" :color="infoData.UserTop?'#FF6699':'#8f8f94'"></i-icon>
        <text class="f28r ml8r cgray">{{ infoData.TopCount || '赞'}}</text>
      </view>
    </view>
    <!-- 评论及回复 -->
    <view class="comment">
      <view class="comment-content" :class="{bbs2r: infoData.replies.length > 0}" @tap="$emit('comm',infoData)">{{infoData.reply_content || '#'}}</view>
      <block v-if="infoData.replies.length > 0" v-for="(item, index) in infoData.replies" :key="index">
        <view class="comment-childs" v-if="item.user_info && index < 3">
          <text class="f28r ctheme" @tap="$emit('user',infoData.replies[index].user_info)">{{infoData.replies[index].user_info.nick_name || '#'}}</text>
          <text class="mlr8r" @tap="$emit('comm',infoData.replies[index])">回复</text>
          <text class="f28r ctheme" @tap="$emit('user',infoData.replies[index].reply_user)">{{infoData.replies[index].reply_user.nick_name || '#'}}</text>
          <text @tap="$emit('comm',infoData.replies[index])">：{{infoData.replies[index].reply_content}}</text>
        </view>
      </block>
      <view class="f28r c555 mt8r mb28r" v-if="infoData.replies.length > 3" @tap="$emit('more', infoData.reply_id)">
          <text class="mr8r">更多 {{infoData.replies.length - 3}} 条评论</text>
        <i-icon type="you" size="32" color="#8f8f94"></i-icon>
      </view>
    </view>
  </view>
</template>

<script>
  import {
    fnFormatDate
  } from "@/utils/CommonUtil.js"

  /**
   * 评论列表单元组件
   * @event {Function} top 点赞 点击事件
   * @event {Function} user 用户名 点击事件
   */
  export default {
    name: 'comm-cell',

    props: {
      // 信息数据
      infoData: {
        type: Object,
        default: () => {
          return {
          }
        }
      }
    },

    computed: {
      // 时间友好格式 几天前
      calDatetime() {
        return fnFormatDate(this.infoData.created_time);
      },
      // 计算显示用户头像
      calUserAvater() {
        let user = this.infoData.user_info;
        return !!user ? user.user_head : '/static/default_avatar.png'
      },
    },

  }
</script>

<style>
  .comment {
    margin-left: 128rpx;
    padding-left: 18rpx;
  }

  .comment-content {
    font-size: 28rpx;
    color: #333;
    line-height: 56rpx;
    font-weight: bold;
  }

  .comment-childs {
    font-size: 24rpx;
    line-height: 42rpx;
    color: #333;
    padding-right: 18rpx;
  }
</style>

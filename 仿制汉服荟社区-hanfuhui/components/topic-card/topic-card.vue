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

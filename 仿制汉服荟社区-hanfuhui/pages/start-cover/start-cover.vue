<template>
  <view class="posia posi-all0 z999 bgwhite anima-punch-in3">
    <!-- 封面 -->
    <image class="cover" :src="starCover.ImgSrc" mode="aspectFill"></image>
    <!-- 封面发布者 -->
    <text class="cover-publisher posif br8r f32r cwhite bgblack-a3" v-if="starCover.Title">©{{starCover.Title}}</text>
    <!-- 底部logo -->
    <image class="cover-logo" src="/static/default_brand.png" mode="aspectFit"></image>
  </view>
</template>

<script>
  export default {

    computed: {
      starCover() {
        let data = this.$store.getters['common/getStarCoverData'];
        // 不存在时显示默认
        if (typeof data == 'undefined') {
          data = {
            "Title": "汉服荟",
            // "ImgSrc": "https://pic.hanfugou.com/web/2019/8/19/9/5952c600bfa24db498f9f5396da75a5a.png",
            "ImgSrc": "/static/default_launch.jpg"
          }
        } else {
          if (data.ImgSrc.indexOf('_min.jpg/format/webp') == -1) data.ImgSrc += '_min.jpg/format/webp'
        }
        return data
      }
    }

  }
</script>

<style>
  /* 启动封面图 */
  .cover {
    width: 100%;
    height: 80%;
  }

  .cover-publisher {
    left: 5%;
    top: 74%;
    padding: 4rpx 18rpx;
    animation: fly-in 0.6s;
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  .cover-logo {
    width: 100%;
    height: 112rpx;
    margin: 64rpx auto 0;
  }
</style>

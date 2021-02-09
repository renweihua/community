<template>
  <view class="posir" :class="calAvatarSize.c" :style="calAvatarSize.s" @tap="$emit('click')">
    <image class="hw100v" :class="[square ? 'br8r':'br50v']" :src="src" mode="scaleToFill" :lazy-load="true"></image>
    <image class="posia posi-br0 br50v" v-if="tag" :style="calTagSizeStyle" :src="calTagSrc" mode="aspectFit"
      :lazy-load="true"></image>
  </view>
</template>

<script>
  /**  
   * 用户头像组件
   * @property {Object} size 大小 sm md lg Number  
   * @property {Object} square 是否矩形形状 默认圆形  
   * @property {Object} src 图片地址  
   * @property {Object} tag 标签  
   * @event {Function} click 点击事件  
   */
  export default {
    name: 'user-avatar',

    props: {
      /**
       * 大小 sm md lg Number
       */
      size: {
        validator: val => {
          return typeof val === 'number' || ['sm', 'lg', 'md'].includes(val);
        },
        default: 'md'
      },
      /**
       * 是否矩形形状 默认圆形
       */
      square: {
        type: Boolean,
        default: false
      },
      /**
       * 图片地址
       */
      src: {
        type: String,
        default: '/static/default_avatar.png'
      },
      /**
       * 标签
       */
      tag: {
        type: String,
        default: ''
      }
    },

    computed: {
      // 计算头像框大小
      calAvatarSize() {
        let size = {
          c: '',
          s: ''
        }
        if (typeof this.size === 'number') {
          size.s = `width:${this.size}rpx;height:${this.size}rpx;`
        } else {
          if (this.size == 'sm') size.c = 'hw64r'
          if (this.size == 'md') size.c = 'hw96r'
          if (this.size == 'lg') size.c = 'hw128r'
        }
        return size
      },
      // 计算标签大小
      calTagSizeStyle() {
        let num = 0;
        if (typeof this.size === 'number') {
          num = Math.floor(this.size / 3)
        } else {
          if (this.size == 'sm') num = 24
          if (this.size == 'md') num = 32
          if (this.size == 'lg') num = 42
        }
        return `height: ${num}rpx;width: ${num}rpx;`
      },
      // 计算改变标签铭牌
      calTagSrc() {
        // console.log(this.tag);
        return `/static/icon-avatar/icon_${this.tag}.png`
      }
    }
  }
</script>

<style></style>

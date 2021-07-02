<template>
  <view class="posif posi-blr0 h112r bgwhite cblack bts2r flex z500" :style="setColorStyle">
    <!-- 我的消息气泡 -->
    <text class="my-bubble" v-if="bubble">{{bubble}}</text>
    <!-- 列表遍历 -->
    <block v-for="(item,index) in parameter.list" :key="index">
      <view class="flex-fitem flexc-jsc flex-aic" @tap="$emit('current', index)">
        <image :class="[ item.text ?'hw64r' : 'hw96r']" :src="current==index ? item.selectedIcon : item.icon" mode="widthFix"></image>
        <view class="f24r cgray" :style="{color:  current==index ? parameter.selectedColor :parameter.color}">{{item.text}}</view>
      </view>
    </block>
  </view>
</template>

<script>
  /**  
   * 底部导航栏组件
   * @property {Number} bubble 消息气泡 - 默认0  
   * @property {Number} current 选中值 - 默认0，第一项  
   * @property {Object} parameter 导航栏参数配置  
   * @event {Function} click 底部导航栏项 点击事件  
   */
  export default {
    name: 'bottom-nav',

    props: {
      /**
       * 消息气泡
       */
      bubble: {
        type: Number,
        default: 0
      },
      /**
       * 选中值
       */
      current: {
        type: Number,
        default: 0
      },
      /**
       * 导航栏参数配置 
       */
      parameter: {
        type: Object,
        default: () => {
          return {
            // 文字颜色
            "color": "#8E8E8E",
            // 选中文字颜色
            "selectedColor": "#000000",
            // 背景颜色
            "backgroundColor": "#FFFFFF",
            // 底部上边线颜色
            borderColor: "#EEEEEE",
            // 导航项
            "list": [{
                "text": "首页",
                "icon": "",
                "selectedIcon": ""
              },
              {
                "text": "",
                "icon": "",
                "selectedIcon": ""
              },
              {
                "text": "我的",
                "icon": "",
                "selectedIcon": ""
              }
            ],
            //
          }
        }
      }
    },

    computed: {
      /**
       * 上边线背景颜色样式
       */
      setColorStyle() {
        let colorStyle = '';
        // 背景颜色
        if (this.parameter.backgroundColor) {
          colorStyle += `background:${this.parameter.backgroundColor};`;
        }
        // 底部上边线颜色
        if (this.parameter.borderColor) {
          colorStyle += `border-top: 2rpx ${this.parameter.borderColor} solid;`;
        }
        return colorStyle;
      }
    }
  }
</script>

<style>
  /* 我的消息气泡 */
  .my-bubble {
    display: block;
    position: absolute;
    top: 8rpx;
    right: 20rpx;
    font-size: 24rpx;
    text-align: center;
    padding: 4rpx 12rpx;
    border-radius: 50%;
    overflow: hidden;
    color: #FFFFFF;
    background-color: #FF6699;
  }
</style>

<template>
  <view class="icon" :class="'icon-'+type" :style="setStyle" @tap="$_click"></view>
</template>

<script>
  /**  
   * iconfont 官方自定义收藏图标库组件  
   * @property {String} type 图标图案类型 - 列如：dianzan  
   * @property {String} color 图标颜色 - 默认白色  
   * @property {Number} size 图标大小 - 默认32，单位rpx  
   * @event {Function} click 图标组件点击事件  
   */
  export default {
    name: 'i-icon',

    props: {
      /**
       * 图标类型
       */
      type: String,
      /**
       * 图标颜色
       */
      color: {
        type: String,
        default: '#fff'
      },
      /**
       * 图标大小
       */
      size: {
        type: [Number, String],
        default: '32'
      }
    },

    computed: {
      setStyle() {
        return `color:${this.color};
				font-size:${this.size}rpx;
				line-height:${this.size}rpx`
      }
    },

    methods: {
      $_click(e) {
        this.$emit('click', e)
      }
    }
  }
</script>

<style>
  @font-face {
    font-family: "icon";
    src: url('data:application/x-font-woff2;charset=utf-8;base64,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') format('woff2')
  }

  /*换上面src里的url，需要woff的base64和ttf的文件引入，这里的icon与下面的icon-对应*/
  .icon {
    display: inline;
    vertical-align: middle;
    letter-spacing: normal;
    word-spacing: normal;
    font-family: "icon" !important;
    font-size: 32rpx;
    font-style: normal;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  /* 往下都是图标的字体编码内容，icon为前缀 */
  .icon-dianzan:before {
    content: "\e601";
  }

  .icon-queding:before {
    content: "\e615";
  }

  .icon-dingdan:before {
    content: "\e613";
  }

  .icon-quxiao:before {
    content: "\e630";
  }

  .icon-taolun:before {
    content: "\e63f";
  }

  .icon-shouji:before {
    content: "\e64f";
  }

  .icon-gou:before {
    content: "\e616";
  }

  .icon-gengduo:before {
    content: "\e618";
  }

  .icon-jinru:before {
    content: "\e627";
  }

  .icon-quan:before {
    content: "\e741";
  }

  .icon-nv:before {
    content: "\e666";
  }

  .icon-nan:before {
    content: "\e646";
  }

  .icon-bianji:before {
    content: "\e696";
  }

  .icon-aite:before {
    content: "\e619";
  }

  .icon-shoucang:before {
    content: "\e60a";
  }

  .icon-fenxiang:before {
    content: "\e626";
  }

  .icon-biaoqing:before {
    content: "\e63e";
  }

  .icon-fabu:before {
    content: "\e71f";
  }

  .icon-remen:before {
    content: "\e6be";
  }

  .icon-xia:before {
    content: "\e614";
  }

  .icon-yanzhengma:before {
    content: "\e6ba";
  }

  .icon-fuzhi:before {
    content: "\e6d2";
  }

  .icon-zuo:before {
    content: "\e640";
  }

  .icon-you:before {
    content: "\e720";
  }

  .icon-shijian:before {
    content: "\e62d";
  }

  .icon-mimakejian:before {
    content: "\e637";
  }

  .icon-mima:before {
    content: "\e61b";
  }

  .icon-pinglun:before {
    content: "\e665";
  }

  .icon-yuanbao:before {
    content: "\e697";
  }

  .icon-mimabukejian:before {
    content: "\e698";
  }

  .icon-sousuo:before {
    content: "\e66b";
  }

  .icon-weizhi:before {
    content: "\e634";
  }

  .icon-gouwuche:before {
    content: "\e612";
  }

  .icon-jubao:before {
    content: "\e60b";
  }

  .icon-quan-cuo:before {
    content: "\e711";
  }

  .icon-quan-dui:before {
    content: "\e712";
  }
</style>

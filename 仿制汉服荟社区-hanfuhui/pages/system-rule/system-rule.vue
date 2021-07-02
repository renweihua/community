<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni :down="{use:false}" :up="{use:false}">
      <!-- 规则标题 -->
      <view class="f36r fbold fcenter mt64r mb64r">{{ruleTitle}}</view>
      <!-- 内容 -->
      <view class="f28r c111 fword plr18r ptb18r mb64r">
        <u-parse :content="content" :loading="!content" :img-options="false"></u-parse>
      </view>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    getSysParamValue,
  } from "@/api/CommonServer.js"

  // HTML富文本解析器
  import uParse from '@/components/gaoyia-parse/parse.vue'

  export default {
    components: {
      uParse
    },

    data() {
      return {
        title: '',
        ruleTitle: '',
        content: ''
      }
    },

    onLoad(options) {
      if (options && options.key) {
        if (options.key == 'hui_rule') this.title = '社区公约'
        if (options.key == 'helper') this.title = '帮助中心'
        if (options.key == 'hui_privacy') this.title = '隐私政策'
        // 获取信息
        uni.showLoading({
          title: '加载中',
          mask: true
        })
        getSysParamValue(options.key).then(res => {
          this.ruleTitle = res.data.Data.Describe
          this.content = res.data.Data.Value
          // 导航栏标题
          uni.setNavigationBarTitle({
            title: this.ruleTitle
          });
          setTimeout(() => {
            uni.hideLoading()
          }, 2000)
        })
      }
    },
  }
</script>

<style>
  page {
    background: #FFFFFF;
  }
</style>

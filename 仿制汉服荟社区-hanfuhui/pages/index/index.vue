<template>
  <view>
    <!-- 主页 -->
    <home v-if="status.home" :style="{display: current==0 ? 'block' :'none'}" :refresh="current==0 && clickRefresh"></home>
    <!-- 发现 -->
    <find v-if="status.find" :style="{display: current==1 ? 'block' :'none'}" :refresh="current==1 && clickRefresh"></find>
    <!-- 商城 -->
    <shop v-if="status.shop" :style="{display: current==3 ? 'block' :'none'}" :refresh="current==3 && clickRefresh"></shop>
    <!-- 我的 -->
    <my v-if="status.my" :style="{display: current==4 ? 'block' :'none'}" :refresh="current==4 && clickRefresh"></my>

    <!-- 发布 -->
    <release v-if="status.release" @close="status.release = false"></release>

    <!-- 启动封面 -->
    <start-cover v-if="status.start"></start-cover>

    <!-- 底部导航组件 -->
    <bottom-nav :bubble="bubble" :current="current" :parameter="bottomNav" @current="fnCurrent"></bottom-nav>
  </view>
</template>

<script>
  // 底部导航栏组件
  import BottomNav from '@/components/bottom-nav/bottom-nav'
  // 底部导航栏组件-主页
  import Home from '@/pages/home/home'
  // 底部导航栏组件-发现
  import Find from '@/pages/find/find'
  // 底部导航栏组件-我的
  import My from '@/pages/my/my'
  // 底部导航栏组件-商城
  import Shop from '@/pages/shop/shop'
  // 底部导航栏组件-发布
  import Release from '@/pages/release/release'
  // 启动封面组件
  import StartCover from '@/pages/start-cover/start-cover'
  import {
    getMessageNoReadCount,
  } from "@/api/MessageServer.js"

  export default {
    components: {
      BottomNav,
      Home,
      Find,
      Shop,
      My,
      Release,
      StartCover
    },
    data() {
      return {
        // 底部导航选中值
        current: 0,
        // 底部导航栏参数
        bottomNav: {
          // 文字颜色
          "color": "#8E8E8E",
          // 选中文字颜色
          "selectedColor": "#FF6699",
          // 背景颜色
          "backgroundColor": "#FFFFFF",
          // 底部上边线颜色
          borderColor: "#EEEEEE",
          // 导航项
          "list": [{
              "text": "主页",
              "icon": "/static/icon-bottom-nav/home_normal.png",
              "selectedIcon": "/static/icon-bottom-nav/home_select.png"
            },
            {
              "text": "发现",
              "icon": "/static/icon-bottom-nav/find_normal.png",
              "selectedIcon": "/static/icon-bottom-nav/find_select.png"
            },
            {
              "text": "", // 单图标时文字留空
              "icon": '/static/icon-bottom-nav/send.png',
              "selectedIcon": '/static/icon-bottom-nav/send.png'
            },
            {
              "text": "商城",
              "icon": "/static/icon-bottom-nav/shop_normal.png",
              "selectedIcon": "/static/icon-bottom-nav/shop_select.png"
            },
            {
              "text": "我的",
              "icon": "/static/icon-bottom-nav/my_normal.png",
              "selectedIcon": "/static/icon-bottom-nav/my_select.png"
            }
          ]
        },
        // 激活底部导航关联页状态
        status: {
          home: false,
          find: false,
          shop: false,
          my: false,
          release: false,
          start: true
        },
        // 双击刷新
        clickRefresh: false,
        // 刷新间隔
        timeOut: 0,
        //
      }
    },

    // 初始跳转
    onLoad(option) {
      // 带参数时改变选中项关闭启动封面
      if (option && option.current) {
        this.current = parseInt(option.current);
        this.status.start = false;
        this.status[['home', 'find', 'shop', 'my'][this.current]] = true;
        return
      }
      // 定时关闭启动页
      setTimeout(() => {
        this.status[['home', 'find', 'shop', 'my'][this.current]] = true;
        this.status.start = false;
      }, 5000);
    },

    onShow() {
      // 获取聊天设置状态
      getMessageNoReadCount().then(res => {
        this.$store.commit('setNewsCountData', res.data.Data)
      })
    },

    computed: {
      // 消息气泡
      bubble() {
        return this.$store.getters['getNewsTotalData'];
      }
    },

    methods: {
      /**
       * 底部导航选中项
       * @param {Object} index 选择list项下标
       */
      fnCurrent(index) {
        // 发布选择
        if (index == 2) return this.status.release = true;
        // 是否当前项点击
        if (this.current == index) {
          this.timeOut += 1;
          // 是否为刷新值和连续触发
          if (!this.clickRefresh && this.timeOut >= 2) {
            // 刷新值开和定时器重置
            this.clickRefresh = true;
            this.timeOut = setTimeout(() => {
              // 清除定时器
              clearTimeout(this.timeOut)
              // 连续触发记录重置
              this.timeOut = 0;
              // 刷新值关
              this.clickRefresh = false;
            }, 5000);
          }
          return;
        } else {
          let text = this.bottomNav.list[index].text;
          // 是否中间单图标点击 
          if (text) {
            this.current = index;
            uni.setNavigationBarTitle({
              title: text
            });
          }
          // 首次选中激活底部导航关联页状态
          if (!this.status.find && index == 1) this.status.find = true;
          if (!this.status.shop && index == 3) this.status.shop = true;
          if (!this.status.my && index == 4) this.status.my = true;
          // 清除定时器
          clearTimeout(this.timeOut)
          // 连续触发记录重置
          this.timeOut = 0;
          // 刷新值关
          this.clickRefresh = false;
        }
      },
      //
    }
  }
</script>

<style>
</style>

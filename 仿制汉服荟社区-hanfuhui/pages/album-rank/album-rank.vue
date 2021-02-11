<template>
  <view>
    <!-- 顶部导航栏 -->
    <view class="posif posi-tlr0 bgf8 z500">
      <view id="tabbar" class="flexr-jsa flex-ais hl80r bgwhite bbs2r">
        <view class="f32r fbold fcenter w128r tabbar" :class="{'tabbarsh':current == 1}" @tap="fnBarClick(1)">周榜单</view>
        <view class="f32r fbold fcenter w128r tabbar" :class="{'tabbarsh':current == 2}" @tap="fnBarClick(2)">月榜单</view>
      </view>
    </view>

    <!-- 滚动内容区 -->
    <mescroll-uni :top="calTop" :down="{use:false}" @up="upCallback" @init="mescrollInit">
      <!-- 周榜 -->
      <view v-if="status.week" :style="{display: current==1 ? 'block' :'none'}">
        <view class="flexr-jsb flex-fww ptb18r plr18r bgwhite" v-if="rankWeekListData.length">
          <view v-for="(album,index) in rankWeekListData" :key="index" class="w48v-mb2v">
            <album-card :info-data="album" @user="fnUserInfo" @click="fnAlbumInfo"></album-card>
          </view>
        </view>
      </view>
      <!-- 月榜 -->
      <view v-if="status.month" :style="{display: current==2 ? 'block' :'none'}">
        <view class="flexr-jsb flex-fww ptb18r plr18r bgwhite" v-if="rankMonthListData.length">
          <view v-for="(album,index) in rankMonthListData" :key="index" class="w48v-mb2v">
            <album-card :info-data="album" @user="fnUserInfo" @click="fnAlbumInfo"></album-card>
          </view>
        </view>
      </view>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    getRankList
  } from "@/api/AlbumServer.js"

  // 摄影展示卡组件
  import AlbumCard from '@/components/album-card/album-card'
  
  export default {
    components:{
      AlbumCard
    },
    data() {
      return {
        // 榜项选中 
        current: 1,
        // 激活顶部导航关联页状态
        status: {
          week: true,
          month: false,
        },
        // mescroll组件实例
        mescroll: null,
        // 双击刷新
        clickRefresh: false,
        // 刷新间隔
        timeOutRank: 0,
      }
    },

    onLoad(options) {
      if (options && options.current) {
        this.current = parseInt(options.current)
        if(this.current == 2) this.status.month = true
      }
    },

    computed: {
      // 周榜数据
      rankWeekListData() {
        return this.$store.getters['album/getRankWeekListData']
      },
      // 月榜数据
      rankMonthListData() {
        return this.$store.getters['album/getRankMonthListData']
      },
      // 固定H5
      calTop(){
        let top = 80;
        // #ifdef H5
        top = 250;
        // #endif
        return top;
      }
      //
    },

    methods: {
      /// mescroll组件初始化完成的回调
      mescrollInit(mescroll) {
        this.mescroll = mescroll;
      },
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        let params = {
          ranktype: this.current,
          page: mescroll.num,
          count: mescroll.size
        }
        // 获取摄影榜数据
        getRankList(params).then(rankRes => {
          if (this.current == 1) {
            if (mescroll.num == 1) {
              this.$store.commit('album/setRankWeekListData', rankRes.data.Data)
            } else {
              this.$store.commit('album/setRankWeekListData', this.rankWeekListData.concat(rankRes.data.Data))
            }
          }
          if (this.current == 2) {
            if (mescroll.num == 1) {
              this.$store.commit('album/setRankMonthListData', rankRes.data.Data)
            } else {
              this.$store.commit('album/setRankMonthListData', this.rankMonthListData.concat(rankRes.data.Data))
            }
          }
          mescroll.endSuccess(rankRes.data.Data.length, rankRes.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      
      /// 顶部导航选项点击
      fnBarClick(current) {
        // 是否当前项点击
        if (this.current == current) {
          this.timeOutRank += 1;
          // 是否为刷新值和连续触发
          if (!this.clickRefresh && this.timeOutRank >= 2) {
            // 刷新值开
            this.clickRefresh = true;
            // 获取新数据
            this.mescroll.resetUpScroll();
            // 定时器重置
            this.timeOutRank = setTimeout(() => {
              // 清除定时器
              clearTimeout(this.timeOutRank)
              // 连续触发记录重置
              this.timeOutRank = 0;
              // 刷新值关
              this.clickRefresh = false;
            }, 5000);
          }
        } else {
          // 改变顶部导航选中
          this.current = current;
          // 首次选中激活顶部导航关联页状态
          if (!this.status.month && this.current == 2) this.status.month = true;
          // 获取新数据
          this.mescroll.resetUpScroll();
          // 清除定时器
          clearTimeout(this.timeOutRank)
          // 连续触发记录重置
          this.timeOutRank = 0;
          // 刷新值关
          this.clickRefresh = false;
        }
        // 滚动上滑 
        this.mescroll.scrollTo(0, 300);
      },

      /// 跳转用户信息页
      fnUserInfo(e) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${e.ID}`
        })
      }, 
      /// 跳转摄影详情页
      fnAlbumInfo(e) {
        uni.navigateTo({
          url: `/pages/album-details/album-details?id=${e.ID}&fromPage=rank&current=${this.current}`
        })
      },
      //
    }, 
  }
</script>

<style> 
</style>

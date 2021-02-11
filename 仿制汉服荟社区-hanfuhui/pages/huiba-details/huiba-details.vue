<template>
  <view>
    <!-- 滚动列表内容 -->
    <mescroll-uni :down="{use:false}" :up="{onScroll:true}" @up="upCallback" @scroll="scroll" @init="mescrollInit">
      <!-- 荟吧背景封面 -->
      <view class="posir" :class="{mb18r: !huibaTopData}">
        <image class="huiba-cover" :src="calHuibaFacePic" mode="aspectFill"></image>
        <view class="posia posi-blr0">
          <view class="flex plr28r ptb18r">
            <image class="hw128r br8r" style="overflow: hidden;" :src="calHuibaIcon" mode="aspectFill"></image>
            <view class="flex-fitem flexc-jsa plr18r">
              <view class="f32r cwhite">{{huibaInfoData.Name}}</view>
              <view class="f24r cwhite">
                {{huibaInfoData.FollowCount || 0}} 人关注
                <text class="mlr8r">|</text>
                {{huibaInfoData.TrendCount || 0}} 条动态
              </view>
            </view>
            <view class="bgwhite f28r fcenter w128r br8r ptb8r flex-asc" :class="[huibaInfoData.HuibaFollow ? 'cgray':'ctheme']"
              @tap="fnHuibaFollow">{{huibaInfoData.HuibaFollow?'已关注':'关注'}}</view>
          </view>
          <view class="plr28r ptb18r bgblack-a3 f28r cwhite">{{huibaInfoData.Describe}}</view>
        </view>
      </view>
      <!-- 置顶公告 -->
      <view class="plr28r bgwhite mb18r" v-if="huibaTopData">
        <block v-for="top in huibaTopData" :key="top.ID">
          <view class="flex flex-aic bbs2r">
            <view class="f24r bgtheme cwhite ptb8r plr18r br4r mr18r">置顶</view>
            <view class="f28r c111 ellipsis hl80r flex-fitem">{{top.Title}}</view>
          </view>
        </block>
      </view>

      <!-- 选择导航 -->
      <view v-if="isFixed" class="hl90r bgwhite"></view>
      <view id="tabbar" class="flexr-jsa flex-ais hl90r bgwhite bbs2r" :class="{'tabbar-fixed':isFixed}">
        <view class="f32r fbold fcenter w128r tabbar" :class="{'tabbarsh':current == 0}" @tap="fnBarClick(0)">热门</view>
        <view class="f32r fbold fcenter w128r tabbar" :class="{'tabbarsh':current == 1}" @tap="fnBarClick(1)">最新</view>
      </view>
      <!-- 最热 -->
      <view v-if="status.hottest" :style="{display: current==0 ? 'block' :'none'}">
        <block v-for="(infoData,index) in huibaHottestListData" :key="index">
          <trend-card :info-data="infoData" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop"
            @comm="fnCardComm" @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
        </block>
      </view>
      <!-- 最新 -->
      <view v-if="status.latest" :style="{display: current==1 ? 'block' :'none'}">
        <block v-for="(infoData,index) in huibaLatestListData" :key="index">
          <trend-card :info-data="infoData" @click="fnCardInfo" @user="fnCardUser" @huiba="fnCardHuiba" @top="fnCardTop"
            @comm="fnCardComm" @save="fnCardSave" @follow="fnCardFollow" @black="fnCardBlack" @report="fnCardReport"></trend-card>
        </block>
      </view>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    getHuibaInfo,
    getHuibaTop,
    getHuibaTrend,
    addHuibaFollows,
    delHuibaFollows
  } from "@/api/HuibaServer.js"
  import {
    addUserAtte,
    delUserAtte,
    addUserBlack,
    delUserBlack,
  } from "@/api/UserServer.js"
  import {
    addTop,
    delTop,
    addSave,
    delSave
  } from "@/api/InteractServer.js"

  // 动态信息项卡片组件
  import TrendCard from '@/components/trend-card/trend-card'

  export default {
    components: {
      TrendCard
    },
    data() {
      return {
        // 选中 最热
        current: 0,
        // 激活顶部导航关联页状态
        status: {
          hottest: true,
          latest: false,
        },
        // 滚动动实例
        mescroll: null,
        // 荟吧id
        id: 0,
        maxID: [-1, -1],
        // 双击刷新
        clickRefresh: false,
        // 刷新间隔
        timeOutHuiba: 0,
        // 导航距离顶部
        tabbarTop: 0,
        // 是否固定导航
        isFixed: false,
        // 距离顶部达到导航距离
        topNum: 0,
        //
      };
    },

    onLoad(option) {
      if (option && option.id) {
        uni.showLoading({
          title: "加载中",
          mask: true
        })
        this.id = parseInt(option.id);
        // 获取荟吧信息和置顶信息
        Promise.all([
          getHuibaInfo(this.id),
          getHuibaTop(this.id)
        ]).then(resArray => {
          this.$store.commit('huiba/setHuibaInfoData', resArray[0].data.Data)
          this.$store.commit('huiba/setHuibaTopData', resArray[1].data.Data)
          // 导航标题
          uni.setNavigationBarTitle({
            title: resArray[0].data.Data.Name
          });
        })
        // 等待一秒页面渲染,$nextTick使用不能准确
        setTimeout(() => {
          uni.hideLoading()
          // 获取导航条距顶部高度
          this.setTabbarTop();
        }, 1500);
      }
    },

    computed: {
      // 荟吧信息
      huibaInfoData() {
        return this.$store.getters['huiba/getHuibaInfoData']
      },
      // 荟吧置顶公告信息
      huibaTopData() {
        return this.$store.getters['huiba/getHuibaTopData']
      },
      // 荟吧最新信息
      huibaLatestListData() {
        return this.$store.getters['huiba/getHuibaLatestListData']
      },
      // 荟吧最热信息
      huibaHottestListData() {
        return this.$store.getters['huiba/getHuibaHottestListData']
      },
      /// 计算荟吧icon图
      calHuibaIcon() {
        let faceUrl = this.huibaInfoData.FaceUrl;
        return !!faceUrl ? faceUrl + '_200x200.jpg' : '/static/default_avatar.png'
      },
      /// 计算荟吧背景封面
      calHuibaFacePic() {
        let faceUrl = this.huibaInfoData.FaceUrl;
        return !!faceUrl ? faceUrl + '_500x200.blur.jpg/format/webp' : '/static/default_image.png'
      },
    },

    methods: {
      /// mescroll组件初始化的回调,可获取到mescroll对象
      mescrollInit(mescroll) {
        this.mescroll = mescroll;
      },
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        let params = {
          huibaid: this.id,
          ishot: this.current == 0,
          max_id: this.maxID[this.current],
          page: mescroll.num,
          count: mescroll.size
        }
        getHuibaTrend(params).then(res => {
          // 最热
          if (this.current == 0) {
            if (mescroll.num == 1) {
              this.$store.commit('huiba/setHuibaHottestListData', res.data.Data);
            } else {
              this.$store.commit('huiba/setHuibaHottestListData', this.huibaHottestListData.concat(res.data.Data))
            }
          }
          // 最新
          if (this.current == 1) {
            if (mescroll.num == 1) {
              this.$store.commit('huiba/setHuibaLatestListData', res.data.Data);
            } else {
              this.$store.commit('huiba/setHuibaLatestListData', this.huibaLatestListData.concat(res.data.Data))
            }
          }
          this.maxID[this.current] = res.data.Data[0].ID
          mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })

      },
      /// 滚动事件 (需在up配置onScroll:true才生效)
      scroll(mescroll) {
        this.topNum = mescroll.getScrollTop()
        if (mescroll.getScrollTop() >= this.tabbarTop) {
          this.isFixed = true // 显示悬浮菜单
        } else {
          this.isFixed = false // 隐藏悬浮菜单 
        }
      },
      /// 设置nav到顶部的距离 (滚动条为0, 菜单顶部的数据加载完毕获取到的数值是最精确的)
      setTabbarTop() {
        let view = uni.createSelectorQuery().in(this).select('#tabbar');
        view.boundingClientRect(data => {
          // 到屏幕顶部的距离
          this.tabbarTop = data.top
        }).exec();
      },

      /// 顶部导航选项点击
      fnBarClick(current) {
        // 是否当前项点击
        if (this.current == current) {
          this.timeOutHuiba += 1;
          // 是否为刷新值和连续触发
          if (!this.clickRefresh && this.timeOutHuiba >= 2) {
            // 刷新值开
            this.clickRefresh = true;
            // 获取新数据
            this.mescroll.resetUpScroll();
            // 定时器重置
            this.timeOutHuiba = setTimeout(() => {
              // 清除定时器
              clearTimeout(this.timeOutHuiba)
              // 连续触发记录重置
              this.timeOutHuiba = 0;
              // 刷新值关
              this.clickRefresh = false;
            }, 5000);
          }
        } else {
          // 改变顶部导航选中
          this.current = current;
          // 首次选中激活顶部导航关联页状态
          if (!this.status.latest && this.current == 1) this.status.latest = true;
          // 获取新数据
          this.mescroll.resetUpScroll();
          // 清除定时器
          clearTimeout(this.timeOutHuiba)
          // 连续触发记录重置
          this.timeOutHuiba = 0;
          // 刷新值关
          this.clickRefresh = false;
        }
        // 滚动上滑
        this.mescroll.scrollTo(this.tabbarTop, 300);
      },

      /// 荟吧用户关注
      fnHuibaFollow() {
        // 荟吧被关注
        if (this.huibaInfoData.HuibaFollow) {
          uni.showModal({
            content: '确定要取消关注吗？',
            success: res => {
              if (res.confirm) {
                delHuibaFollows(this.huibaInfoData.ID).then(delRes => {
                  if (delRes.data.Data == false) return
                  this.huibaInfoData.FollowCount--
                  this.huibaInfoData.HuibaFollow = false
                })
              }
            }
          })
          return
        } else {
          addHuibaFollows(this.huibaInfoData.ID).then(addRes => {
            if (addRes.data.Data == false) return
            this.huibaInfoData.FollowCount++
            this.huibaInfoData.HuibaFollow = true
          })
        }
      },


      /// 展卡跳转详情页
      fnCardInfo(e) {
        console.log(e.ObjectType);
        if (e.ObjectType == 'trend') {
          uni.navigateTo({
            url: `/pages/trend-details/trend-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}`
          })
          return
        }
        if (e.ObjectType == 'album') {
          uni.navigateTo({
            url: `/pages/album-details/album-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}`
          })
          return
        }
        if (e.ObjectType == 'topic') {
          uni.navigateTo({
            url: `/pages/topic-details/topic-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}`
          })
          return
        }
        if (e.ObjectType == 'topicreply') {
          uni.navigateTo({
            url: `/pages/topicreply-details/topicreply-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}`
          })
          return
        }
        if (e.ObjectType == 'video') {
          uni.navigateTo({
            url: `/pages/video-details/video-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}`
          })
          return
        }
        if (e.ObjectType == 'word') {
          uni.navigateTo({
            url: `/pages/word-details/word-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}`
          })
          return
        }
      },
      /// 展卡评论跳转详情页
      fnCardComm(e) {
        console.log(e.ObjectType);
        if (e.ObjectType == 'trend') {
          uni.navigateTo({
            url: `/pages/trend-details/trend-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}&comm=true`
          })
          return
        }
        if (e.ObjectType == 'album') {
          uni.navigateTo({
            url: `/pages/album-details/album-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}&comm=true`
          })
          return
        }
        if (e.ObjectType == 'topic') {
          uni.navigateTo({
            url: `/pages/topic-details/topic-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}&comm=true`
          })
          return
        }
        if (e.ObjectType == 'topicreply') {
          uni.navigateTo({
            url: `/pages/topicreply-details/topicreply-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}&comm=true`
          })
          return
        }
        if (e.ObjectType == 'video') {
          uni.navigateTo({
            url: `/pages/video-details/video-details?id=${e.ObjectID}&fromPage=huiba&current=${this.current}&comm=true`
          })
          return
        }
      },
      /// 展卡跳转用户中心页 
      fnCardUser(e) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${e.User.ID}`
        })
      },
      /// 展卡跳转荟吧页
      fnCardHuiba(e) {
        if (e.ID == this.id) return
        uni.navigateTo({
          url: `/pages/huiba-details/huiba-details?id=${e.ID}`
        })
      },
      /// 展卡点赞 
      fnCardTop(e) {
        let filItem = {};
        // 最热
        if (this.current == 0) filItem = this.huibaHottestListData.filter(item => item.ObjectID == e.ObjectID)[0];
        // 最新
        if (this.current == 1) filItem = this.huibaLatestListData.filter(item => item.ObjectID == e.ObjectID)[0];
        let params = {
          objecttype: filItem.ObjectType,
          objectid: filItem.ObjectID
        }
        // 用户是否点过赞
        if (filItem.UserTop) {
          delTop(params).then(delRes => {
            if (delRes.data.Data == false) return
            filItem.TopCount--;
            filItem.UserTop = false
          })
        } else {
          addTop(params).then(addRes => {
            if (addRes.data.Data == false) return
            filItem.TopCount++;
            filItem.UserTop = true
          })
        }
      },
      /// 展卡收藏
      fnCardSave(e) {
        let filItem = {};
        // 最热
        if (this.current == 0) filItem = this.huibaHottestListData.filter(item => item.ObjectID == e.ObjectID)[0];
        // 最新
        if (this.current == 1) filItem = this.huibaLatestListData.filter(item => item.ObjectID == e.ObjectID)[0];
        let params = {
          objectid: filItem.ObjectID,
          objecttype: filItem.ObjectType
        }
        // 用户是否已收藏
        if (filItem.UserSave) {
          delSave(params).then(delRes => {
            if (delRes.data.Data == false) return
            filItem.SaveCount--;
            filItem.UserSave = false
          })
        } else {
          addSave(params).then(addRes => {
            if (addRes.data.Data == false) return
            filItem.SaveCount++;
            filItem.UserSave = true
          })
        }
      },
      /// 展卡更多-关注
      fnCardFollow(e) {
        // 用户被关注
        if (e.User.UserAtte) {
          uni.showModal({
            content: '确定要取消关注TA吗？',
            success: res => {
              if (res.confirm) {
                delUserAtte(e.User.ID).then(delRes => {
                  if (delRes.data.Data == false) return
                  this.huibaHottestListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
                    false)
                  this.huibaLatestListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
                    false)
                  // 登录用户关注数减
                  let tempUser = this.$store.getters['user/getUserInfoData']
                  tempUser.AtteCount--
                  this.$store.commit('user/setUserInfoData', tempUser)
                })
              }
            }
          })
          return
        } else {
          addUserAtte(e.User.ID).then(addRes => {
            if (addRes.data.Data == false) return
            this.huibaHottestListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
              true)
            this.huibaLatestListData.filter(item => item.User.ID == e.User.ID).map(item => item.User.UserAtte =
              true)
            // 登录用户关注数加
            let tempUser = this.$store.getters['user/getUserInfoData']
            tempUser.AtteCount++
            this.$store.commit('user/setUserInfoData', tempUser)
          })
        }
      },
      /// 展卡更多-拉黑
      fnCardBlack(e) {
        // 用户是否被列入黑名单
        e.User.Black ? delUserBlack(e.User.ID) : addUserBlack(e.User.ID)
      },
      /// 展卡更多-跳转举报页
      fnCardReport(e) {
        uni.navigateTo({
          url: `/pages/report/report?id=${e.ObjectID}&type=${e.ObjectType}`
        })
      },
      //
    }
  }
</script>

<style>
  .huiba-cover {
    display: block;
    width: 100%;
    height: 300rpx;
  }
</style>

<template>
  <view>
    <!-- 顶部导航栏 -->
    <view class="posif posi-tlr0 z500 bgf8">
      <!-- 导航条 -->
      <view class="flexr-jsa flex-ais hl80r bgwhite">
        <view class="f32r fbold fcenter w128r tabbar" :class="{'tabbarsh':current == 0}" @tap="fnBarClick(0)">用户</view>
        <view class="f32r fbold fcenter w128r tabbar" :class="{'tabbarsh':current == 1}" @tap="fnBarClick(1)">话题</view>
      </view>
    </view>

    <!-- 滑动切换视图 -->
    <swiper class="posia posi-all0" :current="current" @change="fnBarClick">
      <!-- 用户 -->
      <swiper-item>
        <mescroll-uni v-if="status.user" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(user,index) in userBlackListData" :key="index">
            <view class="flex plr18r ptb18r bgwhite bbs2r">
              <user-avatar @click="fnUserInfo(user.ID)" :src="user.HeadUrl + '_100x100.jpg'" :tag="user.AuthenticateCode"
                size="md"></user-avatar>
              <view class="flexc-jsa ml18r mr28r flex-gitem w128r">
                <view>
                  <text class="f28r fbold mr18r">{{user.NickName}}</text>
                  <i-icon :type="user.Gender == '男' ?'nan':'nv' " size="28" :color="user.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
                </view>
                <view class="f24r cgray ellipsis">{{calAddress(user.CityNames)}}</view>
              </view>
              <view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnUserBlack(user.ID)">洗白</view>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>

      <!-- 话题 -->
      <swiper-item>
        <mescroll-uni v-if="status.topic" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(topic,index) in topicBlackListData" :key="index">
            <view class="plr18r ptb18r bgwhite bbs2r h112r flexr-jsb">
              <view class="flex-fitem w128r" @tap="fnTopicInfo(topic.ID)">
                <view class="f32r fbold ellipsis hl80r">{{topic.Name}}</view>
                <view class="f24r cgray">
                  <text class="mr8r">关注</text>{{topic.UserCount || 0}}
                  <text class="ml28r mr8r">动态</text>{{topic.TrendCount || 0}}
                </view>
              </view>
              <view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnTopicBlack(topic.ID)">洗白</view>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>
    </swiper>
  </view>
</template>

<script>
  import {
    getUserBlackList,
    getUserExistsBlack,
    delUserBlack
  } from "@/api/UserServer.js"
  import {
    getTopicBlackList,
    delTopicBlack
  } from "@/api/TopicServer.js"


  export default {
    data() {
      return {
        // 顶部导航滑动页选中
        current: 0,
        // 激活顶部导航关联页状态
        status: {
          user: true,
          topic: false,
        },
        // 双击刷新
        clickRefresh: false,
        // 刷新间隔
        timeOutBlack: 0,
        // 刷新组件实例
        mescroll: [null, null],
        //
      }
    },

    computed: {
      // 用户黑名单
      userBlackListData() {
        return this.$store.getters['user/getUserBlackListData']
      },
      // 话题黑名单
      topicBlackListData() {
        return this.$store.getters['topic/getTopicBlackListData']
      },
      //
    },

    methods: {
      /// mescroll组件初始化的回调,可获取到mescroll对象
      mescrollInit(mescroll) {
        this.mescroll[this.current] = mescroll;
      },
      /// 下拉刷新的回调
      downCallback(mescroll) {
        // 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
        this.mescroll[this.current].resetUpScroll()
      },
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        [getUserBlackList, getTopicBlackList][this.current]({
          page: mescroll.num,
          limit: mescroll.size
        }).then(res => {
          // 用户
          if (this.current == 0) {
            if (mescroll.num == 1) {
              this.$store.commit('user/setUserBlackListData', res.data.Data)
            } else {
              this.$store.commit('user/setUserBlackListData', this.userBlackListData.concat(res.data.Data))
            }
          }
          // 话题
          if (this.current == 1) {
            if (mescroll.num == 1) {
              this.$store.commit('topic/setTopicBlackListData', res.data.Data)
            } else {
              this.$store.commit('topic/setTopicBlackListData', this.topicBlackListData.concat(res.data.Data))
            }
          }
          mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size)
        }).catch(() => {
          mescroll.endErr()
        })
      },

      /// 导航选项双击刷新获取新数据
      fnRefreshData() {
        this.mescroll[this.current].scrollTo(0, 300);
        setTimeout(() => {
          this.mescroll[this.current].resetUpScroll(true)
        }, 1000)
      },
      /// 顶部导航选项点击
      fnBarClick(e) {
        let current = e.hasOwnProperty("detail") ? e.detail.current : e;

        // 是否当前项点击
        if (typeof e == 'number' && this.current == current) {
          this.timeOutBlack += 1;
          // 是否为刷新值和连续触发
          if (!this.clickRefresh && this.timeOutBlack >= 2) {
            // 刷新值开
            this.clickRefresh = true;
            // 获取新数据
            this.fnRefreshData();
            // 定时器重置
            this.timeOutBlack = setTimeout(() => {
              // 清除定时器
              clearTimeout(this.timeOutBlack)
              // 连续触发记录重置
              this.timeOutBlack = 0;
              // 刷新值关
              this.clickRefresh = false;
            }, 5000);
          }
          return;
        } else {
          // 改变顶部导航选中
          this.current = current;
          // 首次选中激活顶部导航关联页状态
          if (!this.status.topic && current == 1) this.status.topic = true;
          // 清除定时器
          clearTimeout(this.timeOutBlack)
          // 连续触发记录重置
          this.timeOutBlack = 0;
          // 刷新值关
          this.clickRefresh = false;
        }
      },

      /// 跳转用户信息页
      fnUserInfo(id) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${id}`
        })
      },
      /// 用户移出黑名单
      fnUserBlack(id) {
        uni.showModal({
          content: '是否将该用户移出黑名单？',
          success: res => {
            if (res.confirm) {
              // 用户是否被列入黑名单
              delUserBlack(id).then(delRes => {
                if (delRes.data.Data == false) return
                this.$store.commit('user/setUserBlackListData', this.userBlackListData.filter(user => user.ID !=
                  id))
                // 没数据时显示空布局
                if (this.userBlackListData.length == 0) this.mescroll[this.current].showEmpty()
              })
            }
          }
        })
      },
      // 跳转话题详情页
      fnTopicInfo(id) {
        uni.navigateTo({
          url: `/pages/topic-details/topic-details?id=${id}&fromPage=black&current=${this.current}`
        })
      },
      /// 话题移出黑名单
      fnTopicBlack(id) {
        uni.showModal({
          content: '是否取消屏蔽该话题？',
          success: res => {
            if (res.confirm) {
              // 用户是否被列入黑名单
              delTopicBlack(id).then(delRes => {
                if (delRes.data.Data == false) return
                this.$store.commit('topic/setTopicBlackListData', this.topicBlackListData.filter(topic =>
                  topic.ID !=
                  id))
                // 没数据时显示空布局
                if (this.topicBlackListData.length == 0) this.mescroll[this.current].showEmpty()
              })
            }
          }
        })
      },
      /// 地址逗号换空格
      calAddress(addr) {
        return typeof addr == 'string' ? addr.split(',').join(' ') : '     '
      },
      //
    }
  }
</script>

<style>
</style>

<template>
  <view>
    <!-- 顶部导航栏 -->
    <view class="posif posi-tlr0 z500 bgf8">
      <!-- 导航条 -->
      <scroll-view class="scroll-bar" :scroll-x="true" :show-scrollbar="false" :scroll-into-view="scrollInto"
        :scroll-with-animation="true">
        <view v-for="tab in tabBars" :key="tab.id" class="scroll-bar-item33v" :class="{'scroll-bar-itemsh':current == tab.current}"
          :id="tab.id" :data-current="tab.current" @tap="fnBarClick(tab)">
          {{tab.name}}
        </view>
      </scroll-view>
    </view>

    <!-- 滑动切换视图 -->
    <swiper class="posia posi-all0" :current="current" @change="fnBarClick">
      <!-- 关注用户 -->
      <swiper-item>
        <mescroll-uni v-if="status.user" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(user,index) in userAtteUserListData" :key="index">
            <view class="flex plr18r ptb18r bgwhite bbs2r">
              <user-avatar @click="fnUserInfo(user.ID)" :src="user.HeadUrl ? user.HeadUrl + '_100x100.jpg' : '/static/default_avatar.png'"
                :tag="user.AuthenticateCode" size="md"></user-avatar>
              <view class="flexc-jsa ml18r mr28r flex-gitem w128r">
                <view>
                  <text class="f28r fbold mr18r">{{user.NickName}}</text>
                  <i-icon :type="user.Gender == '男' ?'nan':'nv' " size="28" :color="user.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
                </view>
                <view class="f24r cgray ellipsis">{{user.Describe || '该同袍还不知道怎么描述寄己 (╯▽╰)╭'}}</view>
              </view>
              <view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnUserFollow(user)">{{ user.UserAtte?'已关注':'关注'}}</view>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>

      <!-- 关注话题 -->
      <swiper-item>
        <mescroll-uni v-if="status.topic" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(topic,index) in topicUserFollowData" :key="index">
            <view class="plr18r ptb18r bgwhite bbs2r h112r" @tap="fnTopicInfo(topic.Topic.ID)">
              <view class="f32r fbold ellipsis hl80r">{{topic.Topic.Name}}</view>
              <view class="flexr-jsb flex-aic">
                <view class="f24r cgray flex-fitem">
                  <text class="mr8r">关注</text>{{topic.Topic.UserCount || 0}}
                  <text class="ml28r mr8r">动态</text>{{topic.Topic.TrendCount || 0}}
                </view>
                <view class="bgtheme cwhite br18r f24r plr18r" v-if="topic.NoReadCount">{{topic.NoReadCount || 0}}</view>
              </view>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>

      <!-- 关注荟吧 -->
      <swiper-item>
        <mescroll-uni v-if="status.huiba" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(huiba,index) in huibaUserFollowData" :key="index">
            <view class="flexr-jsb flex-aic plr18r ptb18r bgwhite bbs2r" @tap="fnHuibaInfo(huiba.ID)">
              <view class="flex">
                <user-avatar :src="huiba.FaceUrl ? huiba.FaceUrl + '_200x200.jpg':'/static/default_avatar.png'" size="md"
                  :square="true"></user-avatar>
                <view class="flexc-jsa ml28r">
                  <view class="f28r fbold mr18r c111">{{huiba.Name}}</view>
                  <view class="f24r cgray">
                    <text class="mr8r">关注</text>{{huiba.FollowCount || 0}}
                    <text class="ml28r mr8r">动态</text>{{huiba.TrendCount || 0}}
                  </view>
                </view>
              </view>
              <i-icon type="you" size="36" color="#8F8F94"></i-icon>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>
    </swiper>
  </view>
</template>

<script>
  import {
    getUserAtteUserList
  } from "@/api/UserServer.js"
  import {
    getTopicUserFollow
  } from "@/api/TopicServer.js"
  import {
    getHuibaUserFollow
  } from "@/api/HuibaServer.js"

  let dataList = [getUserAtteUserList, getTopicUserFollow, getHuibaUserFollow];

  export default {
    data() {
      return {
        // 导航项滑动初始id
        scrollInto: "user",
        // 顶部导航滑动页选中
        current: 0,
        // 导航项列表
        tabBars: [{
          id: "user",
          name: '用户',
          current: 0
        }, {
          id: "topic",
          name: '话题',
          current: 1
        }, {
          id: "huiba",
          name: '荟吧',
          current: 2
        }],
        // 激活顶部导航关联页状态
        status: {
          user: true,
          topic: false,
          huiba: false,
        },
        // 双击刷新
        clickRefresh: false,
        // 刷新间隔
        timeOutFollow: 0,
        // 刷新组件实例
        mescroll: {
          user: null,
          topic: null,
          huiba: null,
        },
        // 用户ID
        id: -1
        //
      }
    },

    computed: {
      // 用户
      userAtteUserListData() {
        return this.$store.getters['user/getUserAtteUserListData']
      },
      // 话题
      topicUserFollowData() {
        return this.$store.getters['topic/getTopicUserFollowData']
      },
      // 荟吧
      huibaUserFollowData() {
        return this.$store.getters['huiba/getHuibaUserFollowData']
      },
      //
    },

    onLoad(options) {
      if (options && options.id) {
        this.id = parseInt(options.id)
      }
    },

    methods: {
      /// mescroll组件初始化的回调,可获取到mescroll对象
      mescrollInit(mescroll) {
        this.mescroll[this.scrollInto] = mescroll;
      },
      /// 下拉刷新的回调
      downCallback(mescroll) {
        // 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
        this.mescroll[this.scrollInto].resetUpScroll()
      },
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        dataList[this.current]({
          page: mescroll.num,
          limit: mescroll.size,
          userid: this.id
        }).then(res => {
          // 用户
          if (this.scrollInto == 'user') {
            if (mescroll.num == 1) {
              this.$store.commit('user/setUserAtteUserListData', res.data.Data)
            } else {
              this.$store.commit('user/setUserAtteUserListData', this.userAtteUserListData.concat(res.data.Data))
            }
          }
          // 话题
          if (this.scrollInto == 'topic') {
            if (mescroll.num == 1) {
              this.$store.commit('topic/setTopicUserFollowData', res.data.Data)
            } else {
              this.$store.commit('topic/setTopicUserFollowData', this.topicUserFollowData.concat(res.data.Data))
            }
          }
          // 荟吧
          if (this.scrollInto == 'huiba') {
            if (mescroll.num == 1) {
              this.$store.commit('huiba/setHuibaUserFollowData', res.data.Data)
            } else {
              this.$store.commit('huiba/setHuibaUserFollowData', this.huibaUserFollowData.concat(res.data.Data))
            }
          }
          mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size)
        }).catch(() => {
          mescroll.endErr()
        })
      },

      /// 导航选项双击刷新获取新数据
      fnRefreshData() {
        this.mescroll[this.scrollInto].scrollTo(0, 300);
        setTimeout(() => {
          this.mescroll[this.scrollInto].resetUpScroll(true)
        }, 1000)
      },
      /// 顶部导航选项点击
      fnBarClick(e) {
        let current = e.hasOwnProperty("detail") ? e.detail.current : e.current;
        this.scrollInto = this.tabBars[current].id;
        // 是否当前项点击
        if (e.hasOwnProperty("id") && this.current == current) {
          this.timeOutFollow += 1;
          // 是否为刷新值和连续触发
          if (!this.clickRefresh && this.timeOutFollow >= 2) {
            // 刷新值开
            this.clickRefresh = true;
            // 获取新数据
            this.fnRefreshData();
            // 定时器重置
            this.timeOutFollow = setTimeout(() => {
              // 清除定时器
              clearTimeout(this.timeOutFollow)
              // 连续触发记录重置
              this.timeOutFollow = 0;
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
          if (!this.status.huiba && current == 2) this.status.huiba = true;
          // 清除定时器
          clearTimeout(this.timeOutFollow)
          // 连续触发记录重置
          this.timeOutFollow = 0;
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
      /// 用户关注
      fnUserFollow(e) {
        // 用户被关注
        if (e.UserAtte) {
          uni.showModal({
            content: '确定要取消关注TA吗？',
            success: res => {
              if (res.confirm) {
                delUserAtte(e.User.ID).then(delRes => {
                  if (delRes.data.Data == false) return
                  this.userAtteUserListData.filter(item => item.ID == e.ID).map(item => item.UserAtte = false)
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
            this.userAtteUserListData.filter(item => item.ID == e.ID).map(item => item.UserAtte = true)
            // 登录用户关注数加
            let tempUser = this.$store.getters['user/getUserInfoData']
            tempUser.AtteCount++
            this.$store.commit('user/setUserInfoData', tempUser)
          })
        }
      },
      /// 跳转荟吧详情页
      fnHuibaInfo(id) {
        uni.navigateTo({
          url: `/pages/huiba-details/huiba-details?id=${id}`
        })
      },
      /// 跳转话题详情页
      fnTopicInfo(id) {
        uni.navigateTo({
          url: `/pages/topic-details/topic-details?id=${id}`
        })
      },
      //
    }
  }
</script>

<style>
</style>

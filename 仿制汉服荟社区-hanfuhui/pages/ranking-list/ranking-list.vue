<template>
  <view>
    <!-- 顶部导航栏 -->
    <view class="posif posi-tlr0 z500 bgf8">
      <!-- 导航条 -->
      <scroll-view class="scroll-bar" :scroll-x="true" :show-scrollbar="false" :scroll-into-view="scrollInto"
        :scroll-with-animation="true">
        <view v-for="tab in tabBars" :key="tab.id" class="scroll-bar-item25v" :class="{'scroll-bar-itemsh':current == tab.current}"
          :id="tab.id" :data-current="tab.current" @tap="fnBarClick(tab)">
          {{tab.name}}
        </view>
      </scroll-view>
    </view>

    <!-- 滑动切换视图 -->
    <swiper class="posia posi-all0" :current="current" @change="fnBarClick">
      <!-- 摄影榜 -->
      <swiper-item>
        <mescroll-uni v-if="status.album" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(rank,index) in rankAlbumListData" :key="index">
            <view class="flexr-jsb flex-aic plr18r ptb18r bgwhite bbs2r">
              <view class="flex" @tap="fnUserInfo(rank.ID)">
                <user-avatar :src="rank.HeadUrl ? rank.HeadUrl + '_200x200.jpg':'/static/default_avatar.png'" :tag="rank.AuthenticateCode"
                  size="md"></user-avatar>
                <view class="flexc-jsa ml28r">
                  <view>
                    <text class="f28r fbold mr18r c111">{{rank.NickName}}</text>
                    <i-icon :type="rank.Gender == '男' ?'nan':'nv' " size="28" :color="rank.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
                  </view>
                  <view class="f24r cgray">{{calAddress(rank.CityNames)}}</view>
                </view>
              </view>
              <view class="f28r cgray">
                加精作品<text class="ctheme fbold ml8r">{{rank.GoodAlbumCount}}</text>
              </view>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>

      <!-- 汉币榜 -->
      <swiper-item>
        <mescroll-uni v-if="status.hanbi" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(rank,index) in rankHanbiListData" :key="index">
            <view class="flexr-jsb flex-aic plr18r ptb18r bgwhite bbs2r">
              <view class="flex" @tap="fnUserInfo(rank.ID)">
                <user-avatar :src="rank.HeadUrl ? rank.HeadUrl + '_200x200.jpg':'/static/default_avatar.png'" :tag="rank.AuthenticateCode"
                  size="md"></user-avatar>
                <view class="flexc-jsa ml28r">
                  <view>
                    <text class="f28r fbold mr18r c111">{{rank.NickName}}</text>
                    <i-icon :type="rank.Gender == '男' ?'nan':'nv' " size="28" :color="rank.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
                  </view>
                  <view class="f24r cgray">{{calAddress(rank.CityNames)}}</view>
                </view>
              </view>
              <view class="f28r cgray">
                已消费<text class="ctheme fbold mlr8r">{{rank.UseHanbi}}</text>汉币
              </view>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>

      <!-- 人气榜 -->
      <swiper-item>
        <mescroll-uni v-if="status.popularity" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(rank,index) in rankPopularityListData" :key="index">
            <view class="flexr-jsb flex-aic plr18r ptb18r bgwhite bbs2r">
              <view class="flex" @tap="fnUserInfo(rank.ID)">
                <user-avatar :src="rank.HeadUrl ? rank.HeadUrl + '_200x200.jpg':'/static/default_avatar.png'" :tag="rank.AuthenticateCode"
                  size="md"></user-avatar>
                <view class="flexc-jsa ml28r">
                  <view>
                    <text class="f28r fbold mr18r c111">{{rank.NickName}}</text>
                    <i-icon :type="rank.Gender == '男' ?'nan':'nv' " size="28" :color="rank.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
                  </view>
                  <view class="f24r cgray">{{calAddress(rank.CityNames)}}</view>
                </view>
              </view>
              <view class="f28r cgray">
                人气值<text class="ctheme fbold ml8r">{{rank.Popularity}}</text>
              </view>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>

      <!-- 签到榜 -->
      <swiper-item>
        <mescroll-uni v-if="status.signin" :top="80" @down="downCallback" @up="upCallback" @init="mescrollInit">
          <block v-for="(rank,index) in rankSigninListData" :key="index">
            <view class="flexr-jsb flex-aic plr18r ptb18r bgwhite bbs2r">
              <view class="flex" @tap="fnUserInfo(rank.ID)">
                <user-avatar :src="rank.HeadUrl ? rank.HeadUrl + '_200x200.jpg':'/static/default_avatar.png'" :tag="rank.AuthenticateCode"
                  size="md"></user-avatar>
                <view class="flexc-jsa ml28r">
                  <view>
                    <text class="f28r fbold mr18r c111">{{rank.NickName}}</text>
                    <i-icon :type="rank.Gender == '男' ?'nan':'nv' " size="28" :color="rank.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
                  </view>
                  <view class="f24r cgray">{{calAddress(rank.CityNames)}}</view>
                </view>
              </view>
              <view class="f28r cgray">
                连续签到<text class="ctheme fbold mlr8r">{{rank.SigninCount}}</text>天
              </view>
            </view>
          </block>
        </mescroll-uni>
      </swiper-item>
    </swiper>
  </view>
</template>

<script>
  import {
    getRankList
  } from "@/api/CommonServer.js"
  export default {
    data() {
      return {
        // 导航项滑动初始id
        scrollInto: "album",
        // 顶部导航滑动页选中
        current: 0,
        // 导航项列表
        tabBars: [{
          id: "album",
          name: '摄影榜',
          current: 0
        }, {
          id: "hanbi",
          name: '汉币榜',
          current: 1
        }, {
          id: "popularity",
          name: '人气榜',
          current: 2
        }, {
          id: "signin",
          name: '签到榜',
          current: 3
        }],
        // 激活顶部导航关联页状态
        status: {
          album: true,
          hanbi: false,
          popularity: false,
          signin: false,
        },
        // 双击刷新
        clickRefresh: false,
        // 刷新间隔
        timeOutRank: 0,
        // 刷新组件实例
        mescroll: {
          album: null,
          hanbi: null,
          popularity: null,
          signin: null,
        },
        //
      }
    },

    computed: {
      // 摄影榜
      rankAlbumListData() {
        return this.$store.getters['common/getRankAlbumListData']
      },
      // 汉币榜
      rankHanbiListData() {
        return this.$store.getters['common/getRankHanbiListData']
      },
      // 人气榜
      rankPopularityListData() {
        return this.$store.getters['common/getRankPopularityListData']
      },
      // 签到榜
      rankSigninListData() {
        return this.$store.getters['common/getRankSigninListData']
      },
      //
    },

    onLoad(options) {
      // if (options && options.id) {
      //   console.log(options);
      // }
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
        getRankList({
          page: mescroll.num,
          count: mescroll.size,
          type: this.scrollInto
        }).then(res => {
          // 摄影
          if (this.scrollInto == 'album') {
            if (mescroll.num == 1) {
              this.$store.commit('common/setRankAlbumListData', res.data.data)
            } else {
              this.$store.commit('common/setRankAlbumListData', this.rankAlbumListData.concat(res.data.data))
            }
          }
          // 汉币
          if (this.scrollInto == 'hanbi') {
            if (mescroll.num == 1) {
              this.$store.commit('common/setRankHanbiListData', res.data.data)
            } else {
              this.$store.commit('common/setRankHanbiListData', this.rankHanbiListData.concat(res.data.data))
            }
          }
          // 人气
          if (this.scrollInto == 'popularity') {
            if (mescroll.num == 1) {
              this.$store.commit('common/setRankPopularityListData', res.data.data)
            } else {
              this.$store.commit('common/setRankPopularityListData', this.rankPopularityListData.concat(res.data.data))
            }
          }
          // 签到
          if (this.scrollInto == 'signin') {
            if (mescroll.num == 1) {
              this.$store.commit('common/setRankSigninListData', res.data.data)
            } else {
              this.$store.commit('common/setRankSigninListData', this.rankSigninListData.concat(res.data.data))
            }
          }
          mescroll.endSuccess(res.data.data.length, res.data.data.length >= mescroll.size)
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
          this.timeOutRank += 1;
          // 是否为刷新值和连续触发
          if (!this.clickRefresh && this.timeOutRank >= 2) {
            // 刷新值开
            this.clickRefresh = true;
            // 获取新数据
            this.fnRefreshData();
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
          return;
        } else {
          // 改变顶部导航选中
          this.current = current;
          // 首次选中激活顶部导航关联页状态
          if (!this.status.hanbi && current == 1) this.status.hanbi = true;
          if (!this.status.popularity && current == 2) this.status.popularity = true;
          if (!this.status.signin && current == 3) this.status.signin = true;
          // 清除定时器
          clearTimeout(this.timeOutRank)
          // 连续触发记录重置
          this.timeOutRank = 0;
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
      /// 计算地址逗号换空格
      calAddress(cityNames) {
        return typeof cityNames == 'string' ? cityNames.split(',').join(' ') : '未知 未知'
      },
      //
    }
  }
</script>

<style>
</style>

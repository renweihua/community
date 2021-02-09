<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(fans,index) in userFansListData" :key="index">
        <uni-swipe-action>
          <uni-swipe-action-item :options="options" @click="fnSwip(fans)">
            <view class="flex flex-gitem plr18r ptb18r bgwhite bbs2r">
              <user-avatar @click="fnUserInfo(fans.ID)" :src="fans.HeadUrl ? fans.HeadUrl + '_100x100.jpg' : '/static/default_avatar.png'"
                :tag="fans.AuthenticateCode" size="md"></user-avatar>
              <view class="flexc-jsa ml18r mr28r flex-gitem w128r">
                <view>
                  <text class="f28r fbold mr18r">{{fans.NickName}}</text>
                  <i-icon :type="fans.Gender == '男' ?'nan':'nv' " size="28" :color="fans.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
                </view>
                <view class="f24r cgray ellipsis">{{calAddress(fans.CityNames)}}</view>
              </view>
              <view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnUserFollow(fans)">{{ fans.UserAtte?'已关注':'关注'}}</view>
            </view>
          </uni-swipe-action-item>
        </uni-swipe-action>
      </block>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    getUserFansList,
    delFans,
    addUserAtte,
    delUserAtte,
  } from "@/api/UserServer.js"

  // 侧滑操作组件
  import uniSwipeAction from "@/components/uni-swipe-action/uni-swipe-action.vue"
  import uniSwipeActionItem from "@/components/uni-swipe-action-item/uni-swipe-action-item.vue"
  export default {
    components: {
      uniSwipeAction,
      uniSwipeActionItem
    },

    data() {
      return {
        id: -1,
        options: [{
          text: '移除',
          style: {
            backgroundColor: '#FF6699'
          }
        }]
      }
    },

    computed: {
      // 用户
      userFansListData() {
        return this.$store.getters['user/getUserFansListData']
      }
    },

    onLoad(options) {
      if (options && options.id) {
        this.id = parseInt(options.id)
      }
    },

    methods: {
      /// 下拉刷新的回调
      downCallback(mescroll) {
        // 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
        mescroll.resetUpScroll()
      },
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        getUserFansList({
          page: mescroll.num,
          count: mescroll.size,
          userid: this.id
        }).then(atteRes => {
          if (mescroll.num == 1) {
            this.$store.commit('user/setUserFansListData', atteRes.data.data)
          } else {
            this.$store.commit('user/setUserFansListData', this.userFansListData.concat(atteRes.data.data))
          }
          mescroll.endSuccess(atteRes.data.data.length, atteRes.data.data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 地址逗号换空格
      calAddress(addr) {
        return typeof addr == 'string' ? addr.split(',').join(' ') : '     '
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
                delUserAtte(e.ID).then(delRes => {
                  if (delRes.data.data == false) return
                  this.userFansListData.filter(item => item.ID == e.ID).map(item => item.UserAtte = false)
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
          addUserAtte(e.ID).then(addRes => {
            if (addRes.data.data == false) return
            this.userFansListData.filter(item => item.ID == e.ID).map(item => item.UserAtte = true)
            // 登录用户关注数加
            let tempUser = this.$store.getters['user/getUserInfoData']
            tempUser.AtteCount++
            this.$store.commit('user/setUserInfoData', tempUser)
          })
        }
      },
      /// 滑动选择
      fnSwip(e) {
        uni.showModal({
          content: `确定要移除[${e.NickName}]吗？`,
          success: res => {
            if (res.confirm) {
              delFans(e.ID).then(delRes => {
                if (delRes.data.data == false) return
                this.$store.commit('user/setUserFansListData', this.userFansListData.filter(item => item.ID !=
                  e.ID))
                // 登录用户关注数减
                let tempUser = this.$store.getters['user/getUserInfoData']
                tempUser.FansCount--
                this.$store.commit('user/setUserInfoData', tempUser)
              })
            }
          }
        })
      }
      //
    }
  }
</script>

<style>
  page {
    background: #FFFFFF;
  }
</style>

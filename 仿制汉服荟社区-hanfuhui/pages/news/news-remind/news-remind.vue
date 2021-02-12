<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(remind,index) in remindList" :key="index">
        <view class="plr18r ptb18r bgwhite mb18r">
          <view class="flex plr18r ptb18r bbs2r">
            <user-avatar @click="fnUserInfo(remind.User)" :src="remind.User.HeadUrl + '_200x200.jpg'" :tag="remind.User.AuthenticateCode"
              size="md"></user-avatar>
            <view class="flexc-jsa ml28r">
              <view>
                <text class="f28r fbold mr18r">{{remind.User.NickName}}</text>
                <i-icon :type="remind.User.Gender == '男' ?'nan':'nv' " size="28" :color="remind.User.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
              </view>
              <view class="f24r cgray">{{calDateTime(remind.Datetime)}}</view>
            </view>
          </view>
          <view class="fword f28r c555 mt18r">{{remind.Content}}</view>
        </view>
      </block>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    fnFormatTimeHeader
  } from "@/utils/CommonUtil.js"
  import {
    getMessageListByType,
  } from "@/api/MessageServer.js"

  export default {
    data() {
      return {
        // 提醒数据列表
        remindList: []
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
        getMessageListByType({
          page: mescroll.num,
          limit: mescroll.size,
          type: 'remind'
        }).then(res => {
          if (mescroll.num == 1) {
            this.remindList = res.data.Data
          } else {
            this.remindList = this.remindList.concat(res.data.Data)
          }
          mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 计算时间格式 2019-09-09 19:19
      calDateTime(str) {
        return fnFormatTimeHeader(new Date(str).getTime())
      },
      /// 跳转用户信息页
      fnUserInfo(e) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${e.ID}`
        })
      },
    }
  }
</script>

<style>
</style>

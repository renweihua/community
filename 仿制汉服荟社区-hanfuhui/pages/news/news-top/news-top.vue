<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(top,index) in topList" :key="index">
        <view class="plr18r ptb18r bgwhite mb18r">
          <view class="flex plr18r ptb18r bbs2r">
            <user-avatar @click="fnUserInfo(top.User)" :src="top.User.HeadUrl + '_200x200.jpg'" :tag="top.user_info.uuid"
              size="md"></user-avatar>
            <view class="flexc-jsa ml28r flex-gitem">
              <view>
                <text class="f28r fbold mr18r">{{top.User.NickName}}</text>
                <i-icon :type="top.User.Gender == '男' ?'nan':'nv' " size="28" :color="top.User.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
              </view>
              <view class="f24r cgray">{{calDateTime(top.Datetime)}}</view>
            </view>
            <view class="f28r cgray flex-asc">{{top.Content}}</view>
          </view>
          <!-- 动态 -->
          <view class="flex flex-ais br4r" @tap="fnOpenInfo(top)">
            <image class="hw128r br4r" v-if="top.ObjectImgSrc" :src="top.ObjectImgSrc+'_200x200.jpg/format/webp'" mode="aspectFill"></image>
            <view class="flex-fitem f28r ptb8r plr18r bgf8 c555 flex flex-aic">{{top.ObjectText}}</view>
          </view>
        </view>
      </block>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    fnFormatLocalDate
  } from "@/utils/CommonUtil.js"
  import {
    getMessageListByType,
  } from "@/api/MessageServer.js"

  export default {
    data() {
      return {
        // 点赞数据列表
        topList: []
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
          count: mescroll.size,
          type: 'top'
        }).then(res => {
          if (mescroll.num == 1) {
            this.topList = res.data.data
          } else {
            this.topList = this.topList.concat(res.data.data)
          }
          mescroll.endSuccess(res.data.data.length, res.data.data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 计算时间格式 下午 08:12 | 昨日 09:12 | 2019-12-03 20:12
      calDateTime(str) {
        return fnFormatLocalDate(new Date(str).getTime())
      },
      /// 跳转用户信息页
      fnUserInfo(e) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${e.ID}`
        })
      },
      /// 跳转详情页
      fnOpenInfo(e) {
        console.log(e.object_type);
        if (e.object_type == 'trend') {
          uni.navigateTo({
            url: `/pages/trend-details/trend-details?id=${e.ObjectID}&fromPage=comment`
          })
          return
        }
        if (e.object_type == 'album') {
          uni.navigateTo({
            url: `/pages/album-details/album-details?id=${e.ObjectID}&fromPage=comment`
          })
          return
        }
        if (e.object_type == 'topic') {
          uni.navigateTo({
            url: `/pages/topic-details/topic-details?id=${e.ObjectID}&fromPage=comment`
          })
          return
        }
        if (e.object_type == 'topicreply') {
          uni.navigateTo({
            url: `/pages/topicreply-details/topicreply-details?id=${e.ObjectID}&fromPage=comment`
          })
          return
        }
        if (e.object_type == 'video') {
          uni.navigateTo({
            url: `/pages/video-details/video-details?id=${e.ObjectID}&fromPage=comment`
          })
          return
        }
      },
    }
  }
</script>

<style>
</style>

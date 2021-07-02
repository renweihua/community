<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni  @down="downCallback" @up="upCallback">
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
  </view>
</template>

<script>
  import {
    getTopicUserFollow
  } from "@/api/TopicServer.js"

  export default {
    data() {
      return {
        id: 0
      }
    },
    onLoad() {
      this.id = this.$store.getters['user/getLoginUserInfoData'].ID
    },
    computed: {
      // 话题
      topicUserFollowData() {
        return this.$store.getters['topic/getTopicUserFollowData']
      },
    },

    methods: {
      /// 下拉刷新的回调
      downCallback(mescroll) {
        // 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
        mescroll.resetUpScroll()
      },
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        getTopicUserFollow({
          page: mescroll.num,
          limit: mescroll.size,
          userid: this.id
        }).then(res => {
          if (mescroll.num == 1) {
            this.$store.commit('topic/setTopicUserFollowData', res.data.Data)
          } else {
            this.$store.commit('topic/setTopicUserFollowData', this.topicUserFollowData.concat(res.data.Data))
          }
          mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 跳转话题详情页
      fnTopicInfo(id) {
        uni.navigateTo({
          url: `/pages/topic-details/topic-details?id=${id}`
        })
      },
    }
  }
</script>

<style>
</style>

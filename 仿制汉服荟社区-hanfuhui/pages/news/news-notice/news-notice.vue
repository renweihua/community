<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(notice,index) in noticeList" :key="index">
        <view class="mlr18r mtb18r">
          <view class="mautoblock fcenter ptb8r plr18r f24r cgray br8r mb18r">
            {{calDateTime(notice.Datetime)}}
          </view>
          <view class="plr18r ptb18r fword f28r c111 bgwhite br8r">
            {{notice.Content}}
          </view>
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
    getMessageNoticeList,
  } from "@/api/MessageServer.js"

  export default {
    data() {
      return {
        // 公告数据列表
        noticeList: []
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
        getMessageNoticeList({
          page: mescroll.num,
          count: mescroll.size,
        }).then(res => {
          if (mescroll.num == 1) {
            this.noticeList = res.data.Data
          } else {
            this.noticeList = this.noticeList.concat(res.data.Data)
          }
          mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 计算时间格式 下午 08:12 | 昨日 09:12 | 2019-12-03 20:12
      calDateTime(str) { 
        return fnFormatTimeHeader(new Date(str).getTime())
      }
    }
  }
</script>

<style>
</style>

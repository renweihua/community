<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(item,index) in commentList" :key="index">
        <view class="plr18r ptb18r bbs2r">
          <view class="flex">
            <user-avatar @click="fnUserInfo(item.User.ID)" :src="item.User.HeadUrl + '_200x200.jpg'" :tag="item.User.AuthenticateCode"
              size="md"></user-avatar>
            <view class="flexc-jsa ml28r">
              <view>
                <text class="f28r fbold mr18r">{{item.User.NickName}}</text>
                <i-icon :type="item.User.Gender == '男' ?'nan':'nv' " size="28" :color="item.User.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
              </view>
              <view class="f24r cgray">{{calFormatDate(item.Datetime)}}</view>
            </view>
          </view>
          <view class="ml128r f28r c555 mt18r pt18r">
            回复<text class="ctheme" @tap="fnUserInfo(item.ReplyUser.ID)">{{item.ReplyUser.NickName}}</text>：{{item.Content}}
          </view>
        </view>
      </block>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    fnFormatDate
  } from "@/utils/CommonUtil.js"
  import {
    getCommentListByID
  } from "@/api/InteractServer.js"

  export default {
    data() {
      return {
        id: -1,
        commentList: []
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
        getCommentListByID({
          parentid: this.id,
          page: mescroll.num,
          count: mescroll.size
        }).then(res => {
          if (mescroll.num == 1) {
            this.commentList = res.data.Data
          } else {
            this.commentList = this.commentList.concat(res.data.Data)
          }
          mescroll.endSuccess(res.data.Data.length, res.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 跳转用户信息页
      fnUserInfo(id) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${id}`
        })
      },
      /// 格式化时间
      calFormatDate(str) {
        return fnFormatDate(new Date(str).getTime())
      }
    }
  }
</script>

<style>
  page {
    background: #FFFFFF;
  }

  .ml128r {
    margin-left: 128rpx;
  }
</style>

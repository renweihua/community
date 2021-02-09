<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(item,index) in commentList" :key="index">
        <view class="plr18r ptb18r bbs2r">
          <view class="flex">
            <user-avatar @click="fnUserInfo(item.user_info.user_id)" :src="item.user_info.user_head" :tag="item.user_info.uuid"
              size="md"></user-avatar>
            <view class="flexc-jsa ml28r">
              <view>
                <text class="f28r fbold mr18r">{{item.user_info.nick_name}}</text>
                <i-icon :type="item.user_info.user_sex_text == '男' ?'nan':'nv' " size="28" :color="item.user_info.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
              </view>
              <view class="f24r cgray">{{calFormatDate(item.created_time)}}</view>
            </view>
          </view>
          <view class="ml128r f28r c555 mt18r pt18r">
            回复<text class="ctheme" @tap="fnUserInfo(item.reply_user.user_id)">{{item.reply_user.nick_name}}</text>：{{item.reply_content}}
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
          dynamic_id: 0,
          reply_id: 0,
          last_id : 0,
        commentList: []
      }
    },
    onLoad(options) {
      if (options && options.reply_id) {
          this.dynamic_id = parseInt(options.dynamic_id);
        this.reply_id = parseInt(options.reply_id);
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
            dynamic_id: this.dynamic_id,
          reply_id: this.reply_id,
          last_id: this.last_id
        }).then(res => {
            this.last_id = res.data.last_id;
          if (mescroll.num == 1) {
            this.commentList = res.data.data
          } else {
            this.commentList = this.commentList.concat(res.data.data)
          }
          mescroll.endSuccess(res.data.data.length, res.data.data.length >= mescroll.size);
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
        return fnFormatDate(str)
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

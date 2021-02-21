<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(comment,index) in commentList" :key="index">
        <view class="plr18r ptb18r bgwhite mb18r">
          <view class="flex">
            <user-avatar @click="fnUserInfo(comment.user_info.user_id)" :src="comment.user_info.user_avatar" tag=""
              size="md"></user-avatar>
            <view class="flexc-jsa ml18r mr28r flex-gitem w128r">
              <view>
                <text class="f28r fbold mr18r">{{comment.user_info.NickName}}</text>
                <i-icon :type="comment.user_info.Gender == '男' ?'nan':'nv' " size="28" :color="comment.user_info.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
              </view>
              <view class="f24r cgray ellipsis">{{calDateTime(comment.Datetime)}}</view>
            </view>
            <view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnReplyOpen(comment)">回复</view>
          </view>
          <view class="f28r c555 ptb18r fword">
            <template v-if="comment.CommentData.ParentUserID">
              回复
              <text class="ctheme" @tap="fnUserInfo(comment.CommentData.ParentUserID)">
                {{comment.CommentData.ParentNickName}}
              </text>
              ：{{comment.Content}}
            </template>
            <template v-else>
              {{comment.Content}}
            </template>
          </view>
          <view class="ptb18r f28r bts2r" v-if="comment.CommentData.ParentUserID">
            <text class="ctheme" @tap="fnUserInfo(comment.CommentData.ParentUserID)">{{comment.CommentData.ParentNickName}}</text>
            <text class="c555 mlr8r" v-if="comment.CommentData.ParentReplyUserID">回复</text>
            <text class="ctheme" v-if="comment.CommentData.ParentReplyUserID" @tap="fnUserInfo(comment.CommentData.ParentReplyUserID)">{{comment.CommentData.ParentReplyNickName}}</text>
            <text class="c555">：{{comment.CommentData.ParentContent}}</text>
          </view>
          <view class="flex flex-ais br4r" @tap="fnOpenInfo(comment)">
            <image class="hw128r br4r" v-if="comment.ObjectImgSrc" :src="comment.ObjectImgSrc+'_200x200.jpg/format/webp'"
              mode="aspectFill"></image>
            <view class="flex-fitem f28r ptb8r plr18r bgf8 c555 flex flex-aic">{{comment.ObjectText}}</view>
          </view>
        </view>
      </block>
    </mescroll-uni>
    <!-- 评论输入弹出层 -->
    <comm-input ref="comm" @send="fnCommSend"></comm-input>
  </view>
</template>

<script>
  import {
    fnFormatLocalDate
  } from "@/utils/CommonUtil.js"
  import {
    getMessageListByType,
  } from "@/api/MessageServer.js"
  import {
    addComment,
  } from "@/api/InteractServer.js"

  // 评论输入弹出层组件
  import CommInput from '@/components/comm-input/comm-input'

  export default {
    components: {
      CommInput
    },

    data() {
      return {
        // 评论数据列表
        commentList: [],
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
          type: 'comment'
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
      /// 计算时间格式 下午 08:12 | 昨日 09:12 | 2019-12-03 20:12
      calDateTime(str) {
        return fnFormatLocalDate(new Date(str).getTime())
      },
      /// 跳转用户信息页
      fnUserInfo(id) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${id}`
        })
      },
      /// 跳转详情页
      fnOpenInfo(e) {
        console.log(e.ObjectType);
        if (e.ObjectType == 'trend') {
          uni.navigateTo({
            url: `/pages/trend-details/trend-details?id=${e.dynamic_id}&fromPage=comment`
          })
          return
        }
        if (e.ObjectType == 'album') {
          uni.navigateTo({
            url: `/pages/album-details/album-details?id=${e.dynamic_id}&fromPage=comment`
          })
          return
        }
        if (e.ObjectType == 'topic') {
          uni.navigateTo({
            url: `/pages/topic-details/topic-details?id=${e.dynamic_id}&fromPage=comment`
          })
          return
        }
        if (e.ObjectType == 'topicreply') {
          uni.navigateTo({
            url: `/pages/topicreply-details/topicreply-details?id=${e.dynamic_id}&fromPage=comment`
          })
          return
        }
        if (e.ObjectType == 'video') {
          uni.navigateTo({
            url: `/pages/video-details/video-details?id=${e.dynamic_id}&fromPage=comment`
          })
          return
        }
      },
      /// 显示评论输入框
      fnReplyOpen(e) {
        this.$refs.comm.open({
          type: 'reply',
          user: e.User.NickName,
          objecttype: e.ObjectType,
          objectid: e.dynamic_id,
          parentid: e.CommentID
        });
      },
      /// 评论发送
      fnCommSend(e) {
        if (e.state == false) return
        // 无内容信息反馈
        if (e.content == '') {
          uni.showToast({
            title: "评论内容不能为空",
            icon: 'none'
          })
          return
        }
        // 提交评论
        uni.showLoading({
          title: '提交中'
        })
        delete e.state
        delete e.type
        e.fromclient = 'android'
        addComment(e).then(addRes => {
          if (typeof addRes.data.Data != 'object') return
          this.$refs.comm.visible = false;
          uni.hideLoading()
          uni.showToast({
            title: '回复成功'
          })
        })
      },
      //
    }
  }
</script>

<style>
</style>

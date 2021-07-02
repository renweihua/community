<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(item,index) in commentList" :key="index">
        <view class="plr18r ptb18r bgwhite mb18r">
          <view class="flex">
            <user-avatar @click="fnUserInfo(item.sender.user_id)" :src="item.sender.user_avatar" tag=""
              size="md"></user-avatar>
            <view class="flexc-jsa ml18r mr28r flex-gitem w128r">
              <view>
                <text class="f28r fbold mr18r">{{item.sender.nick_name}}</text>
				<i-icon v-if="[0, 1].indexOf(item.sender.user_sex) > -1" :type="item.sender.user_sex_text == '男' ? 'nan':'nv' " size="28"
				 :color="item.sender.user_sex_text == '男' ?'#479bd4':'#FF6699'"></i-icon>
              </view>
              <view class="f24r cgray ellipsis">{{calDateTime(item.created_time)}}</view>
            </view>
            <view class="ball2r-ctheme f28r ctheme fcenter w128r br8r ptb8r flex-asc" @tap="fnReplyOpen(item)">回复</view>
          </view>
          <!-- <view class="f28r c555 ptb18r fword">
            <template v-if="item.CommentData.ParentUserID">
              回复
              <text class="ctheme" @tap="fnUserInfo(item.CommentData.ParentUserID)">
                {{item.CommentData.ParentNickName}}
              </text>
              ：{{item.Content}}
            </template>
            <template v-else>
              {{item.Content}}
            </template>
          </view>
          <view class="ptb18r f28r bts2r" v-if="item.CommentData.ParentUserID">
            <text class="ctheme" @tap="fnUserInfo(item.CommentData.ParentUserID)">{{item.CommentData.ParentNickName}}</text>
            <text class="c555 mlr8r" v-if="item.CommentData.ParentReplyUserID">回复</text>
            <text class="ctheme" v-if="item.CommentData.ParentReplyUserID" @tap="fnUserInfo(item.CommentData.ParentReplyUserID)">{{item.CommentData.ParentReplyNickName}}</text>
            <text class="c555">：{{item.CommentData.ParentContent}}</text>
          </view> -->
          <view class="flex flex-ais br4r" @tap="fnOpenInfo(item)">
            <image class="hw128r br4r" v-if="item.relation.dynamic_images" :src="item.relation.dynamic_images[0]"
              mode="aspectFill"></image>
            <view class="flex-fitem f28r ptb8r plr18r bgf8 c555 flex flex-aic">{{item.relation.dynamic_title}}</view>
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
		fnFormatLocalDate,
		getYearMonth
  } from "@/utils/CommonUtil.js"
  import {
    getCommentByNotify,
  } from "@/api/MessageServer.js"
  import {
    addComment,
  } from "@/api/InteractServer.js"
  import {dynamicDetailPage} from '@/utils/common.js'

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
				search_month: ''
      }
    },
		onLoad() {
			this.search_month = getYearMonth();
		},
    methods: {
      /// 下拉刷新的回调
      downCallback(mescroll) {
        // 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
        mescroll.resetUpScroll()
      },
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
				let search_month = this.search_month;
        getCommentByNotify({
					search_month: search_month,
          page: mescroll.num,
          limit: mescroll.size,
        }).then(res => {
			let lists = res.data;
			
			// 更新未读消息数量
			if (lists.set_read_nums) {
				let newsCount = this.$store.getters['getNewsCountData'];
				newsCount.comment_unreads = newsCount.comment_unreads - lists.set_read_nums;
				this.$store.commit('setNewsCountData', newsCount);
			}
			
			this.commentList = this.commentList.concat(lists.data);
			
			/**
			 * 如果当前月份记录查询完成，那么继续查询上一个月份的
			 */
			this.search_month = lists.month_table;
			// 如果月份不一致，那么page需要重置
			if (search_month != this.search_month) {
				mescroll.setPageNum(1);
			}
			
			if (lists.data.length <= 0 && search_month == this.search_month) {
				// 数据加载完毕
				mescroll.endSuccess(0, false);
			} else {
				mescroll.endSuccess(lists.per_page, true);
			}
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 计算时间格式 下午 08:12 | 昨日 09:12 | 2019-12-03 20:12
      calDateTime(str) {
        return fnFormatLocalDate(str * 1000)
      },
      /// 跳转用户信息页
      fnUserInfo(id) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?user_id=${user_id}`
        })
      },
      /// 跳转详情页
      fnOpenInfo(e) {
        dynamicDetailPage(e.relation, this, 'comment');
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

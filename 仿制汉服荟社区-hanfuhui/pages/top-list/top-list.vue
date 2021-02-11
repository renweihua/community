<template>
  <view> 
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(item,index) in topListData" :key="index">
        <view class="flex plr18r ptb28r bbs2r" @tap="fnUserInfo(item.User)">
          <user-avatar :src="item.User.HeadUrl + '_200x200.jpg'" :tag="item.User.AuthenticateCode" size="md"></user-avatar>
          <view class="flexc-jsa ml28r">
            <view>
              <text class="f28r fbold mr18r">{{item.User.NickName}}</text>
              <i-icon :type="item.User.Gender == '男' ?'nan':'nv' " size="28" :color="item.User.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
            </view>
            <view class="f24r cgray">{{fnDate(item.Datetime)}}</view>
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
    getTopList
  } from "@/api/InteractServer.js"

  export default {
    data() {
      return {
        id: -1,
        type: ''
      }
    },
    computed: {
      // 动态点赞列表数据
      topListData() {
        return this.$store.getters['interact/getTopListData']
      },
    },
    onLoad(options) {
      if (options && options.id) {
        this.id = parseInt(options.id)
        this.type = options.type
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
        getTopList({
          objectid: this.id,
          objecttype: this.type,
          page: mescroll.num,
          count: mescroll.size
        }).then(topRes => {
          if (mescroll.num == 1) {
            this.$store.commit('interact/setTopListData', topRes.data.Data)
          } else {
            this.$store.commit('interact/setTopListData', this.topListData.concat(topRes.data.Data))
          }
          mescroll.endSuccess(topRes.data.Data.length, topRes.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 跳转用户信息页
      fnUserInfo(e) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${e.ID}`
        })
      },
      /// 格式化时间
      fnDate(str) {
        return fnFormatDate(new Date(str || '2020-01-01 01:01').getTime())
      }
    }
  }
</script>

<style>
  page {
    background: #FFFFFF;
  } 
</style>

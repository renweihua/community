<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni @down="downCallback" @up="upCallback">
      <block v-for="(item,index) in punishListData" :key="index">
        <view class="ptb18r plr18r bgwhite mb18r">
          <!-- 用户 -->
          <view class="flexr-jsb flex-aic">
            <view class="flex">
              <user-avatar :src="item.User.HeadUrl+'_100x100.jpg'" :tag="item.user_info.uuid" size="md"></user-avatar>
              <view class="flexc-jsa ml28r">
                <view>
                  <text class="f28r fbold mr18r">{{item.User.NickName}}</text>
                  <i-icon :type="item.User.Gender == '男' ?'nan':'nv' " size="28" :color="item.User.Gender == '男' ?'#479bd4':'#FF6699'"></i-icon>
                </view>
                <view class="f24r cgray">{{calDatetime(item.Datetime)}}</view>
              </view>
            </view>
            <view class="f28r ctheme hl80r fcenter">处罚：{{item.Punish}}</view>
          </view>
          <!-- 原因 -->
          <view class="f28r fbold c555 mtb18r" v-if="item.Reason">违规原因：{{item.Reason}}</view>
          <!-- 处罚 -->
          <view class="bgf8 ptb18r plr18r" v-if="item.Note">
            <!-- 内容 -->
            <view class="fword f28r c111" :class="{mb18r: item.Pics}">{{item.Note}}</view>
            <!-- 图片格 -->
            <view class="flex flex-fww">
              <block v-for="(img,picIndex) in item.Pics" :key="picIndex">
                <image class="hw100v br4r flex-24v-mblr05v" :src="img+'_100x100.blur.jpg'" @tap="fnPreviewImage(picIndex,item.Pics)"
                  :lazy-load="true" mode="widthFix"></image>
              </block>
            </view>
          </view>
        </view>
      </block>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    previewImage
  } from "@/utils/UniUtil.js"
  import {
    fnFormatDate,
  } from "@/utils/CommonUtil.js"
  import {
    getUserViolationList
  } from "@/api/CommonServer.js"

  export default {
    computed: {
      // 处罚公示列表
      punishListData() {
        return this.$store.getters['common/getPunishListData']
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
        getUserViolationList({
          page: mescroll.num,
          count: mescroll.size
        }).then(violaRes => {
          if (mescroll.num == 1) {
            this.$store.commit('common/setPunishListData', violaRes.data.data)
          } else {
            this.$store.commit('common/setPunishListData', this.punishListData.concat(violaRes.data.data))
          }
          mescroll.endSuccess(violaRes.data.data.length, violaRes.data.data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 计算格式友好时间 几天前
      calDatetime(str) {
        let timestamp = new Date(str).getTime();
        return fnFormatDate(timestamp);
      },
      /// 预览图片组
      fnPreviewImage(current, urls) {
        previewImage(current, urls.map(url => url += "_0.jpg/format/webp"))
      },
    }
  }
</script>

<style></style>

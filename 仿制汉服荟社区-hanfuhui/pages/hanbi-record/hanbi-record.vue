<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni :down="{use:false}" @up="upCallback">
      <block v-for="(item,index) in orderList" :key="index">
        <view class="bgwhite bbs2r ptb18r plr18r flex">
          <image class="hw200r br8r mr18r" :src="item.Shop.ImgSrc + '_200x200.jpg'" mode="aspectFill"></image>
          <view class="flex-fitem flexc-jsa">
            <view class="f28r c555">{{item.Shop.Name}}</view>
            <view class="flexr-jsb">
              <view class="f28r fbold ctheme"><text class="f36r mr8r">{{item.Shop.Price}}</text>汉币</view>
              <view class="f36r cgray">{{item.State == 2 ? '已完成':'待发放'}}</view>
            </view>
          </view>
        </view>
      </block>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    getHanbiUserOrdersList
  } from "@/api/HanbiServer.js"

  export default {
    data() {
      return {
        orderList: []
      }
    },
    methods: {
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        getHanbiUserOrdersList({
          page: mescroll.num,
          limit: mescroll.size
        }).then(orderRes => {
          if (mescroll.num == 1) {
            this.orderList = orderRes.data.Data
          } else {
            this.orderList = this.orderList.concat(orderRes.data.Data)
          }
          mescroll.endSuccess(orderRes.data.Data.length, orderRes.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
    }
  }
</script>

<style>
</style>

<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni :down="{use:false}" @up="upCallback">
      <view class="plr18r">
           <block v-for="(item,index) in signinListData" :key="index">
        <view class="flexr-jsb hl90r bbs2r">
          <view class="f28r c555">
            {{calDatetime(item.SigninDate)}}
            <text class="bgtheme cwhite br8r plr8r ml8r" v-if="!item.Hanbi">补</text>
          </view>
          <view class="f32r cgray">第{{item.TodayRanking}}个签到，汉币 {{item.Hanbi}} 个</view>
        </view>
      </block>
      </view> 
    </mescroll-uni>
  </view>
</template>

<script> 
  import {
    getSignInList
  } from "@/api/HanbiServer.js"

  export default { 
    computed: {
      // 签到日期记录数据
      signinListData() {
        return this.$store.getters['getSigninListData']
      },
    }, 
    methods: {
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        getSignInList({ 
          page: mescroll.num,
          count: mescroll.size
        }).then(signinRes => {  
          if (mescroll.num == 1) {
            this.$store.commit('setSigninListData', signinRes.data.Data)
          } else {
            this.$store.commit('setSigninListData', this.signinListData.concat(signinRes.data.Data))
          }
          mescroll.endSuccess(signinRes.data.Data.length, signinRes.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 计算格式时间 2019-02-02
      calDatetime(str) {
        let _data = new Date(str);
        let year = _data.getFullYear(); //年
        let month = _data.getMonth() + 1; //月
        let day = _data.getDate(); //日
        // 个位补零
        month = month < 10 ? "0" + month : month
        day = day < 10 ? "0" + day : day 
        return `${year}-${month}-${day}`;
      },
    }
  }
</script>

<style> 
  page {
    background: #FFFFFF;
  }
</style>

<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni :down="{use:false}" @up="upCallback">
      <!-- 我的汉币 -->
      <view class="plr18r ptb28r f28r flexr-jsb flex-aic bgwhite mb28r">
        <view class="cgray">
          我的汉币：<text class="ctheme">{{hanbiCount}}</text>
        </view>
        <view class="flex flex-aic" @tap="fnSigninRecord">
          <view class="ctheme mr8r">我的兑换</view>
          <i-icon type="you" size="36" color="#FF6699"></i-icon>
        </view>
      </view>

      <!-- 汉币规则 -->
      <view class="plr28r ptb28r bgwhite mb28r">
        <view class="flexr-jsc flex-aic mb28r">
          <view class="line-gl-c222"></view>
          <view class="f28r mlr28r cgray">汉币规则</view>
          <view class="line-gr-c222"></view>
        </view>
        <block v-for="(item,index) in hanbiRule" :key="index">
          <view class="f28r c555" :class="{mb18r: index < hanbiRule.length -1}">{{item}}</view>
        </block>
      </view>

      <!-- 汉币商品 -->
      <block v-for="(item,index) in signinShopListData" :key="index">
        <view class="bgwhite mb18r">
          <view class="bbs2r ptb18r plr18r flex">
            <image class="hw200r br8r mr18r" :src="item.ImgSrc + '_200x200.jpg'" mode="aspectFill"></image>
            <view class="flex-fitem flexc-jsa">
              <view class="f28r c555">{{item.Name}}</view>
              <view class="f28r ctheme hl80r bbdash2r"><text class="f36r mr8r">{{item.Price}}</text>汉币</view>
              <view class="f24r cgray">
                <text class="ctheme mr8r">{{item.Orders.User.NickName}}</text> {{calDatetime(item.Orders.Datetime)}}分钟前换取了
              </view>
            </view>
          </view>
          <view class="flexr-jfe ptb18r plr18r">
            <view class="f28r bgtheme cwhite br8r plr18r ptb18r" @tap="fnHanbiExc(item.ID,item.Price)">汉币兑换</view>
          </view>
        </view>
      </block>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    fnFormatDate,
  } from "@/utils/CommonUtil.js"
  import {
    getSysParamValue
  } from "@/api/CommonServer.js"
  import {
    getHanbiShopList,
    addHanbiOrders
  } from "@/api/HanbiServer.js"

  export default {
    computed: {
      // 签到商品列表
      signinShopListData() {
        return this.$store.getters['getSigninShopListData']
      },
      // 我的汉币数
      hanbiCount() {
        return this.$store.getters['user/getUserInfoData'].Hanbi
      },
      // 汉币规则
      hanbiRule() {
        return this.$store.getters['getHanbiRuleData']
      },
    },

    onLoad(options) {
      // 获取汉币规则
      getSysParamValue('hanbirule').then(res => {
        this.$store.commit('setHanbiRuleData', res.data.Data.Value.match(/[^><]+(?=<\/p>)/img))
      })
    },

    methods: {
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        getHanbiShopList({
          haveorders: true,
          role: 5,
          page: mescroll.num,
          limit: mescroll.size
        }).then(shopRes => {
          if (mescroll.num == 1) {
            this.$store.commit('setSigninShopListData', shopRes.data.Data)
          } else {
            this.$store.commit('setSigninShopListData', this.signinShopListData.concat(shopRes.data.Data))
          }
          mescroll.endSuccess(shopRes.data.Data.length, shopRes.data.Data.length >= mescroll.size);
        }).catch(() => {
          mescroll.endErr();
        })
      },
      /// 计算格式友好时间 几天前
      calDatetime(str) {
        let timestamp = new Date(str).getTime();
        return fnFormatDate(timestamp);
      },
      /// 跳转汉币兑换记录
      fnSigninRecord() {
        uni.navigateTo({
          url: '/pages/hanbi-record/hanbi-record'
        })
      },
      /// 汉币兑换
      fnHanbiExc(id, price) {
        if (this.orderState) return
        uni.showModal({
          content: '确认兑换吗？',
          success: res => {
            if (res.confirm) {
              this.orderState = true
              uni.showLoading({
                title: '兑换中',
                mask: true
              })
              addHanbiOrders({
                shopid: id,
                useraddressid: 0,
                remark: '',
              }).then(res => {
                uni.hideLoading()
                uni.showToast({
                  title: '兑换成功'
                })
                this.orderState = false;
                // 登录用户汉币数减少
                let tempUser = this.$store.getters['user/getUserInfoData']
                tempUser.Hanbi = Number(tempUser.Hanbi) - Number(price)
                this.$store.commit('user/setUserInfoData', tempUser)
                // 重新初始签到日历
                this.$refs.signinCalendar.init()
              }).catch(() => {
                this.orderState = false;
              })
            }
          }
        })
      }
    }
  }
</script>

<style></style>

<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni :bottom="90" :down="{use:false}" :up="{use:false}">
      <!-- 封面 -->
      <image class="org-cover" :src="calOrgCover" mode="widthFix"></image>
      <!-- 标题 -->
      <view class="ptb28r plr18r f36r c111">{{orgInfoData.Name || '#'}}</view>
      <!-- 报名数 -->
      <view class="ptb18r plr18r f28r cgray bbs2r">
        已报名<text class="c555 mlr18r">{{orgInfoData.SalesVolume || 0}}</text>人，剩余<text class="ctheme mlr18r">{{orgInfoData.Stock || 0}}</text>个名额
      </view>
      <!-- 时间 -->
      <view class="ptb18r plr18r bbs2r">
        <i-icon type="shijian" size="56" color="#8F8F94"></i-icon>
        <text class="f28r c555 ml18r">{{calBeginDatetime}} ~ {{calEndDatetime}}</text>
      </view>
      <!-- 位置 -->
      <view class="ptb18r plr18r bbs2r">
        <i-icon type="weizhi" size="56" color="#8F8F94"></i-icon>
        <text class="f28r c555 ml18r">{{calAddress}} {{orgInfoData.AddressStreet || '未知'}}</text>
      </view>
      <!-- 价格 -->
      <view class="ptb18r plr18r bbs2r">
        <i-icon type="yuanbao" size="56" color="#8F8F94"></i-icon>
        <text class="f28r c555 ml18r">{{orgInfoData.Price || 0}}</text>
      </view>
      <!-- 手机 -->
      <view class="ptb18r plr18r bbs2r">
        <i-icon type="shouji" size="56" color="#8F8F94"></i-icon>
        <text class="f28r c555 ml18r">{{orgInfoData.HeadPhone || '00000000000'}}（负责人：{{orgInfoData.HeadPerson || '未知'}}）</text>
      </view>
      <!-- 活动内容 -->
      <view class="f28r c555 fword plr18r ptb18r mb64r">
        <u-parse :content="orgInfoData.Describe" :loading="!orgInfoData.Describe" :img-options="false"></u-parse>
      </view>
      <!-- 活动组织 -->
      <view class="mautoblock fcenter mb64r">
        <image class="hw200r br50v" :src="orgInfoData.Org ? orgInfoData.Org.LogoSrc+ '_100x100.jpg':'/static/default_avatar.png'"
          mode="aspectFill"></image>
        <view class="f28r c111">{{orgInfoData.Org ? orgInfoData.Org.ShortName : '#'}}</view>
      </view>
      <!-- 已报名人数头像 -->
      <view class="f24r c555 fcenter mb28r">{{userArray.length || 0}} 人已报名</view>
      <view class="flexr-jsa flex-fww plr18r" v-if="userArray">
        <block v-for="item in userArray" :key="item.ID">
          <user-avatar :src="item.User.HeadUrl + '_200x200.jpg'" :tag="item.user_info.uuid ? item.user_info.uuid : ''" size="md"></user-avatar>
        </block>
      </view>
    </mescroll-uni>

    <view class="flex bts2r posif posi-blr0">
      <view class="hl90r fcenter flex-fitem f28r c555 bgwhite">我要报名</view>
      <view class="hl90r fcenter flex-fitem f28r cwhite bgtheme">立即报名</view>
    </view>

  </view>
</template>

<script>
  import {
    fnFormatLocalDate
  } from "@/utils/CommonUtil.js"
  import {
    getOrgInfo,
  } from "@/api/OrgServer.js"

  // HTML富文本解析器
  import uParse from '@/components/gaoyia-parse/parse.vue'

  export default {
    components: {
      uParse
    },

    data() {
      return {
        // 活动项信息ID
        orgID: -1,
        // 跳转来源页
        fromPage: '',
        // 来源页标签数据下标
        current: -1,
        // 报名用户
        userArray: []
      }
    },

    onLoad(options) {
      if (options && options.id) {
        console.log(options);
        this.orgID = parseInt(options.id);
        if (typeof options.fromPage == 'string') this.fromPage = options.fromPage
        if (typeof options.current == 'string') this.current = parseInt(options.current)
        // 获取详情信息
        uni.showLoading({
          title: '加载中',
          mask: true
        })
        getOrgInfo(this.orgID).then(orgRes => {
          this.userArray = orgRes.data.Remark.users
          this.$store.commit('org/setOrgInfoData', orgRes.data.data)
          // 导航标题
          uni.setNavigationBarTitle({
            title: orgRes.data.data.Name
          });
          // 延迟关闭load
          setTimeout(() => {
            uni.hideLoading()
          }, 2000)
        })
      }
    },

    computed: {
      // 组织活动内容信息
      orgInfoData() {
        return this.$store.getters['org/getOrgInfoData']
      },
      /// 计算显示组织封面
      calOrgCover() {
        if (typeof this.orgInfoData.FaceSrc == 'undefined') return '/static/default_image.png'
        return this.orgInfoData.FaceSrc + '_700x700.jpg'
      },
      /// 计算开始时间 格式 2019-12-03 20:12
      calBeginDatetime() {
        let now = new Date(this.orgInfoData.BeginDatetime || '2020-01-01 01:01');
        return fnFormatLocalDate(now);
      },
      /// 计算结束时间 格式 2019-12-03 20:12
      calEndDatetime() {
        let now = new Date(this.orgInfoData.EndDatetime || '2020-01-01 01:01');
        return fnFormatLocalDate(now);
      },
      /// 地址逗号换空格
      calAddress() {
        let addr = this.orgInfoData.AddressNames;
        return !!addr ? addr.split(',').join(' ') : '未知 未知'
      }
      //
    },

    methods: {
      /// 跳转用户信息页
      fnUserInfo(e) {
        uni.navigateTo({
          url: `/pages/user-info/user-info?id=${e.ID}`
        })
      },
      //
    },
  }
</script>

<style>
  page {
    background: #FFFFFF;
  }

  /* 组织封面 */
  .org-cover {
    display: block;
    width: 100%;
    height: 500rpx;
  }
</style>

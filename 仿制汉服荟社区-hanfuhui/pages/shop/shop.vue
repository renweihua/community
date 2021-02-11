<template>
  <view>
    <!-- 商城顶部导航栏 -->
    <view class="posif posi-tlr0 z500 bgtheme">
      <!-- #ifdef APP-PLUS -->
      <view class="status-bar"></view>
      <!-- #endif -->
      <view class="flex plr18r">
        <view class="hl90r pr28r">
          <i-icon type="dingdan" size="56" color="#FFFFFF"></i-icon>
        </view>
        <view class="hl90r pr28r">
          <i-icon type="gouwuche" size="56" color="#FFFFFF"></i-icon>
        </view>
        <view class="hl90r pr28r">
          <i-icon type="sousuo" size="56" color="#FFFFFF"></i-icon>
        </view>
      </view>
    </view>

    <!-- 滚动内容区 -->
    <mescroll-uni :top="calTop" :bottom="112" @down="downCallback" @up="upCallback" @init="mescrollInit">
      <!-- 类型网格 -->
      <view class="flex flex-fww fcenter bgwhite pt18r bbs2r">
        <view class="w25v-mb2v">
          <image class="hw96r br4r" src="/static/icon-nav-shop/icon_type_qixiong.png" mode="aspectFill"></image>
          <view class="f28r cgray">齐胸</view>
        </view>
        <view class="w25v-mb2v">
          <image class="hw96r br4r" src="/static/icon-nav-shop/icon_type_beizi.png" mode="aspectFill"></image>
          <view class="f28r cgray">褙子</view>
        </view>
        <view class="w25v-mb2v">
          <image class="hw96r br4r" src="/static/icon-nav-shop/icon_type_jiaoling.png" mode="aspectFill"></image>
          <view class="f28r cgray">交领襦裙</view>
        </view>
        <view class="w25v-mb2v">
          <image class="hw96r br4r" src="/static/icon-nav-shop/icon_type_duijing.png" mode="aspectFill"></image>
          <view class="f28r cgray">对襟襦裙</view>
        </view>
        <view class="w25v-mb2v">
          <image class="hw96r br4r" src="/static/icon-nav-shop/icon_type_aoqun.png" mode="aspectFill"></image>
          <view class="f28r cgray">袄裙</view>
        </view>
        <view class="w25v-mb2v">
          <image class="hw96r br4r" src="/static/icon-nav-shop/icon_type_zhoubian.png" mode="aspectFill"></image>
          <view class="f28r cgray">配饰周边</view>
        </view>
        <view class="w25v-mb2v">
          <image class="hw96r br4r" src="/static/icon-nav-shop/icon_type_hanyuansu.png" mode="aspectFill"></image>
          <view class="f28r cgray">汉元素</view>
        </view>
        <view class="w25v-mb2v">
          <image class="hw96r br4r" src="/static/icon-nav-shop/icon_type_more.png" mode="aspectFill"></image>
          <view class="f28r cgray">全部分类</view>
        </view>
      </view>
      <!-- 品牌、二手 -->
      <view class="flexr-jsa h112r mb18r bgwhite">
        <view class="flex-fitem flexr-jsc flex-aic brs2r">
          <image class="hw64r" src="/static/icon-nav-shop/icon_nav_brand.png" mode="aspectFill"></image>
          <view class="f32r cgray ml28r">品牌馆</view>
        </view>
        <view class="flex-fitem flexr-jsc flex-aic">
          <image class="hw64r" src="/static/icon-nav-shop/icon_nav_secondhand.png" mode="aspectFill"></image>
          <view class="f32r cgray ml28r">二手铺</view>
        </view>
      </view>
      <!-- 专题模块 -->
      <view class="plr18r ptb18r bgwhite mb18r">
        <view class="flexr-jsc flex-aic mb18r">
          <view class="line-gl-c222"></view>
          <view class="f28r mlr28r cgray">最新专题</view>
          <view class="line-gr-c222"></view>
        </view>
        <block v-for="special in specialListData" :key="special.ID">
          <view class="mb18r">
            <image class="special-cover" :src="special.FaceUrl + '_min.jpg'" mode="aspectFill" :lazy-load="true"></image>
            <scroll-view class="special-bar" :id="'special-'+special.ID" :scroll-x="true" :show-scrollbar="false">
              <block v-for="item in special.Products" :key="item.ID">
                <view class="scroll-item" :id="'Product-'+item.ID" :data-current="item.ID">
                  <image class="scroll-cover" :src="item.FaceSrc + '_200x200.jpg'" mode="aspectFill" :lazy-load="true"></image>
                  <view class="f28r fcenter cred">￥{{item.BasePrice}}</view>
                </view>
              </block>
              <view class="scroll-item">
                <image class="scroll-cover" src="/static/see_more.png" mode="aspectFill"></image>
                <view class="f28r fcenter cred" style="color: transparent;">查看更多</view>
              </view>
            </scroll-view>
          </view>
        </block>
        <view class="special-more" v-if="specialListData.length">
          <text class="f28r mr8r">更多专题</text>
          <i-icon type="you" size="32" color="#FF6699"></i-icon>
        </view>
      </view>
      <!-- 本周热卖模块 -->
      <view class="plr18r ptb18r bgwhite mb18r">
        <view class="flexr-jsc flex-aic mb18r">
          <view class="line-gl-c222"></view>
          <view class="f28r mlr28r cgray">本周热卖</view>
          <view class="line-gr-c222"></view>
        </view>
        <view class="flexr-jsb flex-fww">
          <block v-for="(item,index) in productHottestListData" :key="index">
            <view class="w32v-mb2v">
              <image class="sellers-cover" :src="item.FaceSrc + '_300x300.jpg'" mode="aspectFill" :lazy-load="true"></image>
              <view class="ellipsis f24r c555 mtb8r">{{item.Name}}</view>
              <view class="f32r cred"><text class="f24r">￥</text>{{item.BasePrice}}</view>
            </view>
          </block>
        </view>
      </view>
      <!-- 精选美物 -->
      <view class="plr18r ptb18r">
        <view class="flexr-jsc flex-aic mb18r">
          <view class="line-gl-c222"></view>
          <view class="f28r mlr28r cgray">精选美物</view>
          <view class="line-gr-c222"></view>
        </view>
        <view class="flexr-jsb flex-fww">
          <view v-for="(infoData,index) in productBestListData" :key="index" class="w48v-mb2v">
            <shop-card :info-data="infoData"></shop-card>
          </view>
        </view>
      </view>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    getSpecialList,
    getProductHottestList,
    getProductList,
  } from '@/api/ShopServer.js';

  // 商品展示卡组件
  import ShopCard from '@/components/shop-card/shop-card'

  export default {
    components: {
      ShopCard
    },

    props: {
      // 连续触发刷新
      refresh: {
        type: Boolean,
        default: false
      }
    },

    data() {
      return {
        // 滚动区实例
        mescroll: null,
        // 
      }
    },

    watch: {
      // 监听底部导航双击触发
      refresh(newVal) {
        if (newVal) this.fnRefreshData();
      }
    },

    computed: {
      // 专题列表
      specialListData() {
        return this.$store.getters['shop/getSpecialListData']
      },
      // 热卖列表
      productHottestListData() {
        return this.$store.getters['shop/getProductHottestListData']
      },
      // 精选推荐列表
      productBestListData() {
        return this.$store.getters['shop/getProductBestListData']
      },
      // 计算距离顶部距离差异
      calTop() {
        let top = 90;
        // #ifdef H5
        top = 270;
        // #endif
        return top
      }
      //
    },

    methods: {
      /// mescroll组件初始化的回调,可获取到mescroll对象
      mescrollInit(mescroll) {
        this.mescroll = mescroll;
      },
      /// 下拉刷新的回调
      downCallback(mescroll) {
        // 下拉刷新的回调,默认重置上拉加载列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
        this.mescroll.resetUpScroll()
      },
      /// 上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10
      upCallback(mescroll) {
        if (mescroll.num == 1) {
          /// 首次加载专题最热推荐
          Promise.all([
            getSpecialList({
              page: 1,
              count: 3
            }),
            getProductHottestList(),
            getProductList({
              good: true,
              page: mescroll.num,
              count: mescroll.size
            }),
          ]).then(resArray => {
            this.$store.commit('shop/setSpecialListData', resArray[0].data.Data)
            this.$store.commit('shop/setProductHottestListData', resArray[1].data.Data)
            this.$store.commit('shop/setProductBestListData', resArray[2].data.Data)
            mescroll.endSuccess(resArray[2].data.Data.length, resArray[2].data.Data.length >= mescroll.size);
          }).catch(() => {
            mescroll.endSuccess(0, false);
          });
        } else {
          // 推荐商品列表追加
          getProductList({
            good: true,
            page: mescroll.num,
            count: mescroll.size
          }).then(productRes => {
            this.$store.commit('shop/setProductBestListData', this.productBestListData.concat(productRes.data.Data))
            mescroll.endSuccess(productRes.data.Data.length, productRes.data.Data.length >= mescroll.size);
          }).catch(() => {
            mescroll.endErr();
          });
        }
      },

      /// 获取新数据
      fnRefreshData() {
        this.mescroll.scrollTo(0, 300);
        setTimeout(() => {
          this.mescroll.resetUpScroll(true)
        }, 1000)
      },
    }
  }
</script>

<style>
  /*专题模块-主封面*/
  .special-cover {
    height: 220rpx;
    width: 100%;
    border-radius: 8rpx;
    display: block;
    margin-bottom: 8rpx;
  }

  /*专题模块-滑动视图*/
  .special-bar {
    width: 100%;
    height: 236rpx;
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: flex-start;
    white-space: nowrap;
    flex-wrap: nowrap;
    background: #FFFFFF;
    overflow: hidden;
  }

  /*专题模块-滑动视图项*/
  .scroll-item {
    display: inline-block;
    flex-wrap: nowrap;
    white-space: nowrap;
    padding-right: 18rpx;
  }

  .scroll-item:last-child {
    display: inline-block;
    flex-wrap: nowrap;
    white-space: nowrap;
    padding-right: 0;
  }

  /*专题模块-滑动视图项封面*/
  .scroll-cover {
    width: 180rpx;
    height: 180rpx;
    border-radius: 8rpx;
  }

  /*专题模块-更多按钮*/
  .special-more {
    padding: 8rpx 28rpx;
    margin: 0 auto;
    border: 2rpx #FF6699 solid;
    border-radius: 8rpx;
    color: #FF6699;
    width: 25%;
    text-align: center;
  }

  /*本周热卖-项封面*/
  .sellers-cover {
    height: 220rpx;
    width: 100%;
    border-radius: 4rpx;
    display: block
  }
</style>

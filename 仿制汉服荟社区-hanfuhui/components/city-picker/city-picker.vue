<template>
  <view class="posia posi-all0 z700" v-if="visible">
    <view class="posif posi-all0 anima-punch-in3 bgblack-a3" @tap="$_close"></view>
    <view class="posif posi-blr0 bgwhite anima-slide-up3">
      <view class="flexr-jsa f36r hl90r plr28r">
        <view class="cgray" @tap="$_close">取消</view>
        <view class="flex-fitem fcenter c555">{{cityNames[0] || '□□'}} - {{cityNames[1] || '□□'}}</view>
        <view class="ctheme" @tap="$_picker">确认</view>
      </view>
      <view class="picker-h500r">
        <picker-view style="height: 100%;background: #F8F8F8;" :indicator-style="indicatorStyle" :value="value" @change="$_change">
          <picker-view-column>
            <view class="flexr-jsc flex-aic f36r c555" v-for="item in parentListData" :key="item.ID">{{item.Name}}</view>
          </picker-view-column>
          <picker-view-column>
            <view class="flexr-jsc flex-aic f36r c555" v-for="item in cityListData" :key="item.ID">{{item.Name}}</view>
          </picker-view-column>
        </picker-view>
      </view>
    </view>
  </view>
</template>

<script>
  import {
    getCityList
  } from "@/api/CommonServer.js"

  /**
   * 城市选择弹出层组件
   * 通过ref调用open打开
   * @event {Function} picker 完成选择 点击事件
   */
  export default {
    data() {
      return {
        visible: false,
        indicatorStyle: `height: ${Math.round(uni.getSystemInfoSync().screenWidth/(750/100))}px;`,
        cityIDs: ['1', '2'],
        cityNames: ['北京', '东城'],
        value: [0, 0],
      }
    },

    computed: {
      parentListData() {
        return this.$store.getters['common/getProvinceListData']
      },
      cityListData() {
        return this.$store.getters['common/getCityListData']
      }
    },

    methods: {
      open(e) {
        this.cityIDs = e;
        // 找到省级下标
        this.parentListData.some((item, index) => {
          if (e[0] == item.ID) {
            this.cityNames[0] = item.Name
            this.value[0] = index
            return
          }
        })
        // 找到城市下标
        this.cityListData.some((item, index) => {
          if (e[1] == item.ID) {
            this.cityNames[1] = item.Name
            this.value[1] = index
            return
          }
        })
        this.visible = true;
      },
      $_close() {
        this.$emit('picker', {
          cityIDs: [0,0],
          cityNames: ['',''],
        })
        this.visible = false;
      },
      /// 点击确定完成选择
      $_picker() {
        this.$emit('picker', {
          cityIDs: this.cityIDs,
          cityNames: this.cityNames,
        })
        this.visible = false;
      },
      /// 滚动改变值
      $_change(e) {
        let val = e.detail.value
        let parent = this.parentListData[val[0]]
        let city = this.cityListData[val[1]]
         // 选择其他省时改变城市列表
        if (parent.ID != this.cityIDs[0]) {
          getCityList(parent.ID).then(cityRes => {
            this.$store.commit('common/setCityListData', cityRes.data.data)
            this.cityIDs = [parent.ID, 0]
            this.cityNames = [parent.Name, '□□']
          })
          return
        }
        this.cityIDs = [parent.ID, city.ID];
        this.cityNames = [parent.Name, city.Name];
      }
    }
  }
</script>

<style></style>

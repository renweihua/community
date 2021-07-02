<template>
  <view class="posia posi-all0 z700" v-if="visible">
    <view class="posif posi-all0 anima-punch-in3 bgblack-a3" @tap="$_close"></view>
    <view class="posif posi-blr0 bgwhite anima-slide-up3">
      <view class="flexr-jsa f36r hl90r plr28r">
        <view class="cgray" @tap="$_close">取消</view>
        <view class="flex-fitem fcenter c555">{{provinceName || '□□'}} - {{ stateName[stateID] || '□□' }}</view>
        <view class="ctheme" @tap="$_picker">确认</view>
      </view>
      <view class="picker-h500r">
        <picker-view style="height: 100%;background: #F8F8F8;" :indicator-style="indicatorStyle" :value="value" @change="$_change">
          <picker-view-column>
            <view class="flexr-jsc flex-aic f36r c555" v-for="item in orgProvinceListData" :key="item.ID">{{item.Name}}</view>
          </picker-view-column>
          <picker-view-column>
            <view class="flexr-jsc flex-aic f36r c555" v-for="(item,index) in stateName" :key="index">{{item}}</view>
          </picker-view-column>
        </picker-view>
      </view>
    </view>
  </view>
</template>

<script>
  /**  
   * 组织活动省市选择弹出层组件
   * 通过ref调用open打开
   * @event {Function} picker 完成选择 点击事件  
   */
  export default {
    data() {
      return {
        visible: false,
        indicatorStyle: `height: ${Math.round(uni.getSystemInfoSync().screenWidth/(750/100))}px;`,
        provinceID: 0,
        provinceName: '全国',
        stateID: 0,
        stateName: ['全部状态', '报名中', '进行中', '已结束'],
        value: [0, 0],
      }
    },

    computed: {
      orgProvinceListData() {
        return this.$store.getters['org/getOrgProvinceListData']
      }
    },

    methods: {
      open(e) {
        this.provinceID = e.province;
        this.provinceName = e.provinceName;
        this.stateID = e.state;
        this.value[1] = e.state
        // 找到对应省下标
        this.orgProvinceListData.some((item, index) => {
          if (e.province == item.ID) {
            this.provinceName = item.Name
            this.value[0] = index
            return
          }
        })
        this.visible = true;
      },
      $_close() {
        this.$emit('picker', {
          provinceID: -1,
          provinceName: '全国',
          stateID: -1,
          stateName: '全部状态',
        })
        this.visible = false;
      },
      /// 点击确定完成选择
      $_picker() {
        this.$emit('picker', {
          provinceID: this.provinceID,
          provinceName: this.provinceName,
          stateID: this.stateID,
          stateName: this.stateName[this.stateID],
        })
        this.visible = false;
      },
      /// 滚动改变值
      $_change(e) {
        let val = e.detail.value 
        let province = this.orgProvinceListData[val[0]] 
        this.provinceID = province.ID
        this.provinceName = province.Name
        this.stateID = val[1] 
      }
    }
  }
</script>

<style></style>

<template>
  <view>
    <view class="f32r">
      举报输入
    </view>
    <view class="f28r">
      恶意举报会禁言一天。这个就不提交了
    </view>
  </view>
</template>

<script>
  export default {
    data() {
      return {

      };
    },

    onLoad(options) {
      if (options && options.id) {
        console.log(options);
      }
    }
  }
</script>

<style>

</style>

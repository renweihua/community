<template>
  <view>
    <!-- 滚动内容区 -->
    <mescroll-uni :down="{use:false}" :up="{use:false}">
      <view class="mb18r bgwhite plr28r ">
        <view class="f28r cgray pt18r">聊天设置</view>
        <view class="flex flex-aic hl90r bbs2r" @tap="fnChat(1)">
          <text class="f36r c111 flex-gitem">接收所有人聊天</text>
          <i-icon type="gou" size="42" color="#FF6699" v-if="chatState == 1"></i-icon>
        </view>
        <view class="flex flex-aic hl90r " @tap="fnChat(2)">
          <text class="f36r c111 flex-gitem">仅接受关注的人与认证账号聊天</text>
          <i-icon type="gou" size="42" color="#FF6699" v-if="chatState == 2"></i-icon>
        </view>
      </view>
      <view class="mb18r bgwhite plr28r">
        <view class="flex flex-aic hl90r bbs2r">
          <text class="f36r c111 flex-gitem">隐藏主页赞过</text>
          <switch type="switch" color="#FF6699" style="transform:scale(0.6)" :checked="calHideTop" @change="fnHomeTop"></switch>
        </view>
      </view>
    </mescroll-uni>
  </view>
</template>

<script>
  import {
    modifyHideTop,
    getChatState,
    modifyChatState
  } from "@/api/UserServer.js"

  export default {
    data() {
      return {
        // 聊天设置中状态
        chatState: 1,
        // 登录用户ID
        id: 0,
      }
    },
    onLoad(options) {
      if (options && options.id) {
        this.id = parseInt(options.id)
        // 获取聊天设置状态
        getChatState().then(res => {
          this.chatState = res.data.Data
        })
      }
    },
    computed: {
      // 计算隐藏主页赞过
      calHideTop() {
        return this.$store.getters['user/getLoginUserInfoData'].HideTop
      }
    },

    methods: {
      /// 聊天设置状态
      fnChat(state) {
        modifyChatState(state).then(modifyRes => {
          if (modifyRes.data.Data == false) return
          this.chatState = state
        })
      },
      /// 主页赞过开关
      fnHomeTop(e) {
        modifyHideTop(e.detail.value).then(modifyRes => {
          if (modifyRes.data.Data == false) return
          // 登录用户赞过状态改变
          let login_user = this.$store.getters['user/getLoginUserInfoData']
		  if(!login_user.user_info) return;
          login_user.HideTop = e.detail.value
          this.$store.commit('user/setLoginUserInfoData', login_user)
        })
      }
    }
  }
</script>

<style>
</style>

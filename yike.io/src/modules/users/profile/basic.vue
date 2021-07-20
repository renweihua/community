<template>
  <div class="box">
    <div class="box-heading border-bottom">
      <h5>个人信息</h5>
    </div>
    <form class="w-50" @submit.prevent="submit">
      <div class="form-group">
        <label>昵称</label>
        <input type="text" class="form-control" v-model="user.user_info.nick_name">
      </div>
      <div class="form-group">
        <label>性别</label>
        <div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="male" class="custom-control-input" value="0" v-model="user.user_info.user_sex">
            <label class="custom-control-label" for="male">男</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="female" class="custom-control-input" value="1" v-model="user.user_info.user_sex">
            <label class="custom-control-label" for="female">女</label>
          </div>
        </div>
      </div>
      <div class="form-group" v-if="true">
        <label>账户</label>
        <input type="text" class="form-control" v-model="user.user_name" disabled>
        <small class="form-text text-muted">您可以管理您的<router-link :to="{ name: 'user.account', hash: '#edit-phone' }" class="text-blue">账户设置</router-link>。</small>
      </div>
      <div class="form-group" v-if="true">
        <label>手机号码</label>
        <input type="text" class="form-control" v-model="user.user_mobile" disabled>
        <small class="form-text text-muted">您可以管理您的<router-link :to="{ name: 'user.account', hash: '#edit-phone' }" class="text-blue">手机号码设置</router-link>。</small>
      </div>
      <div class="form-group">
        <label>邮箱地址</label>
        <input type="email" class="form-control" placeholder="example@yike.io" v-model="user.user_email" disabled>
        <small class="form-text text-muted">您可以管理您的<router-link :to="{ name: 'user.account', hash: '#edit-email' }" class="text-blue">邮箱地址设置</router-link>。</small>
      </div>
      <div class="form-group">
        <label>座右铭</label>
        <textarea class="form-control" v-model="user.user_info.basic_extends.user_introduction"></textarea>
        <small class="form-text text-muted">You can @mention other users and organizations to link to them.</small>
      </div>
      <div class="form-group">
        <label>个人主页</label>
        <input type="text" class="form-control" v-model="user.user_info.other_extends.home_url">
      </div>
      <div class="form-group">
        <label>公司</label>
        <input type="text" class="form-control" v-model="user.user_info.other_extends.company">
        <small class="form-text text-muted">You can @mention your company’s GitHub organization to link it.</small>
      </div>
      <div class="form-group">
        <label>当前所在地</label>
        <input type="text" class="form-control" v-model="user.user_info.basic_extends.location">
      </div>
      <button type="submit" class="btn btn-primary rounded">保存</button>
    </form>
  </div>
</template>

<script>
import { cloneDeepWith } from 'lodash'
import { mapGetters, mapActions } from 'vuex'

export default {
  data () {
    return {
      user: {
        other_extends: {
          home_url: '',
          company: '',
        },
        basic_extends: {
          location: ''
        }
      }
    }
  },
  computed: {
    ...mapGetters(['currentUser'])
  },
  created () {
    this.user = cloneDeepWith(this.currentUser)
  },
  methods: {
    ...mapActions(['setUser']),
    async submit () {
      const result = await this.$http.patch(
        `user/update`,
        this.user.user_info
      )

      if (result) {
        this.setUser(result)
        this.$message.success('成功修改用户信息')
      }
    }
  }
}
</script>

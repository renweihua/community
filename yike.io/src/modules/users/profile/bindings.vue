<template>
  <div class="boxes">
    <div class="box">
      <div class="box-heading border-bottom">
        <h5>第三方账户绑定</h5>
      </div>
      <form class="w-100">
        <div class="form-group row d-flex align-items-center">
          <label for="github-form" class="col-sm-2 col-form-label">
            <button type="button" class="text-16 btn btn-dark btn-icon mx-auto">
              <qq-icon></qq-icon>
            </button>
          </label>
          <div class="col-sm-10 pl-0">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" v-if="!user_otherlogin.qq_info || !user_otherlogin.qq_info.pc_openid" @click="oauth('qq')">立即绑定</span>
                <span class="input-group-text" v-else>已绑定</span>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row d-flex align-items-center">
          <label for="twitter-form" class="col-sm-2 col-form-label">
            <button type="button" class="text-16 btn btn-twitter btn-icon mx-auto">
              <github-icon/>
            </button>
          </label>
          <div class="col-sm-10 pl-0">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" v-if="!user_otherlogin.github_info || !user_otherlogin.github_info.pc_openid" @click="oauth('github')">立即绑定</span>
                <span class="input-group-text" v-else>已绑定</span>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row d-flex align-items-center">
          <label for="twitter-form" class="col-sm-2 col-form-label">
            <button type="button" class="text-16 btn btn-twitter btn-icon mx-auto">
              <img src="/icon/weibo.ico" />
            </button>
          </label>
          <div class="col-sm-10 pl-0">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" v-if="!user_otherlogin.weibo_info || !user_otherlogin.weibo_info.pc_openid" @click="oauth('weibo')">立即绑定</span>
                <span class="input-group-text" v-else>已绑定</span>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import QqIcon from '$icons/Qqchat';
import GithubIcon from '$icons/GithubCircle';

export default {
  components: {
    QqIcon,
    GithubIcon
  },
  data () {
    return {
      user_otherlogin: {
      }
    }
  },
  created () {
    this.user_otherlogin = this.$user().user_otherlogin;
  },
  methods: {
  oauth (platform) {
      let iOSChrome =
        /Mobile/.test(navigator.userAgent) && /CriOS/.test(navigator.userAgent)
      let url = '/oauth/oauth-redirect/' + platform
      if (iOSChrome) {
        window.location.href = url;
      } else {
        let windowObjectReference = null
        if (windowObjectReference == null || windowObjectReference.closed) {
          let heights = {
            github: 680,
            google: 540,
            facebook: 700
          }
          windowObjectReference = this.popupCenter(
            url,
            '_blank',
            500,
            heights[platform]
          )
        } else {
          windowObjectReference.focus();
        }
      }
    },
    popupCenter (url, title, w, h) {
      // Fixes dual-screen position                         Most browsers      Firefox
      let dualScreenLeft =
        window.screenLeft !== undefined ? window.screenLeft : window.screenX
      let dualScreenTop =
        window.screenTop !== undefined ? window.screenTop : window.screenY

      let width = window.innerWidth
        ? window.innerWidth
        : document.documentElement.clientWidth
          ? document.documentElement.clientWidth
          : window.screen.width
      let height = window.innerHeight
        ? window.innerHeight
        : document.documentElement.clientHeight
          ? document.documentElement.clientHeight
          : window.screen.height

      let left = width / 2 - w / 2 + dualScreenLeft
      let top = height / 2 - h / 2 + dualScreenTop
      let newWindow = window.open(
        url,
        title,
        'scrollbars=yes, width=' +
          w +
          ', height=' +
          h +
          ', top=' +
          top +
          ', left=' +
          left
      )

      // Puts focus on the newWindow
      if (window.focus) {
        newWindow.focus()
      }

      return newWindow
    }    
  }
}
</script>

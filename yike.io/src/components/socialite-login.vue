<template>
  <div class="oauth-btns d-flex align-items-center justify-content-between">
    <div class="text-20 btn mx-1 btn-icon btn-pink" @click="oauth('github')">
      <github-icon/>
    </div>
    <div class="text-20 btn mx-1 btn-icon btn-dark" @click="oauth('qq')">
      <qq-icon/>
    </div>
    <div class="text-20 btn mx-1 btn-icon btn-facebook" @click="oauth('weibo')">
      <img src="/icon/weibo.ico" />
    </div>
  </div>
</template>


<style scoped>
.btn.btn-icon{
    padding: 0 0 0.13em 0;
}
</style>
<script>
import QqIcon from '$icons/Qqchat';
import GithubIcon from '$icons/GithubCircle';
import WebBoxIcon from '$icons/WebBox';
import WebIcon from '$icons/Web';

export default {
  name: 'oauth-btn',
  components: { QqIcon, GithubIcon, WebBoxIcon, WebIcon },
  methods: {
    oauth (platform) {
      let iOSChrome =
        /Mobile/.test(navigator.userAgent) && /CriOS/.test(navigator.userAgent)
      let url = '/oauth/oauth-redirect/' + platform
      if (iOSChrome) {
        window.location.href = url
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

<style scoped>
</style>

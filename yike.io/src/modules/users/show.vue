<template>
  <div class="page-user-show" v-if="user.user_id > 0">
    <header
      class="page-header d-flex align-items-end bg-grey-blue py-2"
      style="background-image: url(/banners/shanghai.jpg)"
    >
      <div
        class="user-profile container position-relative w-100 text-white p-2 d-md-flex flex-row align-items-center"
      >
        <img :src="user.user_info ? user.user_info.user_avatar : ''" alt="User avatar" class="avatar avatar-120">
        <div class="ml-md-3">
          <h1 class="mt-2 mb-0">
            {{ user.user_info ? user.user_info.nick_name : '' }}
            <a
              class="ml-md-1 d-block d-md-inline text-gray-90 text-16"
            >@{{ user.user_info ? user.user_info.user_uuid : '' }}</a>
          </h1>
          <div class="my-1" v-if="!user.banned_at">{{ user.user_info.basic_extends.user_introduction }}</div>

          <div class="extends text-white d-none d-md-block d-lg-flex" v-if="!user.banned_at">
            <div class="mr-1" v-if="user.user_info && user.user_info.basic_extends.location">
              <map-marker-icon class="mr-1"></map-marker-icon>
              {{ user.user_info ? user.user_info.basic_extends.location : '' }}
            </div>
            <div class="mr-1" v-if="user.user_info && user.user_info.other_extends.company">
              <domain-icon class="mr-1"></domain-icon>
              {{ user.user_info.other_extends.company }}
            </div>
            <div class="mr-1" v-if="user.user_info && user.user_info.other_extends.home_url">
              <link-icon class="mr-1"></link-icon>
              <a class="text-white" target="_blank" :href="user.user_info.other_extends.home_url">{{ user.user_info.other_extends.home_url }}</a>
            </div>
            <div class="mr-1">
              <calendar-check-icon class="mr-1"></calendar-check-icon>
              加入于 {{ user.user_info ? user.user_info.time_formatting : '' }}
            </div>
          </div>
          <div class="pt-2">
            <user-social-btns :user="user.user_info" :spacing="2"></user-social-btns>
          </div>
        </div>
        <template v-if="currentUser && currentUser.user_id != user.user_id && !user.banned_at">
          <follow-btn :item="user" class="d-inline-block ml-md-auto"></follow-btn>
        </template>
      </div>
    </header>
    <div class="user-show-navbar bg-white">
      <div class="container">
        <div class="nav nav-tab-line text-center shadow-6 align-items-stretch">
          <div class="nav-item">
            <router-link :to="{ name: 'users.show' }" class="nav-link" exact>最新动态</router-link>
          </div>
          <div class="nav-item">
            <router-link :to="{ name: 'users.threads' }" class="nav-link" exact>
              讨论
              <span class="text-gray-70 pl-1">{{ user.user_info.basic_extends ? user.user_info.basic_extends.dynamics_count : 0 }}</span>
            </router-link>
          </div>
          <!--<div class="nav-item"><a href="#" class="nav-link">回复 234</a></div>-->
          <div class="nav-item">
            <router-link :to="{ name: 'users.following' }" class="nav-link" exact>
              关注
              <span class="text-gray-70 pl-1">{{ user.user_info.basic_extends ? user.user_info.basic_extends.follows_count : 0 }}</span>
            </router-link>
          </div>
          <div class="nav-item">
            <router-link :to="{ name: 'users.followers' }" class="nav-link" exact>
              粉丝
              <span class="text-gray-70 pl-1">{{ user.user_info.basic_extends ? user.user_info.basic_extends.fans_count : 0 }}</span>
            </router-link>
          </div>
          <div class="nav-item ml-auto" v-if="currentUser.user_info && currentUser.user_info.is_admin">
            <div class="btn-group">
              <button
                type="button"
                class="btn btn-link nav-link dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <admin-icon/>管理
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <button
                  class="dropdown-item"
                  type="button"
                  @click="toggleStatus('banned_at')"
                  :class="{'text-danger': !user.banned_at}"
                >
                  <account-off-icon class="mr-1"/>
                  {{ user.banned_at ? '取消冻结' : '冻结' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container pt-4" v-if="!user.banned_at">
      <div class="row">
        <div class="col-lg-9">
          <router-view></router-view>
        </div>
        <div class="col-lg-3">
          <hot-tags></hot-tags>
          <new-users class="mt-2"></new-users>
          <user-ranking class="mt-2"></user-ranking>
        </div>
      </div>
    </div>
    <div class="container mt-4" v-else>
      <user-locked/>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import { mapGetters } from 'vuex'
import HotTags from '$components/hot-tags'
import UserRanking from '$components/user-ranking'
import NewUsers from '$components/new-users'
import FollowBtn from '$components/buttons/follow-btn'
import MapMarkerIcon from '$icons/MapMarker'
import DomainIcon from '$icons/Domain'
import LinkIcon from '$icons/Link'
import AccountOffIcon from '$icons/AccountOff'
import CalendarCheckIcon from '$icons/CalendarCheck'
import AdminIcon from '$icons/AccountTie'
import UserSocialBtns from '$components/user-social-btns'
import UserLocked from '$components/user-locked'

export default {
  name: 'show',
  components: {
    UserLocked,
    FollowBtn,
    DomainIcon,
    CalendarCheckIcon,
    LinkIcon,
    AdminIcon,
    AccountOffIcon,
    MapMarkerIcon,
    HotTags,
    UserRanking,
    NewUsers,
    UserSocialBtns
  },
  data () {
    return {
      user: {},
      navFixed: false
    }
  },
  computed: {
    ...mapGetters(['currentUser'])
  },
  beforeRouteUpdate (to, from, next) {
    if (to.params.user_uuid !== from.params.user_uuid) {
      this.getUser(to.params.user_uuid)
    }

    next()
  },
  created () {
    this.getUser(this.$route.params.user_uuid)
    this.$nextTick(this.registerEventListener)
  },
  methods: {
    async getUser (user_uuid) {
      user_uuid = user_uuid || this.$route.params.user_uuid
      let res = await this.$http.get(`user/${user_uuid}`).catch(() => {
        this.$router.replace({ name: 'pages.not-found' })
      });
      this.user = res.data;
      console.log(this.user);
    },
    toggleStatus (timestamp) {
      this.user[timestamp] = this.user[timestamp]
        ? null
        : moment().format('YYYY-MM-DD HH:mm:ss')
      this.$http.patch(`user/${this.user.user_uuid}`, this.user).then(() => {
        this.$message.success('搞定！')
        this.getUser()
      })
    },
    registerEventListener () {
      let vm = this
      window.addEventListener('scroll', () => {
        if (vm.$route.name.substr(0, 5) === 'users') {
          let top =
            window.pageYOffset ||
            document.documentElement.scrollTop ||
            document.body.scrollTop ||
            0

          this.navFixed =
            top >= document.querySelector('.user-show-navbar').offsetTop
        }
      })
    }
  }
}
</script>

<style scoped lang="scss">
.page-user-show {
  .bg-image {
    overflow: hidden;

    img {
      width: 100%;
      height: auto;
    }
  }
  .page-header {
    min-height: 40vh;
    overflow-y: visible;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }
  .user-show-navbar {
    position: sticky;
    top: -1px;
    z-index: 999;
    .nav {
      position: relative;
      background: transparent;
      box-shadow: none;
    }
  }
  .avatar {
    border: 2px solid #fff;
  }
  .user-profile {
    z-index: 2;
    text-shadow: 1px 1px 10px #3d465e;
  }

  @media (max-width: 768px) {
    .user-profile {
      text-align: center;
    }
    .user-social-btns {
      justify-content: center !important;
    }
  }
}
</style>

<template>
  <div class="oauth-redirect text-center p-5">
    <div v-if="$route.name=='oauth.oauth_redirect'">Redirecting to {{ $route.params.platform }}...</div>
    <div v-else>Fetching user info...</div>
  </div>
</template>

<script>
export default {
  name: 'oauth',
  mounted () {
    let platform = this.$route.params.platform;
    if (this.$route.name === 'oauth.oauth_redirect') {
        this.$http.get('oauth/' + platform).then(res => {
            console.log(res.data);
            if(res.status){
                window.location = res.data.url;
            }else{
                // this.$message.error(res.msg);
            }
        });
    } else {
        this.$http.get('oauth/' + platform + '/callback?code=' + this.$route.query.code + '&state=' + this.$route.query.state).then(response => {
            
            this.$store.dispatch('setToken', response.data.access_token);
            this.$store.dispatch('loadUser');
            this.$message.success('欢迎回来~');

            if (window.opener) {
                window.opener.location.reload();
                window.close();
            } else {
                window.location.href = '/';
            }
        });
    }
  }
}
</script>

<style scoped>
</style>

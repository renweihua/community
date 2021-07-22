<template>
  <div class="page-node-show">
    <div class="row mt-3">
      <div class="col-md-12">
        <div class="box mb-3" v-for="node in nodes" :key="node.topic_id">
            <router-link :to="{name: 'nodes.node', params: {topic_id: node.topic_id}}" class="btn text-gray-40 btn-outline-light">
                <div class="box-heading">
                    {{node.topic_name}}
                </div>
                <div class="box-body">
                    <div class="text-gray-60 d-flex align-items-center">
                        <a class="p-1">
                            关注量：{{node.follow_count}}
                        </a>
                        <a class="p-1">
                            动态量：{{node.dynamic_count}}
                        </a>
                    </div>
                    <div class="ml-1 text-gray-60">
                        <small>{{node.topic_description}}</small>
                    </div>
                </div>
            </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
    .btn{
        display: unset !important;
        text-align: unset;
        font-weight: 400;
        color: #212529;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        -webkit-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
    }
</style>
<script>

export default {
  data () {
    return {
      nodes: []
    }
  },
  created () {
    this.getNodes();
  },
  methods: {
    getNodes () {
        this.$http.get('topics?all=true').then(({ data }) => {
            this.nodes = data;
        });
    }
  }
}
</script>

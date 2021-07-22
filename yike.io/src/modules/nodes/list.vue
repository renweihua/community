<template>
  <div class="page-node-show">
    <div class="row mt-3">
      <div class="col-md-12">
        <div class="box mb-3" v-for="node in nodes" :key="node.topic_id">
          <div class="box-heading">
            {{node.topic_name}}
          </div>
          <div class="box-body">
            <ul class="nav nav-pills">
              <li class="nav-item mr-2 mb-1" v-for="child in node.childs" :key="child.topic_id">
                <router-link :to="{name: 'nodes.node', params: {topic_id: child.topic_id}}" class="btn text-gray-40 btn-outline-light">
                  {{child.topic_name}}
                </router-link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

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

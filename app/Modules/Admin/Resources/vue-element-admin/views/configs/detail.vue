<template>
  <config-detail :is-edit="true" />
</template>

<script>
import ConfigDetail from './components/ConfigDetail';

export default {
  name: 'EditForm',
  components: { ConfigDetail },
    created() {

    },
}
</script>

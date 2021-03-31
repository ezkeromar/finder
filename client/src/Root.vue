<script>

import { mapGetters } from 'vuex'
import CcHeader from './components/root/header'
import CcFooter from './components/root/footer'
import CcAlerts from './components/general/alerts'
import { bus } from './plugins/eventbus'


export default {
  name: 'Finder',
  components: {
    CcHeader,
    CcFooter,
    CcAlerts,
  },
  data() {
    return {
      showParts:true,
    }
  },
  mounted() {
  },
  computed: {
    
  },
}
</script>

<template>
    <div>
      <link href="/static/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="/static/css/font-awesome.min.css">
      
      <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
      
      <link v-if="$route.name != 'home.index'" rel="stylesheet" href="/static/css/app.css">
      <link v-if="$route.name != 'home.index'" rel="stylesheet" href="/static/css/style.css">
      <link v-if="$route.name != 'home.index'" href="https://cdn.jsdelivr.net/npm/animate.css@3.5.1" rel="stylesheet" type="text/css">
      
      <link v-if="$route.name != 'home.index'" rel="stylesheet" href="/static/css/responsive.css">
      <cc-header></cc-header>
      <cc-alerts></cc-alerts>
      <router-view></router-view>
      <cc-footer></cc-footer>
    </div>
</template>

<style></style>

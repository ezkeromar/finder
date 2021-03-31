import { sync } from 'vuex-router-sync'
import Vue from 'vue'
import Root from './Root'


import store from './store'


import router from './router'


import httpPlugin from './plugins/http'


import eventbus from './plugins/eventbus'

import swal from 'sweetalert2'

import vSelect from 'vue-select'

import 'vue-event-calendar/dist/style.css'

import vueEventCalendar from 'vue-event-calendar'

//import VueSocial from 'vue-social'


var Paginate = require('vuejs-paginate')

var moment = require('moment')

require('./includes')

Vue.use(vueEventCalendar, {locale: 'en'})

Vue.use(httpPlugin, { store, router })

// you don't have to set them explicitly
/*Vue.use(VueSocial, {
  // this is the name for the callback fired
  // when the login popup is redirected to the callback URL
  callbackName: 'handleSocialAuthResponse', 
  // This defines the width/height of the share/login popup
  popup: {
    width: 800,
    height: 600
  }
})*/

Vue.use(eventbus)

Vue.component('v-select', vSelect)

Vue.component('paginate', Paginate)

Vue.component('moment', moment)

sync(store, router)

Vue.config.productionTip = false

new Vue({
  store,
  router,
  el: '#app',
  render: h => h(Root),
})

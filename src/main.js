import Vue from 'vue'
import VueRouter from 'vue-router'
import VueCarousel from 'vue-carousel'
import KeenUI from 'keen-ui'
import axios from 'axios'

import css from './assets/less/app.less'

import App from './App.vue'
import Toolbar from './components/Toolbar.vue'
import Start from './components/Start.vue'

Vue.use(VueRouter)
Vue.use(KeenUI)
Vue.use(VueCarousel)


axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.baseURL = 'api/';  //set the baseurl from api

Vue.prototype.$http = axios;
//Vue.http.options.emulateJSON = true; 


const router = new VueRouter({
  mode: 'history',
  base: __dirname,
  routes: [
    { path: '/', component: Start },
    { path: '/foo', component: Toolbar },
    { path: '/bar', component: Start }
  ]
})


new Vue({
  el: '#app',
  components: { App, Start, Toolbar, 'carousel': VueCarousel.Carousel, 'slide': VueCarousel.Slide },
  router,
  data: {
        showModal: true,
  }
})

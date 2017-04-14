import Vue from 'vue'
import Toolbar from './components/Toolbar.vue'
import Start from './components/Start.vue'
import axios from 'axios'
import css from './assets/less/app.less'
import KeenUI from 'keen-ui';

Vue.use(KeenUI);


axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.baseURL = 'api/';  //set the baseurl from api

Vue.prototype.$http = axios;
//Vue.http.options.emulateJSON = true; 

new Vue({
  el: '#app',
  components: {Start, Toolbar},
  data: {
        showModal: true,
        currentPage: 'Start',
        by : '',
        idNumber: '',
        spare: {},
        report: {}
  }
})

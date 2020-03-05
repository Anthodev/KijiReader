/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import Vue from 'vue'
import Vuex from 'vuex'
import vuetify from './plugins/vuetify'
import Vuelidate from "vuelidate"
import SlideUpDown from 'vue-slide-up-down'
import App from './App.vue'

import axios from 'axios'
axios.defaults.baseURL = window.location.origin
axios.defaults.headers.common['Content-Type'] = 'application/json'

import store from './store/store'
import router from './router'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faHome, faStar, faBookmark } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faHome)
library.add(faStar)
library.add(faBookmark)

// Vue.use(Vuetify)
Vue.use(Vuex)
Vue.use(Vuelidate)

Vue.component('slide-up-down', SlideUpDown)
Vue.component('font-awesome-icon', FontAwesomeIcon)

axios.interceptors.response.use(function (response) {
    return response
}, function (error) {
    if (error.response.status === 401) store.dispatch('logout')
    else return Promise.reject(error)
})

if (localStorage.getItem('userToken')) axios.defaults.headers.common["Authorization"] = `Bearer ${localStorage.getItem('userToken')}`
else delete axios.defaults.headers.common["Authorization"]

// any CSS you import will output into a single css file (app.css in this case)
// import '../css/app.css'

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

new Vue({
    el: '#app',
    vuetify,
    store,
    router,
    render: h => h(App)
})

/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import Vuelidate from "vuelidate";
import SlideUpDown from 'vue-slide-up-down'
import App from './App.vue'

import axios from 'axios'
axios.defaults.baseURL = window.location.origin
axios.defaults.headers.get['Accepts'] = 'application/json'

import store from './store/store'

import { routes } from './routes'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faHome, faStar, faBookmark } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faHome)
library.add(faStar)
library.add(faBookmark)

Vue.use(Vuex)
Vue.use(VueRouter)
Vue.use(Vuelidate)

Vue.component('slide-up-down', SlideUpDown)
Vue.component('font-awesome-icon', FontAwesomeIcon)

if (localStorage.getItem('userToken') != null || localStorage.getItem('userToken') != '') axios.defaults.headers.common["Authorization"] = `Bearer ${localStorage.getItem("userToken")}`
else delete axios.defaults.headers.common["Authorization"]

// any CSS you import will output into a single css file (app.css in this case)
// import '../css/app.css'

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

const router = new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) return savedPosition;
        if (to.hash) return { selector: to.hash };
        return { x: 0, y: 0 };
    }
})

new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
})

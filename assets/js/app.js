/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import App from './App.vue'

import { store } from './store/store.js'
import { routes } from './routes.js'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faHome, faStar, faBookmark } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faHome)
library.add(faStar)
library.add(faBookmark)

Vue.use(Vuex)
Vue.use(VueRouter)

Vue.component('font-awesome-icon', FontAwesomeIcon)

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css'

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

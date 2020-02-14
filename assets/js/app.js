/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import Vue from 'vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';
import App from './App.vue';
import { routes } from './routes.js';
import { library } from "@fortawesome/fontawesome-svg-core";
import { faIcons } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faIcons);

Vue.use(VueResource);
Vue.use(VueRouter);

Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.http.options.root = 'https://docker.app.localhost';
Vue.http.headers.common['Content-Type'] = 'application/json';

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

const router = new VueRouter({
    routes,
    mode: 'history'
});

new Vue({
    el: '#app',
    router,
    render: h => h(App)
});

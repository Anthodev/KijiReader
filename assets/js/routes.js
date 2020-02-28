import store from './store/store'

import Feed from './components/feedPage/FeedPane.vue'
import Authentication from './components/shared/authentication/Authentication.vue'

export const routes = [
    {
        path: '',
        name: 'home',
        components: {
            default: Feed
        },
        beforeEnter: (to, from, next) => {
            if (store.state.userToken) next()
            else next('/signin')
        }
    },
    { path: '/signin', name: 'authentication', component: Authentication },
    { path: '*', redirect: '/' },
]

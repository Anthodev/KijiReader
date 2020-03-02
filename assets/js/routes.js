import store from './store/store'

import Feed from './components/feedPage/FeedPane.vue'
import Authentication from './components/shared/authentication/Authentication.vue'
import Signin from './components/shared/authentication/Signin.vue'
import Signup from './components/shared/authentication/Signup.vue'

export const routes = [
    {
        path: '',
        name: 'home',
        components: {
            default: Feed
        },
        
        beforeEnter: (to, from, next) => {
            store.dispatch('getUsersCount').then(() => {
                if (store.getters.isAuthenticated) next()
                else if (store.getters.usersCount > 0) next('signin')
                else next('signup')
            })
        },

        created() {
            store.dispatch('fetchUser')
        },
    },

    { path: '/signin', name: 'signin', components: {
        default: Authentication,
        'signinComponent': Signin
        },
    
        beforeEnter: (to, from, next) => {
            store.dispatch('getUsersCount').then(() => {
                if (store.getters.isAuthenticated) next('home')
                else if (store.state.usersCount > 0) next()
                else next('signup')
            })
        }

    },

    { path: '/signup', name: 'signup', components: {
        default: Authentication,
        'signupComponent': Signup
        },

        beforeEnter: (to, from, next) => {
            if (store.getters.isAuthenticated) next('home')
            else next()
        }
    },

    { path: '*', redirect: '/' },
]

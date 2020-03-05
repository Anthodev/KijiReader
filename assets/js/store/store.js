import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from '../router'
import { news } from './modules/news'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        drawer: true,
        usersCount: 0,
        userToken: null,
        user: null,
        serverError: '',
        newsfeed: []
    },

    modules: {
        news : { namespaced: true }
    },

    mutations: {
        setDrawerStatus (state) {
            state.drawer = !state.drawer
        },

        setServerError (state, payload) {
            state.serverError = payload.error
        },

        setUsersCount (state, payload) {
            state.usersCount = payload.usersCount
        },

        storeToken (state, payload) {
            state.userToken = payload.userToken
        },
        
        storeUser (state, payload) {
            state.user = payload.user
        },

        clearAuthData (state) {
            state.userToken = null
            state.user = null
        },

        storeNewsfeed (state, payload) {
            state.newsfeed = payload.newsfeed
        }
    },

    actions: {
        setDrawerStatus ({ commit }) {
            commit('setDrawerStatus')
        },

        clearServerError ({ commit }) {
            commit('setServerError', {
                error: ''
            })
        },

        getUsersCount ({ commit }) {
            return axios.get('/user/countUsers', {})
                .then(res => {
                    console.log(res)
                    
                    commit('setUsersCount', {
                        usersCount: res.data
                    })
                })
                .catch(error => console.log(error))
        },

        signup ({ commit, dispatch }, authData) {
            return axios.post('/user/new', {
                username: authData.username,
                email: authData.email,
                password: authData.password,
                roleUser: authData.role
            })
            .then(res => {
                console.log(res)
                dispatch('login', authData)
            })
            .catch(error => console.log(error))
        },

        login ({ commit, getters, dispatch }, authData) {
            return axios.post('/auth/login_check', {
                username: authData.username,
                password: authData.password
            })
            .then(res => {
                console.log(res)

                commit('storeToken', {
                    userToken: res.data.token
                })

                localStorage.setItem('userToken', res.data.token)
                axios.defaults.headers.common['Authorization'] = `Bearer ${getters.userToken}`
                
                router.go({ name: 'home' })
            })
            .catch(error => console.log(error))
        },

        logout ({ commit }) {
            localStorage.removeItem('userToken')
            delete axios.defaults.headers.common['Authorization']

            commit('clearAuthData')
            
            router.go({ name: 'signin' })
        },

        fetchUser ({ commit, getters }) {
            if (!getters.userToken) return

            return axios.get('/user/profile')
            .then(res => {
                console.log(res)
                commit("storeUser", {
                    user: res.data
                })
            })
            .catch(error => console.log(error))
        },

        fetchNewsfeed ({ commit, getters }) {
            if (!getters.userToken) return

            return axios.get('/feed/newsfeed')
            .then(res => {
                console.log(res)
                commit('storeNewsfeed', {
                    newsfeed: res.data
                })

                console.log(getters.newsfeed)
            })
            .catch(error => console.log(error))
        },

        addFeed ({ commit, getters, dispatch }, formData) {
            if (!getters.userToken) return

            return axios.post('/feed/add', {
                feedUrl: formData.feedUrl
            })
            .then(res => {
                console.log(res)

                dispatch('fetchNewsfeed')
            })
            .catch(error => {
                console.log(error)

                commit('setServerError', {
                    error: error.response.status
                })
            })
        }
    },

    getters: {
        drawer (state) {
            return state.drawer
        },

        isAuthenticated () {
            return localStorage.getItem('userToken') !== null
        },

        usersCount (state) {
            return state.usersCount
        },

        user (state) {
            return state.user
        },

        userToken () {
            return localStorage.getItem('userToken')
        },

        newsfeed (state) {
            return state.newsfeed
        },

        serverError (state) {
            return state.serverError
        }
    }
});

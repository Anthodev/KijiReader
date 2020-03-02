import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from 'vue-router'
import { news } from './modules/news'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        usersCount: 0,
        userToken: null,
        user: {
            id: '',
            username: '',
            email: '',
            role: ''
        }
    },

    modules: {
        news : { namespaced: true }
    },

    mutations: {
        setUsersCount (state, payload) {
            state.usersCount = payload.usersCount
        },

        storeToken(state, payload) {
            state.userToken = payload.userToken
        },
        
        storeUser(state, payload) {
            state.user = payload.user
        }
    },

    actions: {
        getUsersCount ({ commit, state }) {
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
                
                router.push({ name: 'home' })
            })
            .catch(error => console.log(error))
        },

        fetchUser ({ commit, state }) {
            if (!state.userToken) return

            return axios.get('/user/profile', {})
            .then(res => {
                console.log(res)
                commit("storeUser", {
                    user: {
                        id: res.data.id,
                        username: res.data.username,
                        email: res.data.email,
                        role: res.data.role
                    }
                });
            })
            .catch(error => console.log(error))
        }
    },

    getters: {
        isAuthenticated (state) {
            return localStorage.getItem('userToken') !== null
        },

        usersCount (state) {
            return state.usersCount
        },

        userToken () {
            return localStorage.getItem('userToken')
        }
    }
});

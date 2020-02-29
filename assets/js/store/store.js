import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import { news } from './modules/news'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        usersCount: 0,
        userToken: null
    },

    modules: {
        news : { namespaced: true }
    },

    actions: {
        getUsersCount ({ commit, state }) {
            axios.get('/user/get/countUsers', {})
                .then(res => {
                    console.log(res)

                    const data = res.data
                    state.usersCount = data
                })
                .catch(error => console.log(error))
        },

        signup ({ commit, dispatch }, authData) {
            axios.post('/new', {
                
            })
        }
    },

    getters: {
        isAuthenticated (state) {
            return state.userToken !== null
        }
    }
});

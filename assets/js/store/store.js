import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import { news } from './modules/news'

Vue.use(Vuex)

export default store =  new Vuex.Store({
    modules: {
        news : { namespaced: true }
    },

    actions: {
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

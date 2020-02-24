const state = {
    newsList: []
}

const getters = {
    getNews: state => {
        return state.newsList
    }
}

const mutations = {
    setNews: (state, payload) => {
        state.newsList.push(payload)
    }
}

const actions = {
    setNews: ({ commit }, payload) => {
        commit('setNews', payload)
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}

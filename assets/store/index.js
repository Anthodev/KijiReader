export const state = () => ({
    drawer: true,
    usersCount: 0,
    userToken: null,
    user: null,
    serverError: '',
    newsfeed: [],
    alert: null
})

export const mutations = {
    SET_DRAWER_STATUS(state) {
        state.drawer = !state.drawer
    },

    SET_SERVER_ERROR(state, payload) {
        state.serverError = payload.error
    },

    SET_USERCOUNT(state, payload) {
        state.usersCount = payload.usersCount
    },

    SET_USER_TOKEN(state, payload) {
        state.userToken = payload.userToken
    },

    SET_USER(state, payload) {
        state.user = payload.user
    },

    DELETE_AUTH_DATA(state) {
        state.userToken = null
        state.user = null
    },

    SET_NEWSFEED(state, payload) {
        state.newsfeed = payload.newsfeed
    },

    SET_MORE_NEWSFEED(state, payload) {
        Array.prototype.push.apply(state.newsfeed, payload.newsfeed)
    },

    SET_ALERT(state, payload) {
        state.alert = payload.alert
    }
}

export const actions = {
    SET_DRAWER_STATUS({ commit }) {
        commit('SET_DRAWER_STATUS')
    },

    DELETE_SERVER_ERROR({ commit }) {
        commit('SET_SERVER_ERROR', {
            error: ''
        })
    },

    async GET_USERCOUNT({ commit }) {
        const data =  await this.$axios.$get('/user/countUsers', {})
        
        commit('SET_USERCOUNT', {
            usersCount: data
        })
    },

    async SIGNUP({ commit, dispatch }, authData) {
        return await this.$axios.$post('/user/new', {
            username: authData.username,
            email: authData.email,
            password: authData.password,
            roleUser: authData.role
        })
            .then(res => {
                console.log(res)
                dispatch('LOGIN', authData)
            })
            .catch(error => console.log(error))
    },

    async LOGIN({ commit, getters }, authData) {
        const data = await this.$axios.$post('/auth/login_check', {
            username: authData.username,
            password: authData.password
        })

        commit('SET_USER_TOKEN', data.token)

        localStorage.setItem('userToken', data.token)
        this.$axios.setToken(data.token, 'Bearer')

        this.$router.go('/')
    },

    LOGOUT({ commit }) {
        localStorage.removeItem('userToken')
        delete this.$axios.setToken(false)

        commit('DELETE_AUTH_DATA')

        this.$router.go('/signin')
    },

    async FETCH_USER({ commit, getters }) {
        if (!getters.userToken) return

        return await this.$axios.$get('/user/profile')
            .then(res => {
                console.log(res)
                commit("SET_USER", {
                    user: res
                })
            })
            .catch(error => console.log(error))
    },

    async FETCH_NEWSFEED({ commit, getters }, offset = 0) {
        if (!getters.userToken) return

        let data = await this.$axios.$get('/feed/newsfeed/' + offset)
            .then((res) => {
                // console.log(res);

                // let sortedNewsfeed = res.data

                // sortedNewsfeed.sort(function (a, b) {
                //     return b.story.date.timestamp - a.story.date.timestamp
                // })

                if (offset === 0) {
                    commit("SET_NEWSFEED", {
                        newsfeed: res
                        // newsfeed: res.data
                    });
                } else {
                    commit("SET_MORE_NEWSFEED", {
                        newsfeed: res
                        // newsfeed: res.data
                    });
                }
            })
            .catch(error => console.log(error))
        
        return data
    },

    async ADD_FEED({ commit, getters, dispatch }, formData) {
        if (!getters.userToken) return

        return await this.$axios.$post('/feed/add', {
            feedUrl: formData.feedUrl
        })
            .then(res => {
                console.log(res)

                dispatch('FETCH_NEWSFEED')
            })
            .catch(error => {
                console.log(error)

                commit('SET_SERVER_ERROR', {
                    error: error.response.status
                })
            }
        )
    },

    async SET_MARK_AS_READ({ commit, getters }, id) {
        if (!getters.userToken) return;

        return await this.$axios.$post("/story/markread/" + id)
            .then(res => {
                console.log(res);
            })
            .catch(error => {
                console.log(error);

                commit("SET_SERVER_ERROR", {
                    error: error.response.status
                })
            })
    },

    SET_ALERT({ commit }, alert) {
        commit("SET_ALERT", {
            alert: alert
        })
    }
}

export const getters = {
    drawer(state) {
        return state.drawer
    },

    isAuthenticated(state) {
        return state.userToken != null
    },

    usersCount(state) {
        return state.usersCount
    },

    user(state) {
        return state.user
    },

    userToken(state) {
        return state.userToken
    },

    newsfeed(state) {
        return state.newsfeed
    },

    serverError(state) {
        return state.serverError
    }
}
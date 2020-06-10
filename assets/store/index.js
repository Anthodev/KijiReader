export const state = () => ({
  drawer: true,
  usersCount: 0,
  userToken: null,
  user: null,
  serverError: '',
  newsfeed: [],
  feeds: [],
  unreadAllCount: 0,
  unreadFeedList: [],
  loadingState: {
    loading: false,
    type: ''
  }
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
    state.newsfeed = []
    Array.prototype.push.apply(state.newsfeed, payload.newsfeed)
  },

  SET_MORE_NEWSFEED(state, payload) {
    Array.prototype.push.apply(state.newsfeed, payload.newsfeed)
  },

  SET_LOADING_STATE(state, payload) {
    state.loadingState = payload.loadingState
  },

  SET_UNREAD_COUNT(state, payload) {
    state.unreadAllCount = payload.unreadCount
    state.unreadFeedList = payload.unreadList
  },

  SET_FEEDS(state, payload) {
    state.feeds = payload.feeds
  }
}

export const actions = {
  SET_DRAWER_STATUS({
    commit
  }) {
    commit('SET_DRAWER_STATUS')
  },

  DELETE_SERVER_ERROR({
    commit
  }) {
    commit('SET_SERVER_ERROR', {
      error: ''
    })
  },

  async GET_USERCOUNT({
    commit
  }) {
    const data = await this.$axios.$get('/api/user/countUsers', {})

    commit('SET_USERCOUNT', {
      usersCount: data
    })
  },

  async SIGNUP({
    dispatch
  }, authData) {
    return await this.$axios.$post('/api/user/new', {
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

  async LOGIN({
    commit,
  }, authData) {
    const data = await this.$axios.$post('/api/auth/login_check', {
      username: authData.username,
      password: authData.password
    })

    commit('SET_USER_TOKEN', data.token)

    localStorage.setItem('userToken', data.token)
    this.$axios.setToken(data.token, 'Bearer')

    this.$router.go('/')
  },

  LOGOUT({
    commit
  }) {
    localStorage.removeItem('userToken')
    delete this.$axios.setToken(false)

    commit('DELETE_AUTH_DATA')

    this.$router.go('/signin')
  },

  async FETCH_USER({
    commit,
    getters
  }) {
    if (!getters.userToken) return

    return await this.$axios.$get('/api/user/profile')
      .then(res => {
        console.log(res)
        commit("SET_USER", {
          user: res
        })
      })
      .catch(error => console.log(error))
  },

  async FETCH_NEWSFEED({
    commit,
    getters,
    dispatch
  }, payload = null) {
    if (!getters.userToken) return

    let offset = 0
    let id = 0

    if (payload != null && payload.hasOwnProperty('offset')) offset = payload.offset
    if (payload != null && payload.hasOwnProperty('id')) id = payload.id

    let data = await this.$axios.$get('/api/feed/newsfeed/' + offset, {
      feedId: id
    })
      .then((res) => {
        if (offset === 0) {
          commit("SET_NEWSFEED", {
            newsfeed: res
          })

          dispatch("FETCH_UNREAD_COUNT");

          return res
        } else {
          commit("SET_MORE_NEWSFEED", {
            newsfeed: res
          })

          return res
        }
      })
      .catch(error => console.log(error))

    return data
  },

  async ADD_FEED({
    commit,
    getters,
    dispatch
  }, formData) {
    if (!getters.userToken) return

    return await this.$axios.$post('/api/feed/add', {
        feedUrl: formData.feedUrl
      })
      .then(res => {
        console.log(res)

        dispatch('DELETE_SERVER_ERROR')
        dispatch('FETCH_FEEDS')
        dispatch('FETCH_NEWSFEED')
      })
      .catch(error => {
        console.log(error)

        commit('SET_SERVER_ERROR', {
          error: error.response.status
        })
      })
  },

  async SET_MARK_AS_READ({
    commit,
    getters,
    dispatch
  }, id) {
    if (!getters.userToken) return;

    return await this.$axios.$post("/api/story/markread/" + id)
      .then(res => {
        console.log(res)
        dispatch('DELETE_SERVER_ERROR')
        dispatch('FETCH_UNREAD_COUNT')
      })
      .catch(error => {
        console.log(error);

        commit("SET_SERVER_ERROR", {
          error: error.response.status
        })
      })
  },

  SET_LOADING_STATE({
    commit
  }, loadingState) {
    commit("SET_LOADING_STATE", {
      loadingState: loadingState
    })
  },

  async FETCH_UNREAD_COUNT({
    commit,
    dispatch
  }) {
    if (!getters.userToken) return;

    return await this.$axios.$get('/api/feed/get/unreadcount')
      .then(res => {
        dispatch('DELETE_SERVER_ERROR')

        let unreadCount = 0

        res.forEach(el => {
          unreadCount += parseInt(el.unreadCount)
        });

        commit('SET_UNREAD_COUNT', {
          unreadCount: unreadCount,
          unreadList: res
        })
      })
      .catch(error => {
        console.log(error)
      })
  },

  async FETCH_FEEDS({
    commit,
    dispatch
  }) {
    if (!getters.userToken) return;

    return await this.$axios.$get('/api/feed/get')
      .then(res => {
        dispatch('DELETE_SERVER_ERROR')

        if (res.length > 0) {
          commit('SET_FEEDS', {
            feeds: res
          })
        }
      })
      .catch(error => {
        console.log(error)
      })
  },

  async DELETE_FEED({
    dispatch
  }, id) {
    if (!getters.userToken) return

    return await this.$axios.$post('/api/feed/delete/' + id)
      .then(res => {
        dispatch('FETCH_NEWSFEED')
        dispatch('FETCH_FEEDS')
      })
      .catch(error => {
        console.log(error)
      })
  },

  async SET_MARK_FEED_AS_READ({
    dispatch
  }, id) {
    if (!getters.userToken) return

    return await this.$axios.$post('/api/feed/mark_read/' + id)
      .then(res => {
        dispatch("FETCH_NEWSFEED")
      })
      .catch(error => {
        console.log(error)
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
  },

  loadingState(state) {
    return state.loadingState
  },

  unreadAllCount(state) {
    return state.unreadAllCount
  },

  unreadFeedList(state) {
    return state.unreadFeedList
  },

  feeds(state) {
    return state.feeds
  }
}

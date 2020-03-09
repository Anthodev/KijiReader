export default function ({ store, route, redirect }) {
    if (localStorage.getItem('userToken')) store.commit('SET_USER_TOKEN', {
        userToken: localStorage.getItem('userToken')
    })

    console.log(store.getters.isAuthenticated)
    if (route.fullPath === '/signin' || route.fullPath === '/signup') return

    store.dispatch('GET_USERCOUNT')

    if (!store.getters.isAuthenticated) {
        if (store.getters.usersCount > 0) return redirect('/signin')
        else return redirect('/signup')
    }
}

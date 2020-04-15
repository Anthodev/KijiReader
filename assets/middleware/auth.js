export default function ({ store, route, redirect }) {
    if (store.getters.userToken != true && localStorage.getItem('userToken') != null) {
        store.commit('SET_USER_TOKEN', {
            userToken: localStorage.getItem('userToken')
        })
    }
    
    if(!store.getters.isAuthenticated) {
        if (route.fullPath === '/signin' || route.fullPath === '/signup') return

        store.dispatch('GET_USERCOUNT').then(res => {
            if (store.getters.usersCount > 0) return redirect('/signin')
            else return redirect('/signup')
        })
    } else {
        if (route.fullPath === '/signin' || route.fullPath === '/signup') return redirect('/')
    }
}

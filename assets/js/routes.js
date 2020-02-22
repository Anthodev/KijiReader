import Feed from './components/feedPage/FeedPane.vue'

export const routes = [
    { path: '', name: 'home', components: {
        default: Feed
    } },
    { path: '*', redirect: '/' },
]

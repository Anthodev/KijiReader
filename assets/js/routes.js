import Feed from './components/feedPage/FeedPane.vue';
import SideS from './components/shared/Sidebar.vue';

export const routes = [
    { path: '*', component: Feed },
    { path: '/feed', component: SideS },
];

console.log('router enabled');

import { createRouter, createWebHistory } from 'vue-router'
import RegisterFormView from '../views/RegisterFormView.vue'
import SignupFormView from '../views/SignupFormView.vue'
import Badges from '../views/badges/BadgesView.vue'
import HomeView from '../views/HomeView.vue'
import MapView from '../views/MapView.vue'
import SavedPathsView from '@/views/SavedPathsView.vue'
import BadgesDetails from '../views/badges/BadgesDetailsView.vue'

const routes = [
    {
        path: '/',
        name: 'SignupFormView',
        component: SignupFormView
    },
    {
        path: '/RegisterFormView',
        name: 'RegisterFormView',
        component: RegisterFormView
    },
    {
        path: '/badges',
        name: 'Badges',
        component: Badges
    },
    {
        path: '/homeView',
        name: 'HomeView',
        component: HomeView
    },
    {
        path: '/mapView',
        name: 'MapView',
        component: MapView
    },
    {
        path: '/savedPathsViews',
        name: SavedPathsView,
        component: SavedPathsView
    },
    {
        path: '/badges/:id',
        name: 'BadgesDetails',
        component: BadgesDetails
    }
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router
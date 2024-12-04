// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

const SignupFormView = () => import('@/views/SignupFormView.vue')
const RegisterFormView = () => import('@/views/RegisterFormView.vue')
const Badges = () => import('@/views/badges/BadgesView.vue')
const MapView = () => import('@/views/MapView.vue')
const SavedPathsView = () => import('@/views/SavedPathsView.vue')
const BadgesDetails = () => import('@/views/badges/BadgesDetailsView.vue')


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
        path: '/mapView',
        name: 'MapView',
        component: MapView
    },
    {
        path: '/savedPathsViews',
        name: 'SavedPathsView',
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
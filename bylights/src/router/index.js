import { createRouter, createWebHistory } from 'vue-router'
import RegisterFormView from '../views/RegisterFormView.vue'
import SignupFormView from '../views/SignupFormView.vue'
import Badges from '../views/badges/BadgesView.vue'

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
    }
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router
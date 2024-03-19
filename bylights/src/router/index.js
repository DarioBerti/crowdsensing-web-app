import { createRouter, createWebHistory } from 'vue-router'
import RegisterFormView from '../views/RegisterFormView.vue'
import SignupFormView from '../views/SignupFormView.vue'

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
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router
import { createRouter, createWebHistory } from "vue-router";

import Page404 from "../components/pages/404.vue";

import adminRoutes from './adminRoutes'

const routes = [
    // Admins Panel
    ...adminRoutes,

    // Not Found
    {
        path: '/:pathMatch(.*)*',
        component: Page404,
        name: 'pageNotFound',
        meta: {
            pageTitle: 'یافت نشد!',
            middleware: 'WEB'
        }
    }
]

const router = createRouter({
    // history: createWebHistory(process.env.BASE_URL),
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from) => {
    if (typeof to?.meta?.middleware?.toUpperCase() != 'undefined' && to?.meta?.middleware?.toUpperCase() === 'AUTH') {
        const crmState = JSON.parse(localStorage?.crmState)

        if (typeof crmState?.userData === undefined || typeof crmState?.userData?.token !== 'string' || crmState?.userData?.token?.length < 30) {
            router.push('/login')
        }
    }
})

export default router

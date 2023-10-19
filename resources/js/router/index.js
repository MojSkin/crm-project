import { createRouter, createWebHistory } from "vue-router";

import WebHome from "../components/pages/WebHome/WebHomeComponent.vue";
import Preview from "../components/pages/WebHome/FormPreview.vue";
import Login from "../components/pages/Auth/LoginComponent.vue";
import ContactUs from "../components/pages/WebContactUs/WebContactUsComponent.vue";
import PreReg from "../components/pages/WebPreReg/PreRegComponent.vue";
import PreRegTracking from "../components/pages/WebPreReg/PreRegTrackingComponent.vue";
import PaymentCallback from "../components/pages/WebPaymentCallback/PaymentCallbackComponent.vue";
import UserProfile from "../components/pages/Profile/UserProfileComponent.vue";
import Page404 from "../components/pages/404.vue";

import adminRoutes from './adminRoutes'

const routes = [
    {
        path: '/login',
        component: Login,
        name: 'login',
        meta: {
            pageTitle: 'Login',
            breadcrumbs: [],
            middleware: 'WEB'
        }
    },
    {
        path: '/preview/:form_id',
        component: Preview,
        name: 'preview',
        props: true,
        meta: {
            pageTitle: 'پیش‌نمایش فرم',
            breadcrumbs: [],
            middleware: 'WEB'
        }
    },
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

import { createRouter, createWebHistory } from 'vue-router';
import CourierLogin from "./Components/Auth/CourierLogin.vue";
import CourierDashboard from "./Components/Couriers/CourierDashboard.vue";

const routes = [
        {
            path: '/admin/reports',
            component: () => import('./Components/Reports/ReportComponent.vue'),
            meta: {requiresAuth: true, adminOnly: true},
        },
        {
            path: '/couriers/schedule',
            name: 'CourierSchedule_page_url',
            component: () => import('./Components/Reports/CourierSchedule.vue'),
            meta: {requiresAuth: true, adminOnly: true},
        },
        {
            path: '/general',
            name: 'general_page_url',
            component: () => import('./Components/Reports/GeneralStats.vue'),
            meta: { requiresAuth: true, adminOnly: true },
        },
        {
            path: '/couriers',
            name: 'couriers_page_url',
            component: () => import('./Components/Reports/CourierStats.vue'),
            meta: { requiresAuth: true, adminOnly: true },
        },
        {
            path: '/routes',
            name: 'routes_page_url',
            component: () => import('./Components/Reports/RouteStats.vue'),
            meta: { requiresAuth: true, adminOnly: true },
        },
        {
            path: '/financial',
            name: 'financial_page_url',
            component: () => import('./Components/Reports/FinancialStats.vue'),
            meta: { requiresAuth: true, adminOnly: true },
        },
        {
            path: '/feedback',
            name: 'feedback_page_url',
            component: () => import('./Components/Reports/ReviewsStats.vue'),
            meta: { requiresAuth: true, adminOnly: true },
        },
        {
            path: '/orders',
            name: 'orders_page_url',
            component: () => import('./Components/Reports/OrdersReports.vue'),
            meta: { requiresAuth: true, adminOnly: true },
        },
        {
            path: '/login',
            component: () => import('./Components/Auth/Login.vue'),
        },
        {
            path: "/courier/login",
            name: "CourierLogin",
            component: CourierLogin,
        },
        {
            path: "/courier/dashboard",
            name: "CourierDashboard",
            component: CourierDashboard,
            meta: { requiresAuth: true },
        },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('authToken');
    const userRole = localStorage.getItem('userRole');

    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ path: '/login' });
    } else if (to.meta.adminOnly && userRole !== 'admin') {
        next({ path: '/unauthorized' });
    } else {
        next();
    }
});

export default router;

import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/AppHome.vue';
import Dashboard from '../views/DashBoard.vue';
import Login from '../views/UserLogin.vue';
import Register from '../views/UserRegister.vue';
import Logout from '../views/UserLogout.vue';
import store from '../store';

const routes = [
    { path: '/', component: Home },
    { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/login', component: Login},
    { path: '/register', component: Register },
    { path: '/logout', component: Logout, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !store.state.token) {
        next('/login');
    } else {
        next();
    }
});

export default router;
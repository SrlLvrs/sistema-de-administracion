import { getAuth } from 'firebase/auth';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "Inicio",
            component: () => import("../views/Inicio.vue"),
            meta: { requiresAuth: true },
        },
        { path: "/clientes", component: () => import("../views/Clientes.vue"), meta: { requiresAuth: true } },
        { path: "/clientes/deudores", component: () => import("../views/Clientes-Deudores.vue"), meta: { requiresAuth: true } },
        { path: "/productos", component: () => import("../views/Productos.vue"), meta: { requiresAuth: true } },
        { path: "/pedidos", component: () => import("../views/Pedidos.vue"), meta: { requiresAuth: true } },
        { path: "/pedidos/automaticos", component: () => import("../views/Pedidos-auto.vue"), meta: { requiresAuth: true } },
        { path: "/pedidos/hoy", component: () => import("../views/Pedidos-hoy.vue"), meta: { requiresAuth: true } },
        { path: "/produccion", component: () => import("../views/Produccion.vue"), meta: { requiresAuth: true } },
        { path: "/admin", component: () => import("../views/Admin.vue"), meta: { requiresAuth: true } },
        { path: "/sectores", component: () => import("../views/Sectores.vue"), meta: { requiresAuth: true } },
        {
            path: "/login",
            name: "LogIn",
            component: () => import("../views/LogIn.vue"),
            meta: { requiresAuth: false },
        },
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (getAuth().currentUser) {
            next();
        } else {
            alert('Debes iniciar sesión para ver esta página');
            next('/login');
        }
        next();
    } else {
        next();
    }
});

export default router;
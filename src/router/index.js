import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", component: () => import("../views/Inicio.vue"), meta: { requiresAuth: true }},
        { path: "/clientes", component: () => import("../views/Clientes.vue")},
        { path: "/clientes/deudores", component: () => import("../views/Clientes-Deudores.vue")},
        { path: "/productos", component: () => import("../views/Productos.vue")},
        { path: "/pedidos", component: () => import("../views/Pedidos.vue")},
        { path: "/pedidos/automaticos", component: () => import("../views/Pedidos-auto.vue")},
        { path: "/pedidos/hoy", component: () => import("../views/Pedidos-hoy.vue")},
        { path: "/produccion", component: () => import("../views/Produccion.vue")},
        { path: "/admin", component: () => import("../views/Admin.vue")},
        { path: "/sectores", component: () => import("../views/Sectores.vue")},
        { path: "/login", component: () => import("../views/LogIn.vue")},
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        next('/login');
    } else {
        next();
    }
});

export default router;
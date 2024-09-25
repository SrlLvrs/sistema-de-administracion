<template>
    <div v-if="sessionData" class="navbar bg-base-100">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <!-- Este es el menú de pantallas pequeñas -->
                    <li><router-link to='/' @click.native="close">Inicio</router-link></li>
                    <li v-if="rol == 'Repartidor'"><router-link to='/repartidor' @click.native="close">Repartos de hoy</router-link></li>
                    <li><router-link to='/clientes' @click.native="close">Clientes</router-link></li>
                    <li><router-link to='/productos' @click.native="close">Productos</router-link></li>
                    <li><router-link to='/sectores' @click.native="close">Sectores de Reparto</router-link></li>
                    <li><router-link to='/pedidos' @click.native="close">Pedidos</router-link></li>
                    <li><router-link to='/produccion' @click.native="close">Producción</router-link></li>
                    <li><router-link to='/admin' @click.native="close">Administrar usuarios</router-link></li>
                </ul>
            </div>
            <a class="btn btn-ghost text-xl"><router-link to='/'>Nuestro Campo</router-link></a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <!-- Este es el menú de pantallas grandes -->
                <li v-if="rol != 'Repartidor'"><router-link to='/'>Inicio</router-link></li>
                <li v-if="rol == 'Repartidor'"><router-link to='/repartidor' @click.native="close">Repartos de hoy</router-link></li>
                <li><router-link to='/clientes'>Clientes</router-link></li>
                <li><router-link to='/productos'>Productos</router-link></li>
                <li><router-link to='/sectores'>Sectores de Reparto</router-link></li>
                <li><router-link to='/pedidos'>Pedidos</router-link></li>
                <li v-if="rol != 'Repartidor'"><router-link to='/produccion'>Producción</router-link></li>
                <li v-if="rol != 'Repartidor'"><router-link to='/admin'>Administrar usuarios</router-link></li>
                <li>
                    <button @click="checkUserSession()" class="btn btn-outline btn-success">Current User</button>
                </li>
                <li>
                    <button @click="logout()" class="btn btn-outline btn-error">Sign Out</button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            sessionData: null,
        };
    },

    computed: {
        rol() {
            return this.sessionData && this.sessionData.rol;
        },
    },

    methods: {
        close() {
            document.activeElement.blur();
        },
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            console.log(sessionData);
            return sessionData ? sessionData : null;
        },
        logout() {
            // Eliminar la información de sesión de localStorage
            localStorage.removeItem('authUser');
            console.log('Sesión cerrada');
            this.$router.push({ name: 'LogIn' });
        },
    },

    created() {
        this.sessionData = this.checkUserSession();

        if (this.sessionData) {
            console.log('Sesión iniciada');
        } else {
            console.log('No hay sesión iniciada');
        }
    },

    watch: {
        $route(to, from) {
            this.sessionData = this.checkUserSession();
        },
    },
}

</script>
<template>
    <div v-if="sessionData" class="navbar bg-base-100">
        <div class="navbar-start">
            <!-- Este es el menú de pantallas pequeñas -->
            <div class="dropdown">
                <!-- SVG Burguer -->
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden" @click="toggleDropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>  
                <!-- Dropdown -->
                <ul v-show="isDropdownOpen" tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <!-- Inicio -->
                    <li v-if="rol != 'Repartidor'" @click="closeDropdown">
                        <router-link to='/'>Inicio</router-link>
                    </li>    
                    <!-- Últimos cambios (Repartidor) -->
                    <li v-if="rol == 'Repartidor'" @click="closeDropdown">
                        <router-link to='/iniciorepartidor'>Últimos cambios</router-link>
                    </li>
                    <!-- Ordenar Pedidos (Repartidor) -->
                    <li v-if="rol == 'Repartidor'" @click="closeDropdown">
                        <router-link to='/pedidos/ordenar'>Ordenar
                            Pedidos</router-link>
                    </li>
                    <!-- Repartos de hoy (Repartidor) -->
                    <li v-if="rol == 'Repartidor'" @click="closeDropdown">
                        <router-link to='/repartidor'>Repartos de
                            hoy</router-link>
                    </li>
                    <!-- Resumen diario -->
                    <li @click="closeDropdown">
                        <router-link to='/resumen'>Resumen diario</router-link>
                    </li>
                    <!-- Clientes -->
                    <li v-if="rol != 'Repartidor'">
                        <details>
                            <summary>Clientes</summary>
                            <ul class="bg-base-100 rounded-t-none p-2">
                                <li @click="closeDropdown">
                                    <router-link to="/clientes">Todos los Clientes</router-link>
                                </li>
                                <li @click="closeDropdown">
                                    <router-link to="/clientes/deudores">Clientes
                                        deudores</router-link></li>
                            </ul>
                        </details>
                    </li>
                    <!-- Productos -->
                    <li v-if="rol != 'Repartidor'" @click="closeDropdown">
                        <router-link to='/productos'>Productos</router-link></li>
                    <!-- Sectores de Reparto -->
                    <li v-if="rol != 'Repartidor'" @click="closeDropdown">
                        <router-link to='/sectores'>Sectores de Reparto</router-link></li>
                    <!-- Pedidos -->
                    <li v-if="rol != 'Repartidor'">
                        <details>
                            <summary>Pedidos</summary>
                            <ul class="bg-base-100 rounded-t-none p-2">
                                <li @click="closeDropdown"><router-link to="/pedidos">Todos los pedidos</router-link>
                                </li>
                                <li @click="closeDropdown"><router-link to="/pedidos/hoy">Pedidos de hoy</router-link>
                                </li>
                                <li @click="closeDropdown"><router-link to="/pedidos/automaticos">Pedidos
                                        automáticos</router-link></li>
                            </ul>
                        </details>
                    </li>
                        <!-- Producción -->
                    <li v-if="rol != 'Repartidor'" @click="closeDropdown">
                        <router-link to='/produccion'>Producción</router-link>
                    </li>
                    <!-- Administrar usuarios -->
                    <li v-if="rol != 'Repartidor'" @click="closeDropdown">
                        <router-link to='/admin'>Administrar
                            usuarios</router-link>
                    </li>
                    <!-- Cerrar sesión -->
                    <li @click="logout()" class="text-red-500">
                        <router-link to='/login' @click="closeDropdown">Cerrar sesión</router-link>
                    </li>
                </ul>
            </div>  
            <!-- Logo -->
            <a class="btn btn-ghost text-xl"><router-link to='/'>Nuestro Campo</router-link></a>
        </div>
        <!-- Este es el menú de pantallas grandes -->
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <!-- Inicio -->
                <li v-if="rol != 'Repartidor'"><router-link to='/'>Inicio</router-link></li>
                <!-- Últimos cambios (Repartidor) -->
                <li v-if="rol == 'Repartidor'"><router-link to='/iniciorepartidor'>Últimos cambios</router-link></li>
                <!-- Ordenar Pedidos (Repartidor) -->
                <li v-if="rol == 'Repartidor'"><router-link to='/pedidos/ordenar'>Ordenar pedidos</router-link></li>
                <!-- Repartos de hoy (Repartidor) -->
                <li v-if="rol == 'Repartidor'"><router-link to='/repartidor'>Repartos de hoy</router-link></li>
                <!-- Resumen diario -->
                <li><router-link to='/resumen'>Resumen diario</router-link></li>
                <!-- Clientes -->
                <li v-if="rol != 'Repartidor'">
                    <details ref="dropdown1">
                        <summary>Clientes</summary>
                        <ul class="bg-base-100 rounded-t-none p-2">
                            <li><router-link to="/clientes">Todos los Clientes</router-link></li>
                            <li><router-link to="/clientes/deudores">Clientes deudores</router-link></li>
                        </ul>
                    </details>
                </li>
                <!-- Productos -->
                <li v-if="rol != 'Repartidor'"><router-link to='/productos'>Productos</router-link></li>
                <!-- Sectores de Reparto -->
                <li v-if="rol != 'Repartidor'"><router-link to='/sectores'>Sectores de Reparto</router-link></li>
                <!-- Pedidos -->
                <li v-if="rol != 'Repartidor'">
                    <details ref="dropdown2">
                        <summary>Pedidos</summary>
                        <ul class="bg-base-100 rounded-t-none p-2">
                            <li><router-link to="/pedidos">Todos los pedidos</router-link></li>
                            <li><router-link to="/pedidos/hoy">Pedidos de hoy</router-link></li>
                            <li><router-link to="/pedidos/automaticos">Pedidos automáticos</router-link></li>
                        </ul>
                    </details>
                </li>
                <!-- Producción -->
                <li v-if="rol != 'Repartidor'"><router-link to='/produccion'>Producción</router-link></li>
                <!-- Administrar usuarios -->
                <li v-if="rol != 'Repartidor'"><router-link to='/admin'>Administrar usuarios</router-link></li>
                <!-- Cerrar sesión -->
                <li @click="logout()" class="text-red-500">
                    <router-link to='/login'>Cerrar sesión</router-link>
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
            isDropdownOpen: false,
        };
    },

    computed: {
        rol() {
            return this.sessionData && this.sessionData.rol;
        },
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        closeDropdown() {
            this.isDropdownOpen = false;
        },
        closeLargeMenuDropdowns() {
            // Verifica si las referencias existen antes de intentar cerrarlas
            if (this.$refs.dropdown1) {
                this.$refs.dropdown1.open = false;
            }
            if (this.$refs.dropdown2) {
                this.$refs.dropdown2.open = false;
            }
        },
        logout() {
            // Eliminar la información de sesión de localStorage
            localStorage.removeItem('authUser');
            // Actualiza el estado de la sesión
            this.sessionData = null;
            // Redirige a la página de inicio de sesión
            this.$router.push({ name: 'LogIn' });
        },
    },

    created() {
        this.sessionData = this.checkUserSession();
    },

    watch: {
        $route() {
            this.sessionData = this.checkUserSession();
            this.closeDropdown(); // Cierra el menú para pantallas pequeñas
            this.closeLargeMenuDropdowns(); // Cierra los dropdowns de pantallas grandes
        },
    },
}
</script>

<style scoped>
ul {
    position: relative;
    /* Para controlar el stacking context */
    z-index: 10;
    /* Para asegurarte de que el dropdown esté encima */
}
</style>
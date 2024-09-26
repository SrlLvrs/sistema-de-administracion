<template>
    <div class="prose max-w-none">
        <h1 class="text-center p-4 m-0">Todos los usuarios</h1>
        <div class="flex justify-center">
            <!-- Botón CREAR NUEVO USUARIO -->
            <CrearUsuario />
        </div>
    </div>
    <!-- TABLA DE USUARIOS -->
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in usuarios" :key="item.ID">
                    <th>{{ item.ID }}</th>
                    <td>{{ item.Username }}</td>
                    <td>{{ item.Rol }}</td>
                    <td>
                        <EditarPass :id=item.ID :label="item.ID + 'pass'" />
                        <EditarRol :id=item.ID :label="item.ID + 'rol'" />
                        <EliminarUsuario :id=item.ID :label="item.ID + 'eliminar'" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';
import CrearUsuario from '../components/CrearUsuario.vue'
import EditarPass from '../components/EditarPass.vue'
import EditarRol from '../components/EditarRol.vue'
import EliminarUsuario from '../components/EliminarUsuario.vue'

export default {
    //Nombre del componente
    name: "Admin",

    data() {
        return {
            usuarios: []
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
        async getUsers() {
            let url = `https://nuestrocampo.cl/api/users/read.php`;
            await axios.get(url).then((response) => (this.usuarios = response.data));
        }
    },

    mounted() {
        const sessionData = this.checkUserSession();
        if (sessionData) {
            console.log('Sesión iniciada');
            if (sessionData.rol === 'Administrador') {
                console.log('Sesión administrador. Montando...')
                this.getUsers();
            } else {
                alert('No tienes permiso para ver esta información.');
                this.$router.push({ name: 'Inicio' });
            }
        } else {
            console.log('No hay sesión iniciada. Redireccionado a login');
            this.$router.push({ name: 'LogIn' });
        }
    },

    components: { CrearUsuario, EditarPass, EditarRol, EliminarUsuario }
}
</script>
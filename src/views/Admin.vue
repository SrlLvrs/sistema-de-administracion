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
                        Eliminar usuario
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

export default {
    //Nombre del componente
    name: "Admin",

    data() {
        return {
            usuarios: []
        };
    },

    mounted() {
        let url = `https://nuestrocampo.cl/api/users/read.php`;
        axios.get(url).then((response) => (this.usuarios = response.data));
    },

    components: { CrearUsuario, EditarPass, EditarRol }
}
</script>
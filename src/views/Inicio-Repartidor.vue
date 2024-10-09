<template>
    <!-- 2 columnas: Pedidos hoy y log -->
    <div class="flex h-screen prose max-w-none">
        <div class="flex-1 px-4">
            <h1 class="text-center p-8">Ultimos cambios</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="log in logs" :key="log.ID">
                        <td>{{ log.log_time }}</td>
                        <td>{{ log.changes }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import DetallesPedido from "../components/DetallesPedido.vue";

export default {
    //Nombre del componente
    name: "Inicio",

    data() {
        return {
            logs: [],
            id_user: '',
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
        async llamadasConcurrentes() {
            const url = `https://nuestrocampo.cl/api/inicio/read-log-repartidor.php?idRepartidor=${this.id_user}`;

            try {
                const respuesta = await axios.get(url);
                this.logs = respuesta.data;
            } catch (error) {
                console.error('Error al obtener los logs del repartidor:', error);
            }
        },
    },

    computed: {
        //Nada por acá...
    },

    mounted() {
        const sessionData = this.checkUserSession();
        if (sessionData) {
            console.log('Sesión iniciada');
            this.id_user = sessionData.id;
            if (sessionData.rol === 'Repartidor') {
                this.llamadasConcurrentes();
            } else {
                console.log('El usuario es Administrador o Colaborador');
                this.$router.push({ name: 'Inicio' });
            }
        } else {
            console.log('No hay sesión iniciada. Redireccionado a login');
            this.$router.push({ name: 'LogIn' });
        }
    },

    components: { DetallesPedido }
}
</script>
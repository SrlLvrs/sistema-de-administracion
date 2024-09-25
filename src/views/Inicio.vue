<template>
    <!-- Estadísticas -->
    <div class="stats shadow flex justify-center">
        <div class="stat" v-for="item in items">
            <div class="stat-title">{{ item.Nombre }}</div>
            <div class="stat-value">{{ item.Valor }}</div>
        </div>
    </div>
    <div class="flex h-screen prose max-w-none">
        <div class="flex-1 px-4">
            <h1 class="text-center p-8">Pedidos pendientes para hoy</h1>
            <div v-if="pendientes.length == 0" class="flex justify-center items-center">
                <p class="text-center">No se encontraron pedidos</p>
            </div>
            <table v-else class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pedido in pendientes" :key="pedido.ID">
                        <td>{{ pedido.ID }}</td>
                        <td>{{ pedido.Nombre }}</td>
                        <td>{{ pedido.Direccion }}, {{ pedido.NombreSector }}, {{ pedido.Comuna }}</td>
                        <td class="not-prose">
                            <DetallesPedido :id="pedido.ID" :label="pedido.ID + 'label'" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex-1 px-4">
            <h1 class="text-center p-8">Ultimas modificaciones</h1>
            <p>Columna 2</p>
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
            items: [],
            pendientes: [],
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
        async llamadasConcurrentes() {
            const urls = [
                'https://nuestrocampo.cl/api/inicio/read.php',
                'https://nuestrocampo.cl/api/inicio/read-pendientes.php',
            ];

            const promises = urls.map(url => axios.get(url));

            try {
                const resultados = await Promise.allSettled(promises);
                resultados.forEach((resultado, indice) => {
                    if (resultado.status === 'fulfilled') {
                        // Si la promesa se resolvió correctamente
                        if (indice === 0) {
                            this.items = resultado.value.data;
                        } else {
                            this.pendientes = resultado.value.data;
                        }
                    } else {
                        // Si la promesa fue rechazada
                        console.error(`Error en el endpoint ${urls[indice]}`, resultado.reason);
                    }
                });
            } catch (error) {
                console.error(error);
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
            //this.id_user = sessionData.id;
            if (sessionData.rol === 'Repartidor') {
                alert('No tienes permiso para ver esta información.');
                this.$router.push({ name: 'Repartidor' });
            } else {
                console.log('El usuario es Administrador o Colaborador');
                this.llamadasConcurrentes();
            }
        } else {
            console.log('No hay sesión iniciada. Redireccionado a login');
            this.$router.push({ name: 'LogIn' });
        }


    },

    components: { DetallesPedido }
}
</script>
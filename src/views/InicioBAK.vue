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
            <table class="table">
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
                            <DetallesPedido :id="pedido.ID" :label="pedido.ID + 'label'"/>
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
        async llamadasConcurrentes() {
            const urls = [
                'https://nuestrocampo.cl/api/inicio/read.php',
                'https://nuestrocampo.cl/api/inicio/read-pendientes.php',
            ];

            const promises = urls.map(url => axios.get(url));

            try {
                const resultados = await Promise.all(promises);
                // resultados es un arreglo con las respuestas de cada endpoint
                console.log(resultados);
                this.items = resultados[0].data;
                this.pendientes = resultados[1].data;

            } catch (error) {
                console.error(error);
            }
        },
    },

    computed: {
        //Nada por acá...
    },

    //Método para llamar a la API cuando se cree la instancia
    mounted() {
        this.llamadasConcurrentes();
    },

    components: { DetallesPedido }
}
</script>
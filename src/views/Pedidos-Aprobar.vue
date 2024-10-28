<template>
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Pedidos Pendientes</h1>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table v-if="pedidosPendientes.length > 0" class="table table-zebra">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Fecha de Reparto</th>
                    <th>Observación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="pedido in pedidosPendientes" :key="pedido.ID">
                    <td>{{ pedido.Nombre }}, {{ pedido.Direccion }}, {{ pedido.NombreSector }}, {{ pedido.Comuna }}</td>
                    <td>{{ pedido.FechaEntrega }}</td>
                    <td>{{ pedido.ObservacionPA }}</td>
                    <td>
                        <div class="flex gap-2">
                            <button @click="agendarPedido(pedido.ID)" class="btn btn-outline btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Aprobar
                            </button>
                            <EditarPedido :id="pedido.ID" :label="'editar-pedido-' + pedido.ID" />
                            <DetallesPedido :id="pedido.ID" :label="'detalles-pedido-' + pedido.ID" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-else class="flex justify-center items-center h-screen">
            <p class="text-center">No se encontraron pedidos pendientes</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import EditarPedido from '../components/EditarPedido.vue';
import DetallesPedido from '../components/DetallesPedido.vue';

export default {
    name: "PedidosAprobar",
    data() {
        return {
            pedidosPendientes: [],
        }
    },
    methods: {
        async getPedidosPendientes() {
            try {
                const response = await axios.get('https://nuestrocampo.cl/demo/pedidos/get-pending.php');
                this.pedidosPendientes = response.data;
            } catch (error) {
                console.error("Error al obtener pedidos pendientes:", error);
            }
        },
        async agendarPedido(id) {
            try {
                await axios.post(`https://nuestrocampo.cl/demo/pedidos/schedule.php?id=${id}`);
                this.getPedidosPendientes();
            } catch (error) {
                console.error("Error al agendar pedido:", error);
            }
        }
    },
    mounted() {
        this.getPedidosPendientes();
    },
    components: {
        EditarPedido,
        DetallesPedido
    }
}
</script>

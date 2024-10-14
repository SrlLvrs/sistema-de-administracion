<template>
    <div class="table table-zebra">
        <h2>Pedidos Pendientes</h2>
        <table>
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
                        <button @click="agendarPedido(pedido.ID)" class="btn btn-outline btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            Agendar
                        </button>
                        <EditarPedido :id="pedido.ID" :label="'editar-pedido-' + pedido.ID" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';
import EditarPedido from '../components/EditarPedido.vue';


export default {
    data() {
        return {
            pedidosPendientes: []
        }
    },
    methods: {
        async getPedidosPendientes() {
            const response = await axios.get('https://nuestrocampo.cl/api/pedidos/get-pending.php');
            this.pedidosPendientes = response.data;
        },
        async agendarPedido(id) {
            await axios.post(`https://nuestrocampo.cl/api/pedidos/schedule.php?id_pedido=${id}`);
            
            location.reload();
        }
    },
    mounted() {
        this.getPedidosPendientes();
    },
    components: {
        EditarPedido
    }
}
</script>
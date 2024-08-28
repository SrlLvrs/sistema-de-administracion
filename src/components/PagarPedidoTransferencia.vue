<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-warning mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">Pagar el pedido {{ id }} con transferencia</h3>

            <p>¿Quieres marcar el pedido {{ id }} como pagado <span class="italic">con transferencia</span>?</p>

            <!-- Acciones -->
            <div class="modal-action">
                <label :for="label" class="btn btn-outline btn-error mr-2">No!</label>
                <label class="btn btn-outline btn-success mr-2" @click="pagarPedido()">Si, marcar como pagado con
                    transferencia</label>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    //Nombre del componente
    name: "PagarPedidoTransferencia",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            items: [],
        };
    },

    methods: {
        //Obtiene el detalle del pedido desde la Base de Datos
        pagarPedido() {
            let idp = this.id
            let url = `https://nuestrocampo.cl/api/pedidos/pay-order-wire.php?id=${idp}`
            axios.put(url).then(function (response) {
                console.log(response.data)
            })
            location.reload()
        },
    },
};
</script>
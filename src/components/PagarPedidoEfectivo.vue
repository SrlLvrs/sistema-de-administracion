<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-success mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">Pagar el pedido {{ id }} con efectivo</h3>

            <p>¿Quieres marcar el pedido {{ id }} como pagado <span class="italic">con efectivo</span>?</p>

            <!-- Acciones -->
            <div class="modal-action">
                <label :for="label" class="btn btn-outline btn-error mr-2">No!</label>
                <label class="btn btn-outline btn-success mr-2" @click="pagarPedido()">Si, marcar como pagado con efectivo</label>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    //Nombre del componente
    name: "PagarPedidoEfectivo",

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
            let url = `https://nuestrocampo.cl/api/pedidos/pay-order-cash.php?id=${idp}`
            axios.put(url).then(function (response) {
                console.log(response.data)
            })
            location.reload()
        },
    },
};
</script>
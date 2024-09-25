<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">Entregar el pedido {{ id }}</h3>

            <p>Elige la opción que corresponda. Luego de eso, el pedido desaparecerá de la lista.</p>

            <!-- Entregado, efectivo -->
            <div>
                <label class="btn btn-outline btn-success m-2" @click="efectivo()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                    Entregado, pagado con Efectivo</label>
            </div>
            <!-- Entregado, transferencia -->
            <div>
                <label class="btn btn-outline btn-success m-2" @click="transferencia()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                    Entregado, pagado con Transferencia</label>
            </div>
            <div>
                <label class="btn btn-outline btn-warning m-2" @click="entregado(id)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    Entregado, sin pagar</label>
            </div>
            <div>
                <label class="btn btn-outline btn-error m-2" @click="pagarPedido()">Rechazado, el cliente explicitamente
                    rechaza el pedido</label>
            </div>
            <div>
                <label class="btn btn-outline btn-error m-2" @click="pagarPedido()">Rechazado, el cliente no se
                    encuentra</label>
            </div>
            <div>
                <label class="btn btn-outline btn-error m-2" @click="pagarPedido()">Rechazado, otro motivo</label>
            </div>
            <!-- Acciones -->
            <div class="modal-action ">
                <label :for="label" class="btn">Salir</label>
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
        pagarPedido() {
            //Nada
        },
        async entregado(idp) {
            //Marcar como Entregado
            let url = `https://nuestrocampo.cl/api/pedidos/update-deliver.php?id=${idp}`
            await axios.put(url).then(function (response) {
                console.log(response.data)
            })
            location.reload()
        },
        async efectivo() {
            //Pagado efectivo
            let idp = this.id
            let url = `https://nuestrocampo.cl/api/pedidos/pay-order-cash.php?id=${idp}`
            await axios.put(url).then(function (response) {
                console.log(response.data)
            })

            await this.entregado(idp)
        },
        async transferencia() {
            //pagado transferencia
            let idp = this.id
            let url = `https://nuestrocampo.cl/api/pedidos/pay-order-wire.php?id=${idp}`
            await axios.put(url).then(function (response) {
                console.log(response.data)
            })

            await this.entregado(idp)
        }
    },
};
</script>
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

            <div class="flex justify-center">
                <p>Elige la opción que corresponda. Luego de eso, el pedido desaparecerá de la lista.</p>
            </div>

            <!-- Entregado, efectivo -->
            <div class="flex justify-center">
                <label class="btn btn-outline btn-success m-2 w-full h-auto text-sm flex flex-col items-center p-2" @click="efectivo()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8 mb-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                    <span>Entregado / Efectivo</span>
                </label>
            </div>

            <!-- Entregado, sin pagar -->
            <div class="flex justify-center">
                <label class="btn btn-outline btn-warning m-2 w-full h-auto text-sm flex flex-col items-center p-2" @click="entregado(id, 'Entregado / Pendiente de pago')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8 mb-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span>Entregado / Pendiente de pago</span>
                </label>
            </div>

            <!-- No entregado -->
            <div class="flex justify-center">
                <label class="btn btn-outline btn-error m-2 w-full h-auto text-sm flex flex-col items-center p-2" @click="entregado(id, 'No Entregado')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8 mb-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                    </svg>
                    <span>No entregado</span>
                </label>
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
    name: "EntregarEfectivo",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            user: '',
            pedido: [],
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
        //Obtiene el usuario para el log
        async getUser() {
            const sessionData = this.checkUserSession();
            this.user = sessionData.username
        },
        //Obtiene el pedido para el log
        async getPedido() {
            let url = `https://nuestrocampo.cl/api/pedidos/read-by-id.php?id=${this.id}`
            await axios.get(url).then((response) => {
                this.pedido = response.data
            })
        },
        async entregado(idp, estado) {
            //Marcar como ${estado}
            let msg = this.pedido[0].Nombre + ', ' + this.pedido[0].Direccion + ', ' + this.pedido[0].NombreSector + ', ' + this.pedido[0].Comuna
            let url = `https://nuestrocampo.cl/api/pedidos/update-state.php?id=${idp}&estado=${estado}&user=${this.user}&msg=${msg}`
            await axios.put(url).then(function (response) {
                console.log(response.data)
            }).then(() => {
                location.reload()
            })
        },
        async efectivo() {
            //Pagar con efectivo
            let idp = this.id
            let msg = this.pedido[0].Nombre + ', ' + this.pedido[0].Direccion + ', ' + this.pedido[0].NombreSector + ', ' + this.pedido[0].Comuna
            let url = `https://nuestrocampo.cl/api/pedidos/pay-order-cash.php?id=${idp}&user=${this.user}&msg=${msg}`
            await axios.put(url).then(function (response) {
                console.log(response.data)
            })
            location.reload()
        },
    },

    mounted() {
        this.getUser()
        this.getPedido()
    }
};
</script>
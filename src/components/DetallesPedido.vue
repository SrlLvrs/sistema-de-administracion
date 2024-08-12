<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-warning mr-2" @click="leerDetallePedido()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box">
            <h3 class="text-lg font-bold mb-2 text-center">Detalle del pedido {{ id }}</h3>

            {{ items }}

            <!-- Agregar aca el spinner de carga para que cargue la info de los items[0] -->

            <div v-for="item in items">
                <!-- Cliente -->
                <div class="label">
                    <span class="label-text font-bold">Nombre del cliente</span>
                </div>
                <p> {{ item[0].Nombre }} </p>

                <!-- Estado -->
                <div class="label">
                    <span class="label-text font-bold">Estado</span>
                </div>
                <p> {{ item[0].Estado }}</p>

                <!-- Pagado -->
                <div class="label">
                    <span class="label-text font-bold">Pagado</span>
                </div>
                <p> {{ item[0].Pagado }} </p>

                <!-- Medio de Pago -->
                <div class="label">
                    <span class="label-text font-bold">Medio de Pago</span>
                </div>
                <p> {{ item[0].MedioPago }} </p>

                <!-- Fecha de Entrega -->
                <div class="label">
                    <span class="label-text font-bold">Fecha de Entrega</span>
                </div>
                <p> {{ item[0].FechaEntrega }} </p>
            </div>

            <!-- Detalle -->
            <div>
                <div class="label">
                    <span class="label-text font-bold">Detalle del pedido</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in items" :key="index" :value="index">
                                <th>{{ item.Cantidad }}</th>
                                <th>{{ item.Descripcion }}</th>
                                <td>{{ item.Total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="label">
                    <span class="label-text font-bold">Total: {{ total }}</span>
                </div>
            </div>

            <!-- Acciones -->
            <div class="modal-action">
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
    name: "EditarProducto",

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
        leerDetallePedido() {
            let idp = this.id;
            let url = `https://nuestrocampo.cl/api/pedidos/read-detail.php?id=${idp}`

            axios.get(url).then((response) => (this.items = response.data));
        }

    },
};
</script>
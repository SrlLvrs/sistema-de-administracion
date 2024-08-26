<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-info mr-2" @click="leerDetalleCliente()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">Detalle de deudas del cliente</h3>

            <!-- Loading Spinner en Renderización condicional -->
            <div v-if="suma === null">
                <span class="loading loading-spinner loading-md"></span>
            </div>

            <div v-else>
                <div v-for="(item, index) in items" :key="index" :value="index">
                    <!-- Cliente -->
                    <div class="label">
                        <span class="label-text font-bold">Cliente</span>
                    </div>
                    <p> {{ item.Nombre }} - {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</p>

                    <!-- Dirección completa 
                    <div class="label">
                        <span class="label-text font-bold">Dirección</span>
                    </div>
                    <p> {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</p>
                    -->

                    <!-- Teléfono 
                    <div class="label">
                        <span class="label-text font-bold">Teléfono</span>
                    </div>
                    <p>{{ item.Telefono }}</p>
                    -->

                    <!-- Teléfono2 
                    <div class="label">
                        <span class="label-text font-bold">Teléfono 2</span>
                    </div>
                    <p>{{ item.Telefono2 }}</p>
                    -->

                    <!-- Frecuencia
                    <div class="label">
                        <span class="label-text font-bold">Frecuencia</span>
                    </div>
                    <p> {{ item.Frecuencia }}</p>
                    -->

                    <!-- Día de Reparto
                    <div class="label">
                        <span class="label-text font-bold">Día de reparto</span>
                    </div>
                    <p> {{ item.Dia_de_Reparto }}</p>
                    -->

                    <!-- Producto Preferido
                    <div class="label">
                        <span class="label-text font-bold">Preferencia</span>
                    </div>
                    <p> {{ item.Producto_Preferido }}</p>
                    -->

                    <!-- Observaciones 
                    <div class="label">
                        <span class="label-text font-bold">Observaciones</span>
                    </div>
                    <p> {{ item.Observacion }}</p>
                    -->
                </div>

                <!-- Detalle -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Pedidos adeudados</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <!-- head -->
                            <thead>
                                <tr>
                                    <th>ID Pedido</th>
                                    <th>Estado</th>
                                    <th>Pagado</th>
                                    <th>Medio de Pago</th>
                                    <th>Total del Pedido</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pedido, index) in detalle" :key="index" :value="index">
                                    <th>{{ pedido.IDPedido }}</th>
                                    <th>{{ pedido.Estado }}</th>
                                    <td>{{ pedido.Pagado }}</td>
                                    <td>{{ pedido.MedioPago }}</td>
                                    <td>{{ pedido.Total_Pedido }}</td>
                                    <td>
                                        <PagarPedido 
                                        :id="pedido.IDPedido"
                                        :label="pedido.IDPedido + 'label' "/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="label">
                        <span class="label-text font-bold">Total: {{ suma }}</span>
                    </div>
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
import PagarPedido from "../components/PagarPedido.vue"

export default {
    //Nombre del componente
    name: "DetalleCliente",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            items: [],
            detalle: [],
            suma: null,
        };
    },

    methods: {
        //Obtiene el detalle del cliente desde la Base de Datos
        leerDetalleCliente() {
            let idc = this.id;
            let url = `https://nuestrocampo.cl/api/clientes/read-detail.php?id=${idc}`

            axios.get(url).then((response) => (this.items = response.data));

            let url2 = `https://nuestrocampo.cl/api/clientes/read-order-detail.php?id=${idc}`
            axios.get(url2).then((response) => (this.detalle = response.data));

            //Sumar el total
            setTimeout(() => {
                let adicion = this.detalle.reduce((total, pedido) => {
                    return total + parseInt(pedido.Total_Pedido, 10);
                }, 0);
                this.suma = adicion
                console.log(this.suma)
            }, 1000);
        },
    },

    components: { PagarPedido },
};
</script>
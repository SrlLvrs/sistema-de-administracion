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
                                    <!-- 
                                        <th>Medio de Pago</th>
                                        -->
                                    <th>Total del Pedido</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pedido, index) in detalle" :key="index" :value="index">
                                    <th>{{ pedido.IDPedido }}</th>
                                    <th>{{ pedido.Estado }}</th>
                                    <td>{{ pedido.Pagado }}</td>
                                    <!-- 
                                        <td>{{ pedido.MedioPago }}</td>
                                        -->
                                    <td>{{ pedido.Total_Pedido }}</td>
                                    <td>
                                        <PagarPedidoEfectivo :id="pedido.IDPedido" :label="pedido.IDPedido + 'cash'" />
                                        <PagarPedidoTransferencia :id="pedido.IDPedido"
                                            :label="pedido.IDPedido + 'wire'" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="label">
                        <span class="label-text font-bold">Total adeudado: {{ suma }}</span>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="modal-action ">
                    <!-- Copiar al portapapeles -->
                    <label class="btn btn-outline mr-2" @click="copyToClipboard(copy)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        {{ mensaje }}
                    </label>
                    <label :for="label" class="btn" @click="reiniciar()">Salir</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import PagarPedidoEfectivo from "../components/PagarPedidoEfectivo.vue"
import PagarPedidoTransferencia from "../components/PagarPedidoTransferencia.vue"

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
            copy: '',
            mensaje: 'Copiar detalle de la deuda'
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
                this.whatsapp()
            }, 1000);
        },
        whatsapp() {
            let array = this.detalle
            this.copy = 'Estimado cliente\n\nLe comentamos que al día de hoy mantiene una deuda con nosotros.\n\nEl detalle de la deuda es el siguiente:\n\n'
            for (let i = 0; i < array.length; i++) {
                this.copy = this.copy + '- Pedido N°' + array[i].IDPedido + ' | ' + array[i].Estado + ' | Total: $' + array[i].Total_Pedido + '\n'
            }
            this.copy = this.copy + '\n'
            this.copy = this.copy + 'El total de su deuda es de: $' + this.suma + '\n\n'
            this.copy = this.copy + 'Por favor envie el comprobante de pago para regularizar su deuda'
        },
        async copyToClipboard(info) {
            if (navigator.clipboard) {
                try {
                    await navigator.clipboard.writeText(info);
                    this.mensaje = 'Copiado!'
                    //alert("Texto copiado al portapapeles usando CLIPBOARD!");
                } catch (err) {
                    console.error("Error al copiar el texto: ", err);
                }
            } else {
                // Fallback para navegadores que no soportan navigator.clipboard
                const textArea = document.createElement("textarea");
                textArea.value = info;
                textArea.style.position = "absolute";
                textArea.style.left = "-9999px";
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand("copy");
                document.body.removeChild(textArea);
                this.mensaje = 'Copiado!'
                //alert("Texto copiado al portapapeles usando execCommand!");
            }
        },
        reiniciar() {
            this.mensaje = 'Copiar detalle de la deuda'
        }
    },

    components: { PagarPedidoEfectivo, PagarPedidoTransferencia },
};
</script>
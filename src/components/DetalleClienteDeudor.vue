<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-error mr-2" @click="leerDetalleCliente()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
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
                                    <td>{{ pedido.Total_Pedido }}</td>
                                    <td>
                                        <DetallePedidoMinimal :id="pedido.IDPedido"
                                            :label="pedido.IDPedido + 'detail'" />
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
                    <!-- Enviar mensaje de WhatsApp -->
                    <label class="btn btn-outline mr-2" @click="enviarMensajeWhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
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
import DetallePedidoMinimal from "../components/DetallesPedidoMinimal.vue"

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
            mensaje: 'Enviar detalle de la deuda',
            // Añadir el número de teléfono del cliente
            telefono: '',
        };
    },

    methods: {
        //Obtiene el detalle del cliente desde la Base de Datos
        leerDetalleCliente() {
            let idc = this.id;
            let url = `https://nuestrocampo.cl/demo/clientes/read-detail.php?id=${idc}`

            axios.get(url).then((response) => {
                this.items = response.data;
                // Asumiendo que el teléfono del cliente está en la respuesta
                this.telefono = response.data[0].Telefono;
            });

            let url2 = `https://nuestrocampo.cl/demo/clientes/read-order-detail.php?id=${idc}`
            axios.get(url2).then((response) => {
                this.detalle = Array.isArray(response.data) ? response.data : [];
                this.calcularSuma();
            });
        },

        calcularSuma() {
            if (Array.isArray(this.detalle) && this.detalle.length > 0) {
                this.suma = this.detalle.reduce((total, pedido) => {
                    return total + parseInt(pedido.Total_Pedido, 10);
                }, 0);
                this.whatsapp();
            } else {
                this.suma = 0;
                this.whatsapp();
            }
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

        enviarMensajeWhatsApp() {
            let encodedMsg = encodeURIComponent(this.copy);
            let phoneNumber = this.telefono;

            // Detectar si es un dispositivo móvil
            let isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

            let whatsappUrl = isMobile
                ? `whatsapp://send?phone=${phoneNumber}&text=${encodedMsg}`
                : `https://web.whatsapp.com/send?phone=${phoneNumber}&text=${encodedMsg}`;

            window.open(whatsappUrl, '_blank');
            this.mensaje = '¡Mensaje enviado!';
        },

        reiniciar() {
            this.mensaje = 'Enviar detalle de la deuda';
        }
    },

    components: { PagarPedidoEfectivo, PagarPedidoTransferencia, DetallePedidoMinimal },
};
</script>
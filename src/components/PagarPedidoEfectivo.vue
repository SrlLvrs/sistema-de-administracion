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
import axios from "axios";

export default {
    name: "PagarPedidoEfectivo",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            pedido: [],
            detalle_pedido: [],
            user: '',
            user_id: '',
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },

        async getUser() {
            const sessionData = this.checkUserSession();
            this.user = sessionData.username;
            this.user_id = sessionData.id;
        },

        async getPedido() {
            let idp = this.id;
            let url = `https://nuestrocampo.cl/demo/pedidos/read-by-id.php?id=${idp}`;
            await axios.get(url).then((response) => {
                this.pedido = response.data;
                return axios.get(`https://nuestrocampo.cl/demo/pedidos/read-detail.php?id=${idp}`);
            }).then((detailResponse) => {
                this.detalle_pedido = detailResponse.data;
            });
        },

        enviarMensajeWhatsApp() {
            let whatsappMsg = `Hola! Soy el repartidor de Nuestro Campo. Este mensaje se ha creado de forma automática.\n\n`
            whatsappMsg += `Este es el detalle de tu pedido:\n`
            this.detalle_pedido.forEach(item => {
                whatsappMsg += `- Cantidad: ${item.Cantidad} | Descripción: ${item.Descripcion} | Total: $${item.Total}\n`
            })
            whatsappMsg += `\nEstado del pedido: *${this.pedido[0].Estado}*\n`
            whatsappMsg += `Medio de Pago: *${this.pedido[0].MedioPago}*\n`
            whatsappMsg += `Pagado: *${this.pedido[0].Pagado}*\n`
            whatsappMsg += `Fecha de Entrega: *${this.pedido[0].FechaEntrega}*\n`

            let encodedMsg = encodeURIComponent(whatsappMsg)
            let phoneNumber = this.pedido[0].Telefono

            // Detectar si es un dispositivo móvil
            let isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent)

            let whatsappUrl = isMobile
              ? `whatsapp://send?phone=${phoneNumber}&text=${encodedMsg}`
              : `https://web.whatsapp.com/send?phone=${phoneNumber}&text=${encodedMsg}`

            window.open(whatsappUrl, '_blank')
        },

        async pagarPedido() {
            await this.getUser();
            await this.getPedido();

            let idp = this.id
            let msg = this.pedido[0].Nombre + ', ' + this.pedido[0].Direccion + ', ' + this.pedido[0].NombreSector + ', ' + this.pedido[0].Comuna
            let url = `https://nuestrocampo.cl/demo/pedidos/pay-order-cash.php?id=${idp}&user=${this.user}&msg=${msg}&idrepartidor=${this.user_id}`
            await axios.put(url).then((response) => {
                console.log(response.data)
                return this.getPedido()
            }).then(() => {
                console.log(this.pedido)
            })

            // Enviar mensaje de WhatsApp
            this.enviarMensajeWhatsApp()

            // Recargar la página
            location.reload()
        },
    },
};
</script>
<template>
    <!-- Modal CREAR NUEVO PEDIDO-->
    <!-- Botón para abrir el modal -->
    <!-- ACÁ SE NECESITA UN ID UNICO PARA PODER ABRIR EL MODAL, SINO ABRIRÁ EL MODAL DE ELIMINAR CL -->
    <label :for="id" class="btn btn-outline btn-success mr-2" @click="crearPedido()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="id" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Nuevo Pedido</h3>
            <p> ACÁ VA LA INFO DEL PEDIDO </p>
            <p>El id del cliente es {{ cliente }}</p>
            <p>{{ items }}</p>
            <!-- ACÁ VA LA INFORMACION RELEVANTE DE ITEMS -->
            <div class="modal-action">
                <label :for="id" class="btn">Salir</label>
                <button class="btn btn-outline btn-success" @click="editarSector(id)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Crear Pedido
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    //Nombre del componente
    name: "CrearPedidoModal",

    props: {
        id: Number,
        cliente: Number,
    },

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
        };
    },

    computed: {

    },

    methods: {
        crearPedido() {
            //POST nuevo pedido
            let idc = this.cliente;
            let url = `https://nuestrocampo.cl/api/pedidos/create.php?id_cliente=${idc}`
            axios.post(url);

            //GET último pedido
            let url2 = "https://nuestrocampo.cl/api/pedidos/last-read.php";
            setTimeout(() => {
                axios.get(url2).then((response) => (this.items = response.data));
            }, 1000);
        },
    }
}
</script>
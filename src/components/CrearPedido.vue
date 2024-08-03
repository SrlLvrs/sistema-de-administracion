<template>
    <!-- Modal CREAR NUEVO PEDIDO-->
    <!-- Botón para abrir el modal -->
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
            <h3 class="text-lg font-bold text-center">Nuevo Pedido</h3>

            <div v-for="item in items">
                <!-- Nombre del cliente -->
                <div class="label">
                    <span class="label-text font-bold">Nombre del Cliente</span>
                </div>
                <p> {{ item.cliente }} </p>
                
                <!-- Estado del pedido -->
                <div class="label">
                    <span class="label-text font-bold">Estado</span>
                </div>
                <p> {{ item.estado }}</p>
            </div>

            <!-- Todos los productos -->
            <div class="label">
                <span class="label-text font-bold">Productos</span>
            </div>

            <select v-model="producto_seleccionado" class="select select-bordered w-full max-w-xs">
                <option v-for="producto in productos">
                    {{ producto.descripcion }}
                </option>
            </select>
            <label class="btn" @click="agregarProducto()">Añadir</label>

            <div class="label">
                <span class="label-text font-bold">Productos elegidos</span>
            </div>
            <ul>
                <li v-for="producto_elegido in productos_elegidos"> - {{ producto_elegido }}</li>
            </ul>



            <!-- Acciones -->
            <div class="modal-action">
                <label :for="id" class="btn">Salir</label>
                <button class="btn btn-outline btn-success" @click="">
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
        id: String,
        cliente: Number,
    },

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
            productos: [],
            producto_seleccionado: '',
            productos_elegidos: [],
        };
    },

    computed: {

    },

    methods: {
        crearPedido() {
            //POST nuevo pedido
            /**
             * 
             let idc = this.cliente;
             let url = `https://nuestrocampo.cl/api/pedidos/create.php?id_cliente=${idc}`
             axios.post(url);
             */

            //GET último pedido
            let url2 = "https://nuestrocampo.cl/api/pedidos/last-read.php";
            setTimeout(() => {
                axios.get(url2).then((response) => (this.items = response.data));
            }, 1000);

            let url3 = "https://nuestrocampo.cl/api/productos/read.php";
            axios.get(url3).then((response) => (this.productos = response.data));
        },
        agregarProducto(){
            if (this.producto_seleccionado) {
                this.productos_elegidos.push(this.producto_seleccionado)
            }
        },
    }
}
</script>
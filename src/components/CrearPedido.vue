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
                <option v-for="(producto, index) in productos" :key="index" :value="index">
                    {{ producto.descripcion }} - ${{ producto.precio }}
                </option>
            </select>
            <label class="btn" @click="agregarProducto()">Añadir</label>

            <!-- Productos elegidos -->
            <div class="label">
                <span class="label-text font-bold">Productos elegidos</span>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(producto, index) in productos_elegidos" :key="index" :value="index">
                            <th>{{ producto.descripcion }}</th>
                            <td>{{ producto.precio }}</td>
                            <button class="btn" @click="removeItem(index)">Eliminar</button>
                        </tr>
                    </tbody>
                </table>
            </div>










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
        agregarProducto() {
            if (this.producto_seleccionado !== null) {
                const selectedArray = this.productos[this.producto_seleccionado];
                let spread = { ...selectedArray }
                console.log(spread)
                this.productos_elegidos.push(spread);
                console.log('producto añadido')
            }
        },
        removeItem(index) {
            console.log(index)
            this.productos_elegidos.splice(index, 1);
        },
    }
}
</script>
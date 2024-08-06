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
        <div class="modal-box modal-pedido">
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

                <!-- Pagado -->
                <div class="label">
                    <span class="label-text font-bold">Pagado</span>
                </div>
                <p> {{ item.pagado }}</p>

                <!-- Medio de Pago -->
                <div class="label">
                    <span class="label-text font-bold">Medio de Pago</span>
                </div>
                <p> {{ item.medio_pago }}</p>
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

            <!-- Cantidad -->
            <div class="label">
                <span class="label-text font-bold">Cantidad</span>
            </div>
            <input v-model="cantidad" type="number" placeholder="1" class="input input-bordered w-full max-w-xs" />
            <label class="btn btn-outline btn-success m-1" @click="agregarProducto()">Añadir</label>

            <!-- Productos elegidos -->
            <!-- Sólo aparece si el array productos_elegidos tiene elementos -->
            <div v-if="productos_elegidos.length > 0">
                <div class="label">
                    <span class="label-text font-bold">Productos elegidos</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(producto, index) in productos_elegidos" :key="index" :value="index">
                                <th>{{ producto.cantidad }}</th>
                                <th>{{ producto.descripcion }}</th>
                                <td>{{ producto.precio }}</td>
                                <td>{{ producto.total }}</td>
                                <button class="btn btn-outline btn-error m-1" @click="borrarProducto(index)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
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
                <label :for="id" class="btn">Salir</label>
                <button class="btn btn-outline btn-success" @click="crearPedido_Producto()">
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
            cantidad: 1,
            total: '',
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

            //GET todos los productos
            let url3 = "https://nuestrocampo.cl/api/productos/read.php";
            axios.get(url3).then((response) => (this.productos = response.data));
        },
        agregarProducto() {
            //Mueve los productos seleccionados del array de todos los productos, a un array vacío
            //los cuales irán finalmente a la base de datos
            if (this.producto_seleccionado !== null) {
                //Nuevo proxy en base al index
                const nuevoArray = this.productos[this.producto_seleccionado];

                //De proxy a objeto
                let spread = { ...nuevoArray }

                //Calcular el total por cantidad de productos
                let total = this.cantidad * spread.precio;

                //Añadir cantidad y total al objeto
                spread['cantidad'] = this.cantidad;
                spread['total'] = total;
                //Añadir pedido
                spread['id_pedido'] = this.items[0].id

                //Añadir el objeto al array (y a la tabla)
                this.productos_elegidos.push(spread);

                //Sumar el total de todos los productos.
                let totalSum = this.productos_elegidos.reduce((accumulator, currentItem) => {
                    return accumulator + currentItem.total;
                }, 0);

                this.total = totalSum;
            }
        },
        borrarProducto(i) {
            this.productos_elegidos.splice(i, 1);

            //Sumar el total de todos los productos restantes.
            let totalSum = this.productos_elegidos.reduce((accumulator, currentItem) => {
                return accumulator + currentItem.total;
            }, 0);

            this.total = totalSum;
        },
        crearPedido_Producto() {
            //Envía todos los productos del array, usando un ciclo for.
            let array = this.productos_elegidos;
            for (let i = 0; i < array.length; i++) {
                //Definir variables
                let pedido = array[i].id_pedido;
                let producto = array[i].id;
                let cantidad = array[i].cantidad;
                let total = array[i].total;

                let url = `https://nuestrocampo.cl/api/pedidos_productos/create.php?id_pedido=${pedido}&id_producto=${producto}&cantidad=${cantidad}&total=${total}`
                axios.post(url);
                console.log('Productos enviados a la base de datos')
            }
        },
    }
}
</script>
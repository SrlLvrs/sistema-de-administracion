<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-info mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
        </svg>
        Despachar repartidor
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">
                Despachar repartidor
            </h3>

            <div>
                <!-- Repartidor -->
                <label class="label">
                    <span class="label-text font-bold">Elegir repartidor</span>
                </label>
                <select v-model="repartidor" class="select select-bordered w-full max-w-xs">
                    <option v-for="item in items" :key="item.ID" :value="item.ID">{{ item.Username }}</option>
                </select>

                <!-- Productos -->
                <label class="label">
                    <span class="label-text font-bold">Agrega productos al camión</span>
                </label>
                <select v-model="producto_seleccionado" class="select select-bordered w-full max-w-xs">
                    <option v-for="(item, index) in productos" :key="index" :value="index">
                        {{ item.descripcion }}
                    </option>
                    <!--
                        <option v-for="item in productos" :key="item.id" :value="item.id">{{ item.descripcion }}</option>
                    -->
                </select>

                <!-- Cantidad -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Cantidad</span>
                    </div>
                    <input v-model="cantidad" type="number" placeholder="1"
                        class="input input-bordered w-full max-w-xs" />
                    <label class="btn btn-outline btn-success m-1" @click="agregarProducto()">Añadir</label>
                </div>

                <!-- Productos elegidos -->
                <!-- Sólo aparece si el array productos_elegidos tiene elementos -->
                <div v-if="productos_elegidos.length > 0">
                    <div class="label">
                        <span class="label-text font-bold">Productos elegidos</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <!-- Cabecera -->
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Producto</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <!-- Cuerpo -->
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

                <!-- Botones del modal -->
                <div class="modal-action">
                    <label :for="label" class="btn">Salir</label>
                    <button class="btn btn-outline btn-info" @click="despachar()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        Despachar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    name: "DespacharRepartidor",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            items: [],
            repartidor: '',
            cantidad: 1,
            total: 0,
            productos: [],
            producto_seleccionado: '',
            productos_elegidos: [],
        };
    },

    methods: {
        async despachar() {
            let hoy = new Date().toISOString().split('T')[0];
            let idr = this.repartidor;
            let array = this.productos_elegidos

            for (let i = 0; i < array.length; i++) {
                let idp = array[i].id
                let c = array[i].cantidad
                let url = `https://nuestrocampo.cl/api/resumen/create.php?idp=${idp}&idr=${idr}&cantidad=${c}&fecha=${hoy}`

                axios.post(url).then(function (response) {
                    console.log(response.data)
                })
            }

            location.reload()
        },
        /** Elimina los productos de la tabla de productos seleccionados */
        borrarProducto(i) {
            this.productos_elegidos.splice(i, 1);

            //Sumar el total de todos los productos restantes.
            this.adicion();
        },
        /** Sumar el total */
        adicion() {
            let totalSum = this.productos_elegidos.reduce((accumulator, currentItem) => {
                return accumulator + currentItem.total;
            }, 0);

            this.total = totalSum;
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

                //Añadir el objeto al array (y a la tabla)
                this.productos_elegidos.push(spread);

                this.adicion()
            }
        },
        async getRepartidores() {
            let url = `https://nuestrocampo.cl/api/users/read-deliver.php`;
            await axios.get(url).then((response) => (this.items = response.data));
        },
        async getProductos() {
            let url = `https://nuestrocampo.cl/api/productos/read.php`
            await axios.get(url).then((response) => (this.productos = response.data));
        },
    },

    mounted() {
        this.getRepartidores()
        this.getProductos()
    }
};
</script>

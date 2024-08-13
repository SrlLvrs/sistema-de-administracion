<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-warning mr-2" @click="leerDetalles(id)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">Editar pedido {{ id }}</h3>

            <div v-if="items.length === 0">
                <span class="loading loading-spinner loading-md"></span>
            </div>

            <div v-else>
                <!-- Nombre cliente -->
                <div class="label">
                    <span class="label-text font-bold">Cliente</span>
                </div>

                <select v-model="id_cliente_nuevo" class="select select-bordered w-full max-w-xs">
                    <option disabled selected> {{ this.items[0].Nombre }} </option>
                    <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id"> {{ cliente.nombre }}
                    </option>
                </select>
                <p>id_cliente_actual = {{ id_cliente_actual }}</p>
                <p>id_cliente_nuevo = {{ id_cliente_nuevo }} </p>

                <!-- Estado -->
                <div class="label">
                    <span class="label-text font-bold">Estado</span>
                </div>
                <select v-model="estado_actual" class="select select-bordered w-full max-w-xs">
                    <option disabled selected> {{ this.items[0].Estado }} </option>
                    <option v-for="estado in estados"> {{ estado }} </option>
                </select>
                <p>estado_actual = {{ estado_actual }}</p>

                <!-- Pagado -->
                <div class="label">
                    <span class="label-text font-bold">Pagado</span>
                </div>
                <select v-model="pagado" class="select select-bordered w-full max-w-xs">
                    <option disabled selected> {{ this.items[0].Pagado }}</option>
                    <option v-for="pagado in pagado_options"> {{ pagado }} </option>
                </select>
                <p>pagado = {{ pagado }}</p>

                <!-- Medio de Pago -->
                <div class="label">
                    <span class="label-text font-bold">Medio de Pago</span>
                </div>
                <select v-model="mediopago" class="select select-bordered w-full max-w-xs">
                    <option disabled selected> {{ this.items[0].Pagado }}</option>
                    <option v-for="pago in mediopago_options"> {{ pago }}</option>
                </select>
                <p>mediopago = {{ mediopago }}</p>

                <!-- Fecha de Entrega -->
                <div class="label">
                    <span class="label-text font-bold">Fecha de Entrega</span>
                </div>
                <VCalendar v-model="fechaentrega"/>
                <p>fechaentrega = {{ fechaentrega }} </p>

                <p>detallepedido = {{ detallepedido }} </p>

                <!-- Detalles -->
                    <div class="label">
                        <span class="label-text font-bold">Productos actualmente en este pedido</span>
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
                                <tr v-for="(detalle, index) in detallepedido" :key="index" :value="index">
                                    <th>{{ detalle.Cantidad }}</th>
                                    <th>{{ detalle.Descripcion }}</th>
                                    <td>{{ detalle.Precio }}</td>
                                    <td>{{ detalle.Total }}</td>
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
                
                <!-- Botones del modal -->
                <div class="modal-action">
                    <label :for="label" class="btn">Salir</label>
                    <button class="btn btn-outline btn-warning" @click="editarCliente(id)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Editar pedido
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import VCalendar from "../components/VCalendar.vue";

export default {
    //Nombre del componente
    name: "EditarPedido",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            //Arrays para guardar datos de la API
            items: [],
            clientes: [],
            id_cliente_actual: '',
            id_cliente_nuevo: '',
            estado_actual: '',
            estados: ['Agendado', 'Entregado', 'Rechazado'],
            pagado: '',
            pagado_options: ['Si', 'No'],
            mediopago: '',
            mediopago_options: ['Efectivo', 'Tarjeta de Débito', 'Tarjeta de Crédito', 'Transferencia'],
            fechaentrega: '',
            detallepedido: [],
        };
    },

    methods: {
        //GET detalle del pedido en base al IDPEDIDO
        leerDetalles(id) {
            let url = `https://nuestrocampo.cl/api/pedidos/read-detail.php?id=${id}`

            axios.get(url).then((response) => (this.items = response.data));


            //Mueve los productos seleccionados del array de todos los productos, a un array vacío
            //los cuales irán finalmente a la base de datos
            /*
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
                */
            
        

            setTimeout(() => {
                this.id_cliente_actual = this.items[0].IDCliente;
                this.id_cliente_nuevo = this.items[0].Nombre;
                this.estado_actual = this.items[0].Estado;
                this.pagado = this.items[0].Pagado;
                this.mediopago = this.items[0].MedioPago;
                this.fechaentrega = this.items[0].FechaEntrega;

                for (let i = 0; i < this.items.length; i++) {

                let articulo = {}
                articulo['IDProducto'] = this.items[i].IDProducto
                articulo['Descripcion'] = this.items[i].Descripcion
                articulo['Cantidad'] = this.items[i].Cantidad
                articulo['Precio'] = this.items[i].Precio
                articulo['Total'] = this.items[i].Total
                

                this.detallepedido.push(articulo);
                
                console.log(this.detallepedido)
            }
            }, 1000);
            
            let url2 = `https://nuestrocampo.cl/api/clientes/read.php`

            axios.get(url2).then((response) => (this.clientes = response.data));

        },
    },

    components: { VCalendar },
};
</script>
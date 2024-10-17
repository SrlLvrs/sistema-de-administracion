<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-warning" @click="leerDetalles(id)">
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
            <h3 class="text-lg font-bold mb-2 text-center">
                Editar pedido {{ id }}
            </h3>

            <!-- Loading Spinner, sólo aparece hasta que se rellene el array PRODUCTOS -->
            <div v-if="productos.length === 0">
                <span class="loading loading-spinner loading-md"></span>
            </div>

            <!-- Todo lo demás -->
            <div v-else>
                <!-- Estado -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Estado</span>
                    </div>
                    <select v-model="estado_actual" class="select select-bordered w-full max-w-xs">
                        <option disabled selected>
                            {{ this.items[0].Estado }}
                        </option>
                        <option v-for="estado in estados">{{ estado }}</option>
                    </select>
                </div>

                <!-- Pagado -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Pagado</span>
                    </div>
                    <select v-model="pagado" class="select select-bordered w-full max-w-xs">
                        <option disabled selected>
                            {{ this.items[0].Pagado }}
                        </option>
                        <option v-for="pagado in pagado_options">
                            {{ pagado }}
                        </option>
                    </select>
                </div>

                <!-- Medio de Pago -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Medio de Pago</span>
                    </div>
                    <select v-model="mediopago" class="select select-bordered w-full max-w-xs">
                        <option disabled selected>
                            {{ this.items[0].MedioPago }}
                        </option>
                        <option v-for="pago in mediopago_options">
                            {{ pago }}
                        </option>
                    </select>
                </div>

                <!-- Fecha de Entrega -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Fecha de Entrega</span>
                    </div>
                    <VCalendar v-model="fechaentrega" />
                </div>

                <!-- Todos los productos -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Productos</span>
                    </div>

                    <select v-model="producto_seleccionado" class="select select-bordered w-full max-w-xs">
                        <option v-for="(producto, index) in productos" :key="index" :value="index">
                            {{ producto.descripcion }} - ${{ producto.precio }}
                        </option>
                    </select>
                </div>

                <!-- Cantidad -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Cantidad</span>
                    </div>
                    <input v-model="cantidad" type="number" placeholder="1"
                        class="input input-bordered w-full max-w-xs" />
                    <label class="btn btn-outline btn-success m-1" @click="agregarProducto()">Añadir</label>
                </div>

                <!-- Detalles -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Productos actualmente en este pedido</span>
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
                                <tr v-for="(detalle, index) in detallepedido" :key="index" :value="index">
                                    <th>{{ detalle.Cantidad }}</th>
                                    <th>{{ detalle.Descripcion }}</th>
                                    <td>{{ detalle.Precio }}</td>
                                    <td>{{ detalle.Total }}</td>
                                    <!-- Botones -->
                                    <!-- Más uno -->
                                    <button class="btn btn-outline btn-success m-1" @click="masUno(index)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                    <!-- Menos uno -->
                                    <button class="btn btn-outline btn-warning m-1" @click="menosUno(index)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                        </svg>
                                    </button>
                                    <!-- Eliminar -->
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
                        <span class="label-text font-bold">Total: {{ suma }}</span>
                    </div>
                </div>

                <!-- Botones del modal -->
                <div class="modal-action">
                    <label :for="label" class="btn" @click="limpiar()">Salir</label>
                    <button class="btn btn-outline btn-warning" @click="editarPedido()">
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
            productos: [],
            id_cliente_actual: "",
            id_cliente_nuevo: "",
            estado_actual: "",
            estados: ["Agendado", "Entregado", "No Entregado"],
            pagado: "",
            pagado_options: ["Si", "No"],
            mediopago: "",
            mediopago_options: ["Efectivo", "Transferencia"],
            fechaentrega: "",
            detallepedido: [],
            suma: "",
            producto_seleccionado: "",
            cantidad: 1,
            id_repartidor: 1,
        };
    },

    methods: {
        //Sumar el total del pedido
        adicion() {
            let adicion = this.detallepedido.reduce((total, pedido) => {
                return total + parseInt(pedido.Total, 10);
            }, 0);
            this.suma = adicion;
        },
        //GET detalle del pedido en base al IDPEDIDO
        async leerDetalles(id) {
            let url = `https://nuestrocampo.cl/api/pedidos/read-detail.php?id=${id}`;
            await axios.get(url).then((response) => (this.items = response.data));

            //Base de datos => Variables locales
            this.id_cliente_actual = this.items[0].IDCliente;
            this.id_cliente_nuevo = this.items[0].Nombre;
            this.estado_actual = this.items[0].Estado;
            this.pagado = this.items[0].Pagado;
            this.mediopago = this.items[0].MedioPago;
            this.fechaentrega = this.items[0].FechaEntregaMySQL;

            //Rellenar array DETALLEPEDIDO
            for (let i = 0; i < this.items.length; i++) {
                let articulo = {};
                articulo["IDPedido"] = this.items[i].IDPedido;
                articulo["IDProducto"] = this.items[i].IDProducto;
                articulo["Descripcion"] = this.items[i].Descripcion;
                articulo["Cantidad"] = this.items[i].Cantidad;
                articulo["Precio"] = this.items[i].Precio;
                articulo["Total"] = this.items[i].Total;

                this.detallepedido.push(articulo);

                this.adicion();
            }

            //GET clientes
            let url2 = `https://nuestrocampo.cl/api/clientes/read.php`;
            axios.get(url2).then((response) => (this.clientes = response.data));

            //GET productos
            let url3 = `https://nuestrocampo.cl/api/productos/read.php`;
            axios
                .get(url3)
                .then((response) => (this.productos = response.data));
        },
        //Suma 1 a la cantidad de productos en el array DETALLEPEDIDO
        masUno(i) {
            this.detallepedido[i].Cantidad++;
            this.detallepedido[i].Total =
                this.detallepedido[i].Cantidad * this.detallepedido[i].Precio;
            this.adicion();

            let idp = this.detallepedido[i].IDPedido;
            let idpro = this.detallepedido[i].IDProducto;
            let c = this.detallepedido[i].Cantidad;
            let t = this.detallepedido[i].Total;

            let url = `https://nuestrocampo.cl/api/pedidos/update-detail.php?idpedido=${idp}&idproducto=${idpro}&cantidad=${c}&total=${t}`;
            axios.put(url).then(function (response) {
                console.log(response.data);
            });
        },
        //Resta 1 a la cantidad de productos en el array DETALLEPEDIDO
        menosUno(i) {
            this.detallepedido[i].Cantidad--;
            this.detallepedido[i].Total =
                this.detallepedido[i].Cantidad * this.detallepedido[i].Precio;
            if (this.detallepedido[i].Cantidad === 0) {
                this.borrarProducto(i);
            } else {
                let idp = this.detallepedido[i].IDPedido;
                let idpro = this.detallepedido[i].IDProducto;
                let c = this.detallepedido[i].Cantidad;
                let t = this.detallepedido[i].Total;

                let url = `https://nuestrocampo.cl/api/pedidos/update-detail.php?idpedido=${idp}&idproducto=${idpro}&cantidad=${c}&total=${t}`;
                axios.put(url).then(function (response) {
                    console.log(response.data);
                });
            }
            this.adicion();
        },
        //Borra el producto seleccionado del array DETALLEPEDIDO
        borrarProducto(i) {
            //Elimina el producto del detalle de la base de datos
            let idp = this.detallepedido[i].IDPedido;
            let c = this.detallepedido[i].Cantidad;

            let url = `https://nuestrocampo.cl/api/pedidos/delete-detail.php?id=${idp}&cantidad=${c}`;
            axios.delete(url).then(function (response) {
                console.log(response.data);
            });

            this.detallepedido.splice(i, 1);
        },
        //Agrega productos y cantidad al array DETALLEPEDIDOD
        agregarProducto() {
            let ps = this.producto_seleccionado;
            let articulo = {};
            articulo["IDPedido"] = this.items[0].IDPedido;
            articulo["IDProducto"] = this.productos[ps].id;
            articulo["Descripcion"] = this.productos[ps].descripcion;
            articulo["Cantidad"] = this.cantidad;
            articulo["Precio"] = this.productos[ps].precio;
            articulo["Total"] = this.cantidad * this.productos[ps].precio;

            this.detallepedido.push(articulo);

            //Sumar
            this.adicion();

            //Enviar el producto a la base de datos
            let idp = this.items[0].IDPedido;
            let idpro = this.productos[ps].id;
            let c = this.cantidad;
            let t = this.cantidad * this.productos[ps].precio;

            let url = `https://nuestrocampo.cl/api/pedidos/create-detail.php?id_pedido=${idp}&id_producto=${idpro}&cantidad=${c}&total=${t}`;
            axios.post(url).then(function (response) {
                console.log(response.data);
            });
        },
        //Guarda todos los cambios
        editarPedido() {
            //PUT pedidos
            let idc = "";

            if (this.id_cliente_actual === this.items[0].IDCliente) {
                // Si el cliente es el mismo
                idc = this.id_cliente_actual;
            } else {
                // Si el cliente es otro
                idc = this.id_cliente_nuevo;
            }

            let idp = this.items[0].IDPedido;
            let idr = this.id_repartidor;
            let e = this.estado_actual;
            let p = this.pagado;
            let mp = this.mediopago;
            let fe = this.formatearFecha(this.fechaentrega);

            let url = `https://nuestrocampo.cl/api/pedidos/update.php?id=${idp}&idcliente=${idc}&idrepartidor=${idr}&estado=${e}&pagado=${p}&mediopago=${mp}&fechaentrega=${fe}`;
            axios.put(url).then(function (response) {
                console.log(response.data);
            });
            location.reload();
        },
        //Limpia los arrays al salir
        limpiar() {
            this.detallepedido = []
        },
        formatearFecha(fecha) {
            if (!fecha) return '';
            const d = new Date(fecha);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');
            const seconds = String(d.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        },
    },

    components: { VCalendar },
};
</script>

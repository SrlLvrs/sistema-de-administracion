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

            <!-- Renderización condicional -->
            <div v-if="items.length === 0">
                <span class="loading loading-spinner loading-md"></span>
            </div>
            <div v-else>
                <!-- Pedidos previamente agendados -->
                <!-- Sólo aparece si el pedidos_agendados tiene elementos -->
                <div v-if="pedidos_agendados.length > 0" class="border-2 border-red-500 m-4 rounded-md">
                    <p class="mt-4 font-bold text-red-500 text-center">Este cliente tiene pedidos previamente agendados!
                    </p>
                    <p class="text-center">Revisa esta información y evita crear pedidos repetidos</p>
                    <!-- Detalle -->
                    <div>
                        <div class="label">
                            <span class="label-text font-bold mt-3">Pedidos previamente agendados</span>
                        </div>

                        <!-- Tabla -->
                        <div class="overflow-x-auto">
                            <table class="table">
                                <!-- head -->
                                <thead>
                                    <tr>
                                        <th>ID Pedido</th>
                                        <th>Estado</th>
                                        <th>Pagado</th>
                                        <th>Total del Pedido</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <!-- body -->
                                <tbody>
                                    <tr v-for="(pedido, index) in pedidos_agendados" :key="index" :value="index">
                                        <th>{{ pedido.IDPedido }}</th>
                                        <th>{{ pedido.Estado }}</th>
                                        <td>{{ pedido.Pagado }}</td>
                                        <td>{{ pedido.Total_Pedido }}</td>
                                        <td>
                                            <!-- Detalle Pedido -->
                                            <DetallePedidoMinimal :label="pedido.IDPedido + 'detail'"
                                                :id="pedido.IDPedido" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex justify-center mb-4">
                        <button class="btn" @click="descartar()">Descartar información y continuar con el
                            pedido</button>
                    </div>
                </div>

                <div v-for="item in items">
                    <!-- Cliente -->
                    <div>
                        <div class="label">
                            <span class="label-text font-bold">Cliente</span>
                        </div>
                        <p> {{ item.cliente }} - {{ item.direccion }}, {{ item.sector }}, {{ item.comuna }}</p>
                    </div>

                    <!-- Día de reparto -->
                    <div>
                        <div class="label">
                            <span class="label-text font-bold">Dia de reparto</span>
                        </div>
                        <p>{{ item.dia_reparto }}</p>
                    </div>

                    <!-- Fecha de entrega -->
                    <div class="mt-2">
                        <div class="label">
                            <span class="label-text font-bold">Fecha de entrega</span>
                        </div>
                        <VCalendar v-model="fecha_reparto_local"></VCalendar>
                    </div>
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

                <!-- Acciones -->
                <div class="modal-action">
                    <!-- Descartar pedido -->
                    <button class="btn btn-outline btn-error" @click="descartarPedido(items[0].id)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Descartar Pedido</button>

                    <!-- Crear pedido -->
                    <button class="btn btn-outline btn-success" @click="crearPedido_Producto()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Crear Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import VCalendar from "../components/VCalendar.vue";
import DetallePedidoMinimal from "../components/DetallesPedidoMinimal.vue";

export default {
    //Nombre del componente
    name: "CrearPedidoModal",

    props: {
        id: String,
        cliente: Number,
    },

    data() {
        return {
            items: [],
            productos: [],
            producto_seleccionado: '',
            productos_elegidos: [],
            pedidos_agendados: [],
            cantidad: 1,
            total: '',
            fecha_reparto_local: '',
            fecha_creacion_local: '',
        };
    },

    methods: {
        /** Sumar el total */
        adicion() {
            let totalSum = this.productos_elegidos.reduce((accumulator, currentItem) => {
                return accumulator + currentItem.total;
            }, 0);

            this.total = totalSum;
        },
        /** Formatear fecha para mysql */
        formatToMySQLDateTime(dateString) {
            // Crea un objeto Date a partir del Date String
            const date = new Date(dateString);

            // Extrae los componentes de la fecha
            const year = date.getFullYear(); // Año (YYYY)
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Mes (MM)
            const day = String(date.getDate()).padStart(2, '0'); // Día (DD)

            // Extrae los componentes de la hora
            const hours = String(date.getHours()).padStart(2, '0'); // Horas (HH)
            const minutes = String(date.getMinutes()).padStart(2, '0'); // Minutos (MM)

            // Retorna la fecha en formato compatible con MySQL DATETIME
            return `${year}-${month}-${day} ${hours}:${minutes}:00`;
        },
        /** Crea un pedido vacío con valores por defecto al inicializar el componente */
        crearPedido() {
            // Fecha de hoy para creación
            this.fecha_creacion_local = new Date();

            //POST nuevo pedido
            let idc = this.cliente;
            let h = this.fecha_creacion_local;
            let hmysql = this.formatToMySQLDateTime(h);
            let url = `https://nuestrocampo.cl/api/pedidos/create.php?id_cliente=${idc}&hora_creacion=${hmysql}&fecha_reparto=${hmysql}`
            axios.post(url).then(function (response) {
                console.log(response.data);
            });

            //GET último pedido
            let url2 = "https://nuestrocampo.cl/api/pedidos/read-last.php";
            setTimeout(() => {
                axios.get(url2).then((response) => (this.items = response.data));
            }, 1000);

            //GET todos los productos
            let url3 = "https://nuestrocampo.cl/api/productos/read.php";
            axios.get(url3).then((response) => (this.productos = response.data));

            //Get pedidos previamente agendados
            let url4 = `https://nuestrocampo.cl/api/clientes/read-previous-order.php?id=${idc}`;
            axios.get(url4).then((response) => (this.pedidos_agendados = response.data));

            this.fecha_reparto_local = new Date();
        },
        /** Copia los productos del array de todos los productos, a un array vacío */
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
                this.adicion();
            }
        },
        /** Elimina los productos de la tabla de productos seleccionados */
        borrarProducto(i) {
            this.productos_elegidos.splice(i, 1);

            //Sumar el total de todos los productos restantes.
            this.adicion();
        },
        /** Inserta los productos de la tabla en la base de datos y modifica la fecha */
        crearPedido_Producto() {
            //Editar campos en tabla PEDIDOS
            let id = this.items[0].id
            let idc = this.items[0].id_cliente
            let e = 'Agendado'
            let p = 'No'
            let m = 'No definido'
            let f = this.fecha_reparto_local
            let fmysql = this.formatToMySQLDateTime(f);

            let url = `https://nuestrocampo.cl/api/pedidos/update.php?id=${id}&idcliente=${idc}&estado=${e}&pagado=${p}&mediopago=${m}&fechaentrega=${fmysql}`
            axios.put(url).then(function (response) {
                console.log(response.data);
            });

            //Envía todos los productos del array, usando un ciclo for.
            let array = this.productos_elegidos;
            for (let i = 0; i < array.length; i++) {
                //Definir variables
                let pedido = array[i].id_pedido;
                let producto = array[i].id;
                let cantidad = array[i].cantidad;
                let total = array[i].total;

                let url = `https://nuestrocampo.cl/api/pedidos/create-detail.php?id_pedido=${pedido}&id_producto=${producto}&cantidad=${cantidad}&total=${total}`
                axios.post(url).then(function (response) {
                    console.log(response.data)
                })
                location.reload();
            }
        },
        /** BORRA el pedido vacío */
        descartarPedido(i) {
            let url = `https://nuestrocampo.cl/api/pedidos/delete-empty.php?id=${i}`
            axios.delete(url);
            location.reload();
        },
        /** Descartar información de pedidos anteriores */
        descartar() {
            this.pedidos_agendados = []
        }
    },

    components: { VCalendar, DetallePedidoMinimal },
}
</script>
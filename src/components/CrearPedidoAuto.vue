<template>
    <!-- Modal CREAR NUEVO PEDIDO-->
    <!-- Botón para abrir el modal -->
    <label :for="id" class="btn btn-outline btn-success mr-2" @click="crearPedido()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="id" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold text-center">Nuevo Pedido Automático</h3>
            <p>https://nuestrocampo.cl/api/pedidos/create-auto.php?id_cliente=1&id_producto=1&cantidad=2&ultimo_pedido=ayer&frecuencia=1%20vez%20al%20mes
            </p>
            {{ items }}

            <div>
                <div v-for="item in items">
                    <!-- Cliente -->
                    <div>
                        <div class="label">
                            <span class="label-text font-bold">Cliente</span>
                        </div>
                        <p> {{ item.Nombre }} - {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</p>
                    </div>

                    <!-- Día de reparto -->
                    <div>
                        <div class="label">
                            <span class="label-text font-bold">Dia de reparto</span>
                        </div>
                        <p>{{ item.Dia_de_Reparto }}</p>
                    </div>

                    <!-- Frecuencia -->
                    <div>
                        <div class="label">
                            <span class="label-text font-bold">Frecuencia</span>
                        </div>

                        <select v-model="frecuencia_seleccionada" class="select select-bordered w-full max-w-xs">
                            <option v-for="item in frecuencias">
                                {{ item }}
                            </option>
                        </select>
                    </div>

                    <!-- Fecha de entrega -->
                    <div class="mt-2">
                        <div class="label">
                            <span class="label-text font-bold">Fecha de entrega</span>
                        </div>
                        <p class="mb-2">Selecciona la fecha de la primera entrega. Las demás se calcularán en base a
                            ésta.</p>
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
                <div>
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

                {{ productos_elegidos }}

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
                    <button class="btn btn-outline btn-success" @click="crearPedido_Auto()">
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
    name: "CrearPedidoAutoModal",

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
            cantidad: 1,
            total: '',
            fecha_reparto_local: '',
            frecuencias: ['Cada 1 semana', 'Cada 2 semanas', 'Cada 3 semanas', 'Cada 4 semanas'],
            frecuencia_seleccionada: 'Cada 1 semana',
            ultimo_pedido: [],
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
        /** Crea un pedido vacío con valores por defecto al inicializar el componente */
        crearPedido() {
            //GET detalle cliente
            let idc = this.cliente
            let url = `https://nuestrocampo.cl/api/clientes/read-detail.php?id=${idc}`;
            axios.get(url).then((response) => (this.items = response.data));

            //GET todos los productos
            let url2 = "https://nuestrocampo.cl/api/productos/read.php";
            axios.get(url2).then((response) => (this.productos = response.data));

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
        /** DESDE ACA */
        // Método para esperar un tiempo especificado (en milisegundos)
        esperar(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        // Método que ejecuta getUltimoPedido después de esperar 1 segundo
        async ejecutarConEspera() {
            console.log("Antes de esperar");

            // Espera 1 segundo (1000 milisegundos)
            await this.esperar(5000);

            console.log("Después de esperar");
            // Llamar al método getUltimoPedido después de esperar
            this.getUltimoPedido();
        },

        // Tu método getUltimoPedido que deseas ejecutar después de la espera
        getUltimoPedido() {
            // Aquí va la lógica de tu método getUltimoPedido
            console.log("Llamando a getUltimoPedido...");
        },
        /* HASTA ACA */
        crearPedido_Auto() {
            let f = new Date(this.fecha_reparto_local)

            for (let i = 0; i < 4; i++) {
                //Añadir 4 pedidos de una vez
                this.crearTodoslosPedidos(f)

                //POST pedido_producto

                //Actualizar fecha
                f.setDate(f.getDate() + 7);
            }
        },
        crearTodoslosPedidos(fecha) {
            //POST nuevo pedido
            let idc = this.cliente;
            let h = new Date();
            let url = `https://nuestrocampo.cl/api/pedidos/create.php?id_cliente=${idc}&hora_creacion=${h}&fecha_reparto=${fecha}`
            axios.post(url).then(function (response) {
                console.log(response.data);
            });

            console.log('Pedido creado')
        },
        crearPedido_Autox() {
            //POST pedido_automático
            let array = this.productos_elegidos

            for (let i = 0; i < array.length; i++) {
                let idc = this.cliente
                let idp = array[i].id
                let c = array[i].cantidad
                let up = this.fecha_reparto_local
                let f = this.frecuencia_seleccionada

                let url = `https://nuestrocampo.cl/api/pedidos/create-auto.php?id_cliente=${idc}&id_producto=${idp}&cantidad=${c}&ultimo_pedido=${up}&frecuencia=${f}`
                axios.post(url).then(function (response) {
                    console.log(response.data);
                });
            }

            console.log('Pedido automático creado')
        },
        /** Inserta los productos de la tabla en la base de datos y modifica la fecha */
        crearPedido_Producto() {
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
        getUltimoPedido() {
            //GET último pedido
            let url = "https://nuestrocampo.cl/api/pedidos/read-last.php";
            axios.get(url).then((response) => (this.ultimo_pedido = response.data));
        },
        /** BORRA el pedido vacío */
        descartarPedido(i) {
            let url = `https://nuestrocampo.cl/api/pedidos/delete-empty.php?id=${i}`
            axios.delete(url);
            location.reload();
        },
    },

    components: { VCalendar, DetallePedidoMinimal },

    mounted() {
        this.ejecutarConEspera()
    }
}
</script>
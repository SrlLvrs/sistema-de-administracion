<template>
    <!-- Modal PROGRAMAR PEDIDOS-->
    <!-- Botón para abrir el modal -->
    <label for="programar" class="btn btn-outline btn-success mr-2" @click="prueba()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
        </svg>
        Crear todos los pedidos automáticos
    </label>

    <!-- Modal -->
    <input type="checkbox" id="programar" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold text-center">Creando pedidos automáticos</h3>

            <div>
                <div>
                </div>
                <!-- Progreso -->
                <div>
                    <div class="label">
                        <span class="label-text font-bold">Creando pedidos</span>
                    </div>
                    <progress class="progress w-56"></progress>
                    <p>
                        {{ fecha_ultimo_pedido }}
                    </p>
                    <p>
                        {{ freq }}
                    </p>
                    <p>
                        {{ items }}
                    </p>
                    <p>
                        {{ detalle_auto }}
                    </p>
                    <p v-for="i in detalle_auto">
                        {{  i.Nombre }}
                    </p>
                </div>

                <!-- Acciones -->
                <div class="modal-action">
                    <!-- Descartar pedido -->
                    <label class="btn" for="programar">Descartar Pedido Automático</label>

                    <!-- Crear pedido -->
                    <button class="btn btn-outline btn-success" @click="prueba()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Crear Pedido Automático
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import VCalendar from "../components/VCalendar.vue";

export default {
    //Nombre del componente
    name: "CrearPedidoAutoProgramar",

    data() {
        return {
            items: [],
            detalle_auto: [],
            OTROS: ['_______________________________________________________'],
            fecha_ultimo_pedido: '',
            freq: '',
            cliente: '',
            cantidad_pedidos: 0,
            productos: [],
            producto_seleccionado: '',
            productos_elegidos: [],
            cantidad: 1,
            total: '',
            fecha_reparto_local: '',
            frecuencias: ['Cada 1 semana', 'Cada 2 semanas', 'Cada 3 semanas', 'Cada 4 semanas'],
            frecuencia_seleccionada: 'Cada 1 semana',
            ultimo_pedido: [],
            ultimo_id: [{ "ID": "0" }],
        };
    },

    methods: {
        async prueba() {
            //El ciclo for debe hacerse por cada PA

            //1. GET pedidos_automaticos
            await this.getPedidosAuto()

            //2. GET detalle pedidos_automaticos
            let array = this.items
            for (let i = 0; i < array.length; i++) {
                console.log(i)
                this.getDetallePedidosAuto(array[i].ID)
            }

            //3. POST pedido base

            //4. POST detalle pedido

            //5. PUT nueva fecha UltimoPedido
        },
        /** 1. Obtiene todos los pedidos automáticos */
        async getPedidosAuto() {
            //GET pedidos automaticos
            let url = `https://nuestrocampo.cl/api/pedidos/read-auto.php`;
            await axios.get(url).then((response) => (this.items = response.data));
        },
        /** 2. Obtiene el detalle de todos los pedidos automáticos */
        async getDetallePedidosAuto(id) {
            //GET pedidos automaticos
            let url = `https://nuestrocampo.cl/api/pedidos/read-detail-auto.php?id=${id}`;
            await axios.get(url).then(response => {
                const data = response.data;

                data.forEach(elemento => {
                    this.detalle_auto.push(elemento);
                });
            })
        },
        /** Calcula la cantidad de pedidos que se crearán en base a cada frecuencia */
        calcularCantidadPedidos(d) {
            if (d === 7) {
                this.cantidad_pedidos = 4
            } else if (d === 14) {
                this.cantidad_pedidos = 2
            } else if (d === 21) {
                this.cantidad_pedidos = 2
            } else if (d === 28) {
                this.cantidad_pedidos = 1
            }
            console.log('los dias son ' + d + ' y la cantidad de pedidos es ' + this.cantidad_pedidos)
        },
        /** Crea los pedidos base */
        crearTodoslosPedidos(fecha, cliente) {
            //POST nuevo pedido
            let h = new Date();
            let url = `https://nuestrocampo.cl/api/pedidos/create.php?id_cliente=${cliente}&hora_creacion=${h}&fecha_reparto=${fecha}`
            axios.post(url).then(function (response) {
                console.log(response.data);
            });
        },
        /** Obtiene el último pedido */
        getUltimoPedido() {
            //GET último pedido
            let url = "https://nuestrocampo.cl/api/pedidos/read-last.php";
            axios.get(url).then((response) => (this.ultimo_pedido = response.data));
        },
        /** Inserta los productos de la tabla en la base de datos en la tabla intermedia PEDIDOS_PRODUCTOS*/
        crearDetalle(id_pedido) {
            //Envía todos los productos del array, usando un ciclo for.
            let array = this.productos_elegidos;
            for (let i = 0; i < array.length; i++) {
                //Definir variables
                let p = id_pedido;
                let pro = array[i].id;
                let c = array[i].cantidad;
                let t = array[i].total;

                let url = `https://nuestrocampo.cl/api/pedidos/create-detail.php?id_pedido=${p}&id_producto=${pro}&cantidad=${c}&total=${t}`
                axios.post(url).then(function (response) {
                    console.log(response.data)
                })
            }
        },
        /** Obtiene info del clientes, todos los productos y asigna la fecha de hoy a Vcalendar al iniciar componente */
        async crearPedidos() {
            //Obtener todos los pedidos automáticos
            this.getPedidosAuto()

            //esperar
            await this.esperar(1000)

            let array = this.items

            //Este for recorre todos los pedidos automáticos
            for (let i = 0; i < array.length; i++) {
                //Asignar ultimo_pedido y frecuencia a variables
                this.fecha_ultimo_pedido = array[i].UltimoPedido
                this.freq = array[i].Frecuencia
                this.cliente = array[i].IDCliente

                //Cantidad de dias que se sumarán al nuevo pedido
                let dias = this.intervalo

                //Calcular cuantos pedidos se crearán
                this.calcularCantidadPedidos(dias);

                //Crear pedidos
                for (let i = 0; i < this.cantidad_pedidos; i++) {
                    //Fecha del ultimo pedido
                    let f = new Date(this.fecha_ultimo_pedido)
                    f.setDate(f.getDate() + dias);

                    this.fecha_ultimo_pedido = f

                    let fup = this.fecha_ultimo_pedido
                    let idc = this.cliente

                    //Crear pedido, pasandole fecha y cliente
                    this.crearTodoslosPedidos(fup, idc)

                    await this.esperar(1000)

                    //get ultimo pedido
                    this.getUltimoPedido()

                    await this.esperar(1000)

                    //asignar id del ultimo pedido 
                    let idp = this.ultimo_pedido[0].id

                    //post pedidos_productos
                    this.crearDetalle(idp)




                }


            }
        },
        /** Crea un pedido automático, crea pedidos base, crea detalles de pedido y actualiza la fecha del último pedido  */
        async prueba2() {
            //1. POST pedidos_automaticos
            //Crea la info necesaria para crear nuevos pedidos en el futuro.
            this.crearPedido_Auto()
            this.progreso = this.progreso + 1
            console.log('Creando pedido automático')

            //2. POST pedidos
            //Crea los 4 pedidos, usando la fecha_reparto_local como base, luego suma x días en base a cada intervalo.
            let f = new Date(this.fecha_reparto_local)
            f.setDate(f.getDate() + dias);
            let dias = this.intervalo

            for (let i = 0; i < 4; i++) {
                //Post pedidos
                console.log('Creando pedido base ' + i)
                this.crearTodoslosPedidos(f)
                this.progreso = this.progreso + 1
                //esperar
                await this.esperar(1000)
                this.progreso = this.progreso + 1
                //get ultimo pedido
                this.getUltimoPedido()
                this.progreso = this.progreso + 1
                console.log('Obteniendo ultimo pedido')
                //esperar
                await this.esperar(1000)
                this.progreso = this.progreso + 1
                //asignar id ultimo pedido a id
                let idp = this.ultimo_pedido[0].id
                this.progreso = this.progreso + 1
                console.log('ID del último pedido: ' + idp)
                //post pedidos_productos
                this.crearDetalle(idp)
                this.progreso = this.progreso + 1
                console.log('Detalle creado')
                //esperar
                await this.esperar(1000)
                this.progreso = this.progreso + 1
                if (i <= 2) {
                    //Actualizar fecha
                    console.log('Actualizando variable de fecha...')
                    this.progreso = this.progreso + 1
                    f.setDate(f.getDate() + dias);
                }
                console.log('fin del ciclo')
                this.progreso = this.progreso + 1
            }

            //3. PUT pedidos_automaticos
            //Actualiza la fecha del último pedido
            console.log(f)
            this.actualizarFecha(f)
            this.progreso = this.progreso + 1

            //esperar
            await this.esperar(1000)

            //Recargar
            location.reload()
        },
        /** Obtiene el id del último pedido automático y actualiza el campo UltimoPedido  */
        async actualizarFecha(fecha) {
            //GET ultimo id de pedidos automaticos
            let url = `https://nuestrocampo.cl/api/pedidos/read-last-auto.php`
            axios.get(url).then((response) => (this.ultimo_id = response.data));
            console.log('Obteniendo último ID de pedido')
            //Esperar
            await this.esperar(1000)
            let last = this.ultimo_id[0].ID
            //Actualizar por fecha
            let url2 = `https://nuestrocampo.cl/api/pedidos/update-auto-date.php?id_pedido=${last}&ultimo_pedido=${fecha}`
            axios.put(url2).then(function (response) {
                console.log(response.data);
            });
            console.log('Actualizada fecha del último pedido')
        },
    },

    components: { VCalendar },

    computed: {
        intervalo() {
            switch (this.freq) {
                case "Cada 1 semana":
                    return 7; // 7 días
                case "Cada 2 semanas":
                    return 14; // 14 días
                case "Cada 3 semanas":
                    return 21; // 21 días
                case "Cada 4 semanas":
                    return 28; // 28 días
                default:
                    return null; // Valor por defecto si no coincide con ningún caso
            }
        }
    },
}
</script>
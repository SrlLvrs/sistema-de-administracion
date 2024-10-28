<template>
    <!-- Modal PROGRAMAR PEDIDOS-->
    <!-- Botón para abrir el modal -->
    <label for="programar" class="btn btn-outline btn-success mr-2" @click="exec()">
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
                        <span class="label-text font-bold">Creando pedido {{ ciclo_pedido }} para {{ nombre }}</span>
                        {{ observaciones }}
                    </div>
                    <progress class="progress w-56"></progress>
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
            freq: '',
            cantidad_pedidos: '',
            fecha_ultimo_pedido: '',
            cliente: '',
            last_idpa: '',
            last_idp: null,
            nombre: '',
            ciclo_pedido: '',
            observaciones: '', // Añadir esta línea
        };
    },

    methods: {
        //Función principal
        async exec() {
            await this.getPA()
            let arrayPA = this.items

            //Ciclo PA
            for (let i = 0; i < arrayPA.length; i++) {
                //Obtener frecuencia
                this.freq = arrayPA[i].Frecuencia
                //Obtener cliente
                this.cliente = arrayPA[i].IDCliente
                //Obtener observaciones
                this.observaciones = arrayPA[i].Observacion
                //Obtener IDPA
                this.last_idpa = arrayPA[i].ID
                //fecha de ultimopedido de mysql a Date()
                let f = this.convertirFecha(arrayPA[i].UltimoPedido)
                let dias = this.intervalo
                //calcular pedidos
                this.calcularCantidadPedidos(dias)

                this.nombre = arrayPA[i].Nombre

                //Ciclo crear pedidos
                for (let j = 0; j < this.cantidad_pedidos; j++) {
                    //Numero del pedido
                    this.ciclo_pedido = j + 1

                    //Sumar dias para el siguiente pedido
                    f.setDate(f.getDate() + dias);

                    //Formatea la fecha para mysql
                    let fmysql = this.formatToMySQLDateTime(f)

                    //crear pedido
                    await this.postP(fmysql)

                    //en este punto ya existe el IDP y el IDPA

                    //ObtenerPAP
                    await this.getPAP(this.last_idpa)

                    //Crear detalles (for interno)
                    await this.postPP(this.last_idp)

                    //Actualizar fecha
                    await this.actualizarFecha(fmysql)

                    //Reiniciar detalle auto
                    this.detalle_auto = []
                }
            }
            location.reload()
        },
        /** 1. Obtiene todos los pedidos automáticos */
        async getPA() {
            //GET pedidos automaticos
            let url = `https://nuestrocampo.cl/demo/pedidos/read-auto.php`;
            await axios.get(url).then((response) => {
                this.items = response.data;
                // Añadir estas líneas
                if (this.items.length > 0) {
                    this.observaciones = this.items[0].Observaciones || '';
                }
            });
        },
        /** 2. Crea los pedidos base */
        async postP(fecha) {
            //POST nuevo pedido
            let idc = this.cliente;
            let h = new Date();
            let hsql = this.formatToMySQLDateTime(h)
            let idpa = this.last_idpa;
            let estado = this.observaciones ? 'Pendiente' : 'Agendado'; // Añadir esta línea
            let url = `https://nuestrocampo.cl/demo/pedidos/create-w-idpa.php?id_cliente=${idc}&hora_creacion=${hsql}&fecha_reparto=${fecha}&idpa=${idpa}&estado=${estado}&observacion=${this.observaciones}` // Modificar esta línea
            await axios.post(url).then(response => {
                console.log(response.data);
                this.last_idp = response.data.id
                console.log('el ultimo idp es ' + this.last_idp)
            });
        },
        /** 3. Obtiene el detalle de todos los pedidos automáticos */
        async getPAP(id) {
            //GET pedidos automaticos
            let url = `https://nuestrocampo.cl/demo/pedidos/read-detail-auto.php?id=${id}`;
            await axios.get(url).then(response => {
                const data = response.data;

                data.forEach(elemento => {
                    this.detalle_auto.push(elemento);
                });
            })
        },
        /** 4. Crea el detalle de los pedidos */
        async postPP(idpedido) {
            let arrayPAP = this.detalle_auto
            for (let k = 0; k < arrayPAP.length; k++) {
                let p = idpedido;
                let pro = arrayPAP[k].IDProducto;
                let c = arrayPAP[k].Cantidad;
                let t = arrayPAP[k].Total

                let url = `https://nuestrocampo.cl/demo/pedidos/create-detail.php?id_pedido=${p}&id_producto=${pro}&cantidad=${c}&total=${t}`
                await axios.post(url).then(function (response) {
                    console.log(response.data)
                })
            }
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
        /** Convertir a objeto Date */
        convertirFecha(fechaStr) {
            const [dd, mm, yyyy] = fechaStr.split('/');
            return new Date(`${yyyy}-${mm}-${dd}T00:00:00`);  // Devuelve el objeto Date
        },
        /** Actualiza el campo UltimoPedido  */
        async actualizarFecha(fecha) {
            //Actualizar por fecha
            let idpa = this.last_idpa
            let url = `https://nuestrocampo.cl/demo/pedidos/update-auto-date.php?id=${idpa}&fecha=${fecha}`
            axios.put(url).then(function (response) {
                console.log(response.data);
            });
            console.log('Actualizada fecha del último pedido')
        },
    },

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

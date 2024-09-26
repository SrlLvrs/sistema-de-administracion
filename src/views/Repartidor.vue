<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Todos los pedidos pendientes de hoy</h1>
            <div class="flex justify-center mb-4">
                <!-- INPUT FILTRAR -->
                <label class="input input-bordered flex items-center gap-2 mx-2 w-full max-w-xs">
                    <input v-model="filterText" type="text" class="grow" placeholder="Cliente, dirección o ID de pedido" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="h-4 w-4 opacity-70">
                        <path fill-rule="evenodd"
                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </label>

                <!-- Botón Excel -->
                <Excel :items="filteredItems" />
            </div>
        </div>
    </div>
    <!-- RESULTADOS -->
    <div v-if="items.length > 0" class="overflow-x-auto">
        <table class="table">
            <!-- Encabezado -->
            <thead>
                <tr>
                    <th></th>
                    <th>Número de pedido</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Pagado</th>
                    <th>Medio de Pago</th>
                    <th>Fecha de Entrega</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es FILTEREDITEMS, que es una función que toma el JSON y lo convierte en array, para poder filtrarlo -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="item in filteredItems" :key="item.ID">
                    <th>
                        <div v-if="item.IDPA" class="tooltip tooltip-right" data-tip="Pedido creado automáticamente">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </div>
                    </th>
                    <th> {{ item.ID }}</th>
                    <th> {{ item.Nombre }}</th>
                    <td> {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</td>
                    <td> {{ item.Estado }}</td>
                    <td> {{ item.Pagado }}</td>
                    <td> {{ item.MedioPago }}</td>
                    <td> {{ item.FechaEntrega }}</td>
                    <td>
                        <!-- Detalle Pedido -->
                        <EntregarPedido :label="item.ID + 'deliver'" :id="item.ID" />
                        <DetallePedido :label="item.ID + 'detail'" :id="item.ID" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-else class="flex justify-center items-center h-screen">
        <p class="text-center">No hay pedidos asignados a ti, o no eres repartidor. Si crees que es un error, comunícate con el administrador.</p>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import EliminarPedido from "../components/EliminarPedido.vue";
import DetallePedido from "../components/DetallesPedido.vue";
import EditarPedido from "../components/EditarPedido.vue";
import Excel from "../components/Excel.vue";
import EntregarPedido from "../components/EntregarPedido.vue";

export default {
    //Nombre del componente
    name: "Pedidos",

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
            filterText: '',
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        }
    },

    computed: {
        //Esta función filtra el array en base a la ID o el Nombre del cliente
        filteredItems() {
            const normalizeText = (text) => {
                return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            };

            const filterTextNormalized = normalizeText(this.filterText);

            return this.items.filter(item => {
                const matchesText =
                    !filterTextNormalized ||
                    normalizeText(item.ID).includes(filterTextNormalized) ||
                    normalizeText(item.Nombre).includes(filterTextNormalized) ||
                    normalizeText(item.Direccion).includes(filterTextNormalized) ||
                    normalizeText(item.NombreSector).includes(filterTextNormalized) ||
                    normalizeText(item.Comuna).includes(filterTextNormalized);

                // Devuelve los elementos que coincidan con todos los filtros activos
                return matchesText;
            });
        },
    },

    mounted() {
        const sessionData = this.checkUserSession();
        if (sessionData) {
            console.log('Sesión iniciada');
            this.id_user = sessionData.id;
            if (sessionData.rol === 'Repartidor') {
                let url = `https://nuestrocampo.cl/api/pedidos/read-today-deliver.php?id=${this.id_user}`;
                axios.get(url).then((response) => (this.items = response.data));
            } else {
                console.log('El usuario no es repartidor');
            }
        } else {
            console.log('No hay sesión iniciada');
            this.$router.push({ name: 'LogIn' });
        }
    },

    components: { EliminarPedido, DetallePedido, EditarPedido, Excel, EntregarPedido },
}
</script>
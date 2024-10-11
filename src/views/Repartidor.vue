<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Todos los pedidos pendientes de hoy</h1>
            <div class="flex justify-center mb-4">
                <!-- INPUT FILTRAR -->
                <label class="input input-bordered flex items-center gap-2 mx-2 w-full max-w-xs">
                    <input v-model="filterText" type="text" class="grow"
                        placeholder="Cliente, dirección o ID de pedido" />
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
        <table class="table table-zebra">
            <!-- Encabezado -->
            <thead>
                <tr>
                    <th></th>
                    <th>Número de pedido</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Teléfono 1</th>
                    <th>Teléfono 2</th>
                    <th>Pagado</th>
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
                    <td>
                        <div class="flex gap-2 items-center">
                            <span>{{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</span>
                            <a :href="item.LinkMaps" target="_blank" class="btn btn-outline btn-error">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="flex gap-2 items-center">
                            <span>{{ item.Telefono }}</span>
                            <a :href="'tel:' + item.Telefono" class="btn btn-outline btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </a>
                            <a :href="'https://wa.me/' + item.Telefono" target="_blank"
                                class="btn btn-outline btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="flex gap-2 items-center">
                            <span>{{ item.Telefono2 }}</span>
                            <a :href="'tel:' + item.Telefono2" class="btn btn-outline btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </a>
                            <a :href="'https://wa.me/' + item.Telefono2" target="_blank"
                                class="btn btn-outline btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                    <td> {{ item.Pagado }}</td>
                    <td>
                        <!-- Acciones  -->
                        <EntregarPedido :label="item.ID + 'deliver'" :id="item.ID" />
                        <DetallePedido :label="item.ID + 'detail'" :id="item.ID" />
                        <EditarPedido :label="item.ID + 'edit'" :id="item.ID" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-else class="flex justify-center items-center h-screen">
        <p class="text-center">No hay pedidos asignados a ti, o no eres repartidor. Si crees que es un error, comunícate
            con el administrador.</p>
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

    components: { EliminarPedido, DetallePedido, EditarPedido, Excel, EntregarPedido},
}
</script>
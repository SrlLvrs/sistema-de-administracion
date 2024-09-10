<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Todos los pedidos automáticos</h1>
            <div class="flex justify-center mb-4">
                <!-- INPUT FILTRAR -->
                <label class="input input-bordered flex items-center gap-2 mr-2">
                    <input v-model="filterText" type="text" class="grow" placeholder="Cliente o direccion" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="h-4 w-4 opacity-70">
                        <path fill-rule="evenodd"
                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </label>
                <CrearPedidoAutoProgramar />
            </div>
        </div>
    </div>
    <!-- RESULTADOS -->
    <div class="overflow-x-auto">
        <table class="table">
            <!-- Encabezado -->
            <thead>
                <tr>
                    <th>Número de pedido automático</th>
                    <th>Cliente</th>
                    <th>Direccion</th>
                    <th>Frecuencia</th>
                    <th>Último Pedido Agendado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es FILTEREDITEMS, que es una función que toma el JSON y lo convierte en array, para poder filtrarlo -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="item in filteredItems" :key="item.id">
                    <th> {{ item.ID }}</th>
                    <th> {{ item.Nombre }}</th>
                    <td> {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</td>
                    <td> {{ item.Frecuencia }}</td>
                    <td> {{ item.UltimoPedido }}</td>
                    <td>
                        <!-- Detalle Pedido -->
                        <DetallePedido :label="item.ID + 'detail'" :id="item.ID" />

                        <!-- Editar Pedido -->
                        <EditarPedido :label="item.id + 'edit'" :id="item.id" />

                        <!-- Eliminar Pedido -->
                        <EliminarPedido :id="item.ID" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import CrearPedidoAutoProgramar from "../components/CrearPedidoAutoProgramar.vue"
import EliminarPedido from "../components/EliminarPedidoAuto.vue";
import DetallePedido from "../components/DetallesPedidoAutoMinimal.vue";
import EditarPedido from "../components/EditarPedido.vue";

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
        //Nada por aquí...
    },

    computed: {
        //Esta función filtra el array en base a el NOMBRE o la DIRECCION del cliente
        filteredItems() {
            const normalizeText = (text) => {
                return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            };

            const filterTextNormalized = normalizeText(this.filterText);

            return this.items.filter(item => {
                const matchesText =
                    !filterTextNormalized ||
                    normalizeText(item.Nombre).includes(filterTextNormalized) ||
                    normalizeText(item.NombreSector).includes(filterTextNormalized) ||
                    normalizeText(item.Comuna).includes(filterTextNormalized) ||
                    normalizeText(item.Direccion).includes(filterTextNormalized);

                // Devuelve los elementos que coincidan con todos los filtros activos
                return matchesText;
            });
        },
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/api/pedidos/read-auto.php";

        axios.get(url).then((response) => (this.items = response.data));
    },

    components: { EliminarPedido, DetallePedido, EditarPedido, CrearPedidoAutoProgramar },
}
</script>
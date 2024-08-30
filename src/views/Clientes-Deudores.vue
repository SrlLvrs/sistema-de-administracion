<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Clientes Deudores</h1>
            <div class="flex justify-center">
                <!-- INPUT FILTRAR -->
                <label class="input input-bordered flex items-center gap-2">
                    <input v-model="filterText" type="text" class="grow" placeholder="Nombre o dirección..." />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="h-4 w-4 opacity-70">
                        <path fill-rule="evenodd"
                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </label>
            </div>
        </div>
    </div>
    <!-- RESULTADOS -->
    <div class="overflow-x-auto">
        <table class="table">
            <!-- Head -->
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Deuda Total</th>
                    <th>Teléfono</th>
                    <th>Teléfono 2</th>
                    <th>Frecuencia</th>
                    <th>Día de reparto</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es ITEMS, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="item in filteredItems" :key="item.id">
                    <th> {{ item.nombre }}</th>
                    <td> {{ item.direccion }}, {{ item.sector }}, {{ item.comuna }}</td>
                    <td> {{ item.deuda }}</td>
                    <td> {{ item.telefono }}</td>
                    <td> {{ item.telefono2 }}</td>
                    <td> {{ item.frecuencia }}</td>
                    <td> {{ item.reparto }}</td>
                    <td> {{ item.observacion }}</td>
                    <td>
                        <!-- Detalle cliente -->
                         <DetalleCliente 
                         :id="item.id"
                         :label="item.id + 'detail'"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import DetalleCliente from "../components/DetalleClienteDeudor.vue"


export default {
    //Nombre del componente
    name: "Clientes-Deudores",

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
            filterText: '',
        };
    },

    methods: {
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
                    normalizeText(item.nombre).includes(filterTextNormalized) ||
                    normalizeText(item.direccion).includes(filterTextNormalized) ||
                    normalizeText(item.sector).includes(filterTextNormalized) ||
                    normalizeText(item.comuna).includes(filterTextNormalized);

                // Devuelve los elementos que coincidan con todos los filtros activos
                return matchesText;
            });
        }
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/api/clientes/read-debt.php";

        axios.get(url).then((response) => (this.items = response.data));
    },

    components: { DetalleCliente },
}
</script>
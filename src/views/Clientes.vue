<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Clientes</h1>
            <div class="flex justify-center">
                <!-- Botón CREAR NUEVO CLIENTE -->
                <CrearCliente />

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

                <!-- SELECT Filtro de días -->
                <select v-model="selectedDay" class="select select-bordered max-w-xs ml-2">
                    <option value="">Filtrar por día</option>
                    <option v-for="dia in dias"> {{ dia }} </option>
                </select>

                <!-- SELECT Filtro de Suscripción -->
                <select v-model="selectedSus" class="select select-bordered max-w-xs ml-2">
                    <option value="">Filtrar por suscripción</option>
                    <option v-for="suscripcion in suscripciones"> {{ suscripcion }} </option>
                </select>
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
                    <th>Teléfono</th>
                    <th>Teléfono 2</th>
                    <th>Frecuencia</th>
                    <th>Día de reparto</th>
                    <th>Observaciones</th>
                    <!-- 
                        <th>Preferencias</th>
                    -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es ITEMS, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="item in filteredItems" :key="item.ID">
                    <th> {{ item.Nombre }}</th>
                    <td> {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</td>
                    <td> {{ item.Telefono }}</td>
                    <td> {{ item.Telefono2 }}</td>
                    <td> {{ item.Freq }}</td>
                    <td> {{ item.diaDeReparto }}</td>
                    <td> {{ item.Observacion }}</td>
                    <!-- 
                    <td> {{ item.preferencia }}</td>
                    -->
                    <td>
                        <!-- Crear Pedido -->
                        <CrearPedido :id="item.ID + 'pdd'" :cliente="item.ID" />
                        <!-- Crear Pedido Auto -->
                        <CrearPedidoAuto :id="item.ID + 'auto'" :cliente="item.ID" />
                        <!-- BOTÓN MAPS -->
                        <button class="btn btn-outline btn-info mr-2">
                            <a :href="item.LinkMaps" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                            </a>
                        </button>

                        <!-- Editar Cliente -->
                        <EditarCliente :id="item.ID" :nombre="item.Nombre" :comuna="item.Comuna"
                            :sector="item.NombreSector" :id_sector="item.IDSector" :dia="item.diaDeReparto"
                            :direccion="item.Direccion" :telefono="item.Telefono" :telefono2="item.Telefono2"
                            :linkmaps="item.LinkMaps" :frecuencia="item.freq" :observaciones="item.Observacion"
                            producto_preferido="no" id_producto_preferido="noid" />

                        <!-- Eliminar Cliente -->
                        <EliminarCliente :id="item.ID + 'eliminar'" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import CrearCliente from "../components/CrearCliente.vue";
import EliminarCliente from "../components/EliminarCliente.vue";
import EditarCliente from "../components/EditarCliente.vue";
import CrearPedido from "../components/CrearPedido.vue";
import CrearPedidoAuto from "../components/CrearPedidoAuto.vue";

export default {
    //Nombre del componente
    name: "Clientes",

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
            dias: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
            suscripciones: ['Ninguna', 'Cada 1 semana', 'Cada 2 semanas', 'Cada 3 semanas', 'Cada 4 semanas'],
            filterText: '',
            selectedDay: '',
            selectedSus: '',
        };
    },

    methods: {
    },

    computed: {
        filteredItems() {
            const normalizeText = (text) => {
                return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            };

            const filterTextNormalized = normalizeText(this.filterText);
            const selectedDayNormalized = normalizeText(this.selectedDay);
            const selectedSusNormalized = normalizeText(this.selectedSus);

            return this.items.filter(item => {
                const matchesText =
                    !filterTextNormalized ||
                    normalizeText(item.Nombre).includes(filterTextNormalized) ||
                    normalizeText(item.Direccion).includes(filterTextNormalized) ||
                    normalizeText(item.NombreSector).includes(filterTextNormalized) ||
                    normalizeText(item.Comuna).includes(filterTextNormalized);

                const matchesDay =
                    !selectedDayNormalized ||
                    normalizeText(item.diaDeReparto) === selectedDayNormalized;

                const matchesSus =
                    !selectedSusNormalized ||
                    normalizeText(item.Freq) === selectedSusNormalized;

                // Devuelve los elementos que coincidan con todos los filtros activos
                return matchesText && matchesDay && matchesSus;
            });
        }
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/api/clientes/read.php";

        axios.get(url).then((response) => (this.items = response.data));
    },

    components: { CrearCliente, EliminarCliente, EditarCliente, CrearPedido, CrearPedidoAuto },
}
</script>
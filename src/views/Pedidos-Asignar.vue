<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Asignar los pedidos para hoy</h1>
            <div class="flex justify-center">
                <!-- INPUT FILTRAR -->
                <label class="input input-bordered flex items-center gap-2 ml-2">
                    <input v-model="filterText" type="text" class="grow" placeholder="Sector o comuna..." />
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
        <table class="table w-11/12">
            <!-- head -->
            <thead>
                <tr>
                    <th>Nombre del Sector</th>
                    <th>Comuna</th>
                    <th>Repartidor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es SECTORES, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR 
                -->
                <tr v-for="item in filteredItems" :key="item.id">
                    <td> {{ item.NombreSector }}</td>
                    <td> {{ item.Comuna }}</td>
                    <td> {{ item.Repartidor }}</td>
                    <td>
                        <!-- Botón ASIGNAR REPARTIDOR -->
                        <AsignarRepartidor :id="item.IDSector" :label="item.IDSector + 'label'" />                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import AsignarRepartidor from '../components/AsignarRepartidor.vue'


export default {
    name: "Sectores",

    data() {
        return {
            items: [],
            filterText: '',
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
        async getSectores() {
            let url = "https://nuestrocampo.cl/api/pedidos/read-asign.php";
            await axios.get(url).then((response) => (this.items = response.data));
        }
    },

    computed: {
        filteredItems() {
            // Normaliza el texto de búsqueda y los campos de los clientes
            const normalizeText = (text) => {
                return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            };

            // Obtiene el texto de búsqueda normalizado
            const filterTextNormalized = normalizeText(this.filterText);

            // Filtra los elementos de la lista de sectores según los criterios de filtrado
            return this.items.filter((item) => {
                const matchesText =
                    !filterTextNormalized ||
                    normalizeText(item.NombreSector).includes(filterTextNormalized) ||
                    normalizeText(item.Comuna).includes(filterTextNormalized);

                // Devuelve los elementos que cumplen con todos los criterios de filtrado
                return matchesText;
            });
        },
    },

    components: { AsignarRepartidor },

    mounted() {
        const sessionData = this.checkUserSession();
        if (sessionData) {
            console.log('Sesión iniciada. Montando...');
            this.getSectores()
        } else {
            console.log('No hay sesión iniciada. Redireccionando a login');
            this.$router.push({ name: 'LogIn' });
        }
    },
};
</script>
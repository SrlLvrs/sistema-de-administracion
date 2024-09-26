<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Sectores de reparto</h1>
            <div class="flex justify-center">
                <!-- Botón CREAR NUEVO SECTOR -->
                <crearSectorModal />

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
                    <th>Día de reparto</th>
                    <th>Orden de Reparto</th>
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
                    <td> {{ item.nombreSector }}</td>
                    <td> {{ item.comuna }}</td>
                    <td> {{ item.diaReparto }}</td>
                    <td> {{ item.orden }}</td>
                    <td>
                        <!-- Botón EDITAR SECTOR -->
                        <editarSectorModal 
                            :id="item.id" 
                            :nombreSector="item.nombreSector" 
                            :comuna="item.comuna" 
                            :diareparto="item.diaReparto" 
                            :orden="item.orden" />
                        <!-- Botón BORRAR SECTOR -->
                        <eliminarSectorModal :id="item.id" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import eliminarSectorModal from "../components/EliminarSector.vue";
import crearSectorModal from "../components/CrearSector.vue";
import editarSectorModal from "../components/EditarSector.vue";

export default {
    //Nombre del componente
    name: "Sectores",

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
        },
        async getSectores() {
            let url = "https://nuestrocampo.cl/api/sectores/read.php";
            await axios.get(url).then((response) => (this.items = response.data));
        }
    },

    computed: {
        //Esta función filtra el array en base a el NOMBRE o la DIRECCION del cliente
        filteredItems() {
            return this.items.filter(item => {
                return item.nombreSector.toLowerCase().includes(this.filterText.toLowerCase()) || //esta linea filtra texto
                    item.comuna.toLowerCase().includes(this.filterText.toLowerCase());
                //item.edad.toString().includes(this.filterText); //esta linea filtra INTs
                //hay que añadir una de estas 2 tipos de lineas, para cada una de las columnas a filtrar

            });
        }
    },

    components: { eliminarSectorModal, crearSectorModal, editarSectorModal },

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
<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Sectores de reparto</h1>
            <div class="flex justify-center">
                <!-- Botón CREAR NUEVO SECTOR -->
                <crearSectorModal />
            </div>
        </div>
    </div>
    <!-- RESULTADOS -->
    <div class="overflow-x-auto">
        <table class="table w-11/12">
            <!-- head -->
            <thead>
                <tr>
                    <!-- 
                        <th>index</th>
                        <th>ID</th>
                        -->
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
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="(sector, index) in sectores" :key="sector.id">
                    <!-- 
                        <th> {{ index }}</th>
                        <td> {{ sector.id }}</td>
                        -->
                    <td> {{ sector.nombreSector }}</td>
                    <td> {{ sector.comuna }}</td>
                    <td> {{ sector.diaReparto }}</td>
                    <td> {{ sector.orden }}</td>
                    <td>
                        <!-- Botón EDITAR SECTOR -->
                        <editarSectorModal 
                        :id="sector.id" 
                        :nombreSector="sector.nombreSector" 
                        :comuna="sector.comuna" 
                        :diareparto="sector.diaReparto" 
                        :orden="sector.orden" />
                        <!-- Botón BORRAR SECTOR -->
                        <eliminarSectorModal :id="sector.id" />
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
            sectores: [],
        };
    },

    components: { eliminarSectorModal, crearSectorModal, editarSectorModal },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/demo/sectores/read.php";

        axios.get(url).then((response) => (this.sectores = response.data));
    },
};
</script>
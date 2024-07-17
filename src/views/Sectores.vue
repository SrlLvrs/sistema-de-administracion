<template>
    <!-- ALERTA -->
    <div role="alert" class="alert alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Vista de sectores</span>
    </div>

    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Sectores de reparto</h1>
            <div class="flex justify-center">
                <button class="btn btn-outline btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    Crear nuevo sector
                </button>
            </div>
        </div>
    </div>
    <!-- RESULTADOS -->
    <div class="overflow-x-auto">
        <table class="table">
            <!-- head -->
            <thead>
                <tr>
                    <th>index</th>
                    <th>ID</th>
                    <th>Nombre del Sector</th>
                    <th>Comuna</th>
                    <th>Día de reparto</th>
                    <th>Orden de Reparto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es SECTORES, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="(sector, index) in sectores" :key="sector.id">
                    <th> {{ index }}</th>
                    <td> {{ sector.id }}</td>
                    <td> {{ sector.nombreSector }}</td>
                    <td> {{ sector.comuna }}</td>
                    <td> {{ sector.diaReparto }}</td>
                    <td> {{ sector.orden }}</td>
                    <td>
                        <button class="btn btn-outline btn-warning mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>

                            Editar Sector</button>
                        <button class="btn btn-outline btn-error mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                            Eliminar Sector</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    //Nombre del componente
    name: "PostsList",

    data() {
        return {
            //Array para guardar datos de la API
            sectores: [],
        };
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint en archivo .env
        let url = "https://nuestrocampo.cl/api/sectores/read.php";

        axios.get(url).then((response) => (this.sectores = response.data));
    },
};
</script>
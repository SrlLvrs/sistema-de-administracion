<template>
    <!-- ALERTA -->
    <div role="alert" class="alert alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Vista de sectores</span>
    </div>

    <!-- GET -->
     <!-- el nombre del array es SECTORES, que debe ser el mismo que se define en DATA() RETURN -->
    <div v-for="(sector, index) in sectores" :key="sector.id">
        <p> {{ index }} </p>
        <p> {{ sector.id }} </p>
        <p> {{ sector.nombreSector }} </p>
        <p> {{ sector.diaReparto }} </p>
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
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/api/sectores/read.php";

        axios.get(url).then((response) => (this.sectores = response.data));
    },
};
</script>
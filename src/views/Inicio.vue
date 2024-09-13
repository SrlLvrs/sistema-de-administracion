<template>
    <!-- Estadísticas -->
    <div class="stats shadow">
        <div class="stat" v-for="item in items">
            <div class="stat-title">{{ item.Nombre }}</div>
            <div class="stat-value">{{ item.Valor }}</div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    //Nombre del componente
    name: "Inicio",

    data() {
        return {
            items: [],
        };
    },

    methods: {
        //Nada por aqui...
    },

    computed: {
        //Nada por acá...
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        let url = "https://nuestrocampo.cl/api/inicio/read.php";
        axios.get(url).then((response) => (this.items = response.data));
    },
}
</script>
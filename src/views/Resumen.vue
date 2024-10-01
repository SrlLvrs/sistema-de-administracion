<template>
    <h1>Resumen diario</h1>
    <div id="items" v-if="items.length === 0">
        <p>
            No hay información para hoy. Necesita despachar al menos un repartidor.
        </p>
        <DespacharRepartidor id="1" label="despachar" />
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import DespacharRepartidor from "../components/DespacharRepartidor.vue";

export default {
    //Nombre del componente
    name: "Resumen",

    data() {
        return {
            items: [],
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
    },

    computed: {
        //Nada por aqui
    },

    mounted() {
        const sessionData = this.checkUserSession();
        if (sessionData) {
            console.log('Sesión iniciada. Montando...');
            
        } else {
            console.log('No hay sesión iniciada. Redireccionando a login');
            this.$router.push({ name: 'LogIn' });
        }
    },

    components: { DespacharRepartidor }
};
</script>
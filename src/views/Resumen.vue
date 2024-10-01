<template>
    <div class="prose max-w-none text-center p-4">
        <h1>Resumen diario</h1>
        <div id="items" v-if="items.length === 0">
            <p>
                No hay información para hoy. Necesita despachar al menos un repartidor.
            </p>
            <DespacharRepartidor id="1" label="despachar" />
        </div>
        <div v-else>
            {{ repartidores }}
            <div v-for="(repartidor, index) in repartidores" :key="index">
                <h2>Repartidor {{ repartidor.Username }}</h2>
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Descripcion</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.filter(i => i.IDRepartidor === repartidor.IDRepartidor)" :key="item.ID">
                            <td>{{ item.Fecha }}</td>
                            <td>{{ item.Descripcion }}</td>
                            <td>{{ item.Cantidad }}</td>
                            <td>{{ item.Precio }}</td>
                            <td>{{ item.TOTAL }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="label">
                    <span class="label-text font-bold">Total Repartidor: {{ totalRepartidor(repartidor.IDRepartidor).toLocaleString('es-CL') }}</span>
                </div>
            </div>
            <div class="label">
                <span class="label-text font-bold">Total General: {{ total.toLocaleString('es-CL') }}</span>
            </div>
        </div>
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
        getResumen() {
            let url = `https://nuestrocampo.cl/api/resumen/read.php`;
            axios.get(url).then((response) => {
                this.items = response.data;
            })
        },
        totalRepartidor(idRepartidor) {
            return this.items
                .filter(item => item.IDRepartidor === idRepartidor)
                .reduce((acc, item) => acc + parseInt(item.TOTAL), 0);
        }
    },

    computed: {
        total() {
            return this.items.reduce((acc, item) => acc + parseInt(item.TOTAL), 0);
        },
        repartidores() {
            return this.items.reduce((acc, item) => {
                const existingRepartidor = acc.find(r => r.IDRepartidor === item.IDRepartidor);
                if (!existingRepartidor) {
                    acc.push({ IDRepartidor: item.IDRepartidor, Username: item.Username });
                }
                return acc;
            }, []);
        }
    },

    mounted() {
        const sessionData = this.checkUserSession();
        if (sessionData) {
            console.log('Sesión iniciada. Montando...');
            this.getResumen();
        } else {
            console.log('No hay sesión iniciada. Redireccionando a login');
            this.$router.push({ name: 'LogIn' });
        }
    },

    components: { DespacharRepartidor }
};
</script>

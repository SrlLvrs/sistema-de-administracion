<template>
    <div class="prose max-w-none text-center p-4">
        <h1>Resumen diario</h1>
        <div id="items" v-if="items.length === 0">
            <p>
                No hay información para hoy. Necesitas crear al menos 1 pedido.
            </p>
        </div>
        <div v-else>
            <div v-for="(repartidor, index) in repartidores" :key="index">
                <h2>{{ repartidor.Username }}</h2>
                <table class="table table-zebra" v-if="repartidorDetails[repartidor.IDRepartidor]">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, idx) in repartidorDetails[repartidor.IDRepartidor]" :key="idx">
                            <td>{{ item.Nombre }}</td>
                            <td>{{ item.Valor }}</td>
                        </tr>
                        <tr class="font-bold">
                            <td>Total esperado del Repartidor</td>
                            <td>{{ totalRepartidor(repartidor.IDRepartidor).toLocaleString('es-CL') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="table table-zebra mt-4">
                <tbody>
                    <tr class="font-bold">
                        <td>Total esperado General</td>
                        <td>{{ total.toLocaleString('es-CL') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Resumen",

    data() {
        return {
            items: [],
            repartidorDetails: {}, // Almacenará los detalles de cada repartidor
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
        getResumen() {
            console.log('Obteniendo resumen...')
            let url = `https://nuestrocampo.cl/api/resumen/read.php`;
            axios.get(url).then((response) => {
                console.log(response.data)
                this.items = response.data;
            });
        },
        totalRepartidor(idRepartidor) {
            if (this.repartidorDetails[idRepartidor]) {
                return this.repartidorDetails[idRepartidor]
                    .reduce((acc, item) => {
                        if (item && item.Valor && typeof item.Valor === 'string') {
                            return acc + parseFloat(item.Valor.replace(/[^0-9,-]+/g,"")) || 0;
                        }
                        return acc;
                    }, 0);
            }
            return 0;
        },
        getDetail(idRepartidor) {
            console.log('Obteniendo detalle para el repartidor:', idRepartidor);
            // Verifica si los detalles ya están almacenados
            if (!this.repartidorDetails[idRepartidor]) {
                let url = `https://nuestrocampo.cl/api/resumen/read-detail.php?idr=${idRepartidor}`;
                axios.get(url).then((response) => {
                    // Almacena los detalles para el repartidor directamente
                    this.repartidorDetails[idRepartidor] = response.data;
                });
            }
        },
    },

    computed: {
        total() {
            return this.items.reduce((acc, item) => acc + parseInt(item.Total), 0);
        },
        repartidores() {
            return this.items.reduce((acc, item) => {
                const existingRepartidor = acc.find(r => r.IDRepartidor === item.IDRepartidor);
                if (!existingRepartidor) {
                    acc.push({ IDRepartidor: item.IDRepartidor, Username: item.Repartidor });
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

    watch: {
        // Observa cambios en 'repartidores' y ejecuta 'getDetail' para cada repartidor
        repartidores(newRepartidores) {
            newRepartidores.forEach(repartidor => {
                this.getDetail(repartidor.IDRepartidor);
            });
        }
    },
};
</script>


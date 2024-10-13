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
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad Necesaria</th>
                            <th>Cantidad Entregada</th>
                            <th>Cantidad Restante</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.filter(i => i.IDRepartidor === repartidor.IDRepartidor)"
                            :key="item.ID">
                            <td>{{ item.Producto }}</td>
                            <td>{{ item.TotalCantidad }}</td>
                            <td>{{ item.CantidadEntregada }}</td>
                            <td>{{ item.TotalRestante }}</td>
                            <td>{{ item.Precio }}</td>
                            <td>{{ item.Total }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="label">
                    <span class="label-text font-bold">Total esperado del Repartidor: {{
                        totalRepartidor(repartidor.IDRepartidor).toLocaleString('es-CL') }}</span>
                </div>

                <div v-if="repartidorDetails[repartidor.IDRepartidor]"
                    v-for="(item, idx) in repartidorDetails[repartidor.IDRepartidor]" :key="idx" class="label">
                    <span class="label-text font-bold">
                        {{ item.Nombre }}: {{ item.Valor }}
                    </span>
                </div>
            </div>
            <div class="label">
                <span class="label-text font-bold">Total esperado General: {{ total.toLocaleString('es-CL') }}</span>
            </div>
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
            const total = this.items
                .filter(item => item.IDRepartidor === idRepartidor)
                .reduce((acc, item) => acc + parseInt(item.Total), 0);

            return total;
        },
        getDetail(idRepartidor) {
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

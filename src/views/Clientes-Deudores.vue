<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Clientes Deudores</h1>
            <div class="flex justify-center">
                <!-- INPUT FILTRAR -->
                <label class="input input-bordered flex items-center gap-2 mx-2">
                    <input v-model="filterText" type="text" class="grow" placeholder="Nombre o dirección..." />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="h-4 w-4 opacity-70">
                        <path fill-rule="evenodd"
                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </label>
                <!-- BOTON Exportar a Excel -->
                <Excel :items="filteredItems" />
            </div>
        </div>
        
        <!-- Mostrar la suma total de deudas -->
        <div class="text-center mt-4 mb-4">
            <span class="font-bold text-xl">Deuda Total: ${{ totalDeuda.toLocaleString('es-CL') }}</span>
        </div>
    </div>
    <!-- RESULTADOS -->
    <div class="overflow-x-auto">
        <table class="table">
            <!-- Head -->
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Deuda Total</th>
                    <th>Teléfono</th>
                    <th>Teléfono 2</th>
                    <th>Día de reparto</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es ITEMS, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="item in filteredItems" :key="item.ID">
                    <th>{{ item.Nombre }}</th>
                    <td>{{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</td>
                    <td>{{ item.Deuda_Total }}</td>
                    <td>{{ item.Telefono }}</td>
                    <td>{{ item.Telefono2 }}</td>
                    <td>{{ item.diaDeReparto }}</td>
                    <td>{{ item.Observacion }}</td>
                    <td>
                        <!-- Detalle cliente -->
                         <DetalleCliente 
                         :id="item.ID"
                         :label="item.ID + 'detail'"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import DetalleCliente from "../components/DetalleClienteDeudor.vue"
import Excel from "../components/Excel.vue"

export default {
    //Nombre del componente
    name: "Clientes-Deudores",

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
            filterText: '',
            totalDeuda: 0,
        };
    },

    methods: {
        checkUserSession() {
            const sessionData = JSON.parse(localStorage.getItem('authUser'));
            return sessionData ? sessionData : null;
        },
        async getClientesDeudores() {
            let url = "https://nuestrocampo.cl/api/clientes/read-debt.php";
            await axios.get(url).then((response) => {
                this.items = response.data;
                this.calcularTotalDeuda();
            });
        },
        calcularTotalDeuda() {
            this.totalDeuda = this.filteredItems.reduce((total, item) => {
                return total + parseFloat(item.Deuda_Total.replace(/[^0-9.-]+/g,""));
            }, 0);
        }
    },

    computed: {
        //Esta función filtra el array en base a el NOMBRE o la DIRECCION del cliente
        filteredItems() {
            const normalizeText = (text) => {
                return text ? text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase() : '';
            };

            const filterTextNormalized = normalizeText(this.filterText || '');

            const filteredResults = this.items.filter(item => {
                const matchesText =
                    !filterTextNormalized ||
                    normalizeText(item.Nombre || '').includes(filterTextNormalized) ||
                    normalizeText(item.Direccion || '').includes(filterTextNormalized) ||
                    normalizeText(item.NombreSector || '').includes(filterTextNormalized) ||
                    normalizeText(item.Comuna || '').includes(filterTextNormalized);

                // Devuelve los elementos que coincidan con todos los filtros activos
                return matchesText;
            });

            this.$nextTick(() => {
                this.calcularTotalDeuda();
            });

            return filteredResults;
        }
    },

    mounted() {
        const sessionData = this.checkUserSession();
        if (sessionData) {
            console.log('Sesión iniciada. Montando...');
            this.getClientesDeudores()
        } else {
            console.log('No hay sesión iniciada. Redireccionando a login');
            this.$router.push({ name: 'LogIn' });
        }
    },

    components: { DetalleCliente, Excel },
}
</script>
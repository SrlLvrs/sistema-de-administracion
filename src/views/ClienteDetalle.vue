<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Detalle Clientes {{ id }}</h1>
        </div>
    </div>
    <div>
        {{ items }}
    </div>
    <div class="overflow-x-auto">
        <table class="table">
            <!-- Head -->
            <thead>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Teléfono 2</th>
                    <th>Día de reparto</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es ITEMS, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="item in items" :key="item.IDCliente">
                    <th>
                        <div v-if="item.Deuda" class="text-red-500 tooltip tooltip-right"
                            data-tip="Cliente con deuda pendiente">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                    </th>
                    <th> {{ item.Nombre }}</th>
                    <td> {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</td>
                    <td> {{ item.Telefono }}</td>
                    <td> {{ item.Telefono2 }}</td>
                    <td> {{ item.Dia_de_Reparto }}</td>
                    <td> {{ item.Observacion }}</td>
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
    name: "DetalleCliente",

    props: {
        id: Number,
    },

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
        };
    },

    methods: {
        async getInfoCliente() {
            let url = `https://nuestrocampo.cl/api/clientes/read-detail.php?id=${this.id}`;
            await axios.get(url).then((response) => (this.items = response.data));
        },
    },

    computed: {
        //Nada por aqui
    },

    mounted() {
        this.getInfoCliente()
    },
}
</script>
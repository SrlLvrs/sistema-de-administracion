<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Clientes</h1>
            <div class="flex justify-center">
                <!-- Botón CREAR NUEVO CLIENTE -->
                <CrearCliente />
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
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Sector</th>
                    <th>Comuna</th>
                    <th>Teléfono</th>
                    <th>Teléfono 2</th>
                    <th>Frecuencia</th>
                    <th>Día de reparto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es SECTORES, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="(cliente, index) in clientes" :key="cliente.id">
                    <!-- 
                        <th> {{ index }}</th>
                        <td> {{ cliente.id }}</td>
                        -->
                    <td> {{ cliente.nombre }}</td>
                    <td> {{ cliente.direccion }}</td>
                    <td> {{ cliente.nombresector }}</td>
                    <td> {{ cliente.comuna }}</td>
                    <td> {{ cliente.telefono }}</td>
                    <td> {{ cliente.telefono2 }}</td>
                    <td> {{ cliente.frecuencia }}</td>
                    <td> {{ cliente.diadereparto }}</td>
                    <td>
                        <!-- Crear Pedido -->
                        <CrearPedido :id="cliente.id"/>
                        <!-- BOTÓN MAPS -->
                        <button class="btn btn-outline btn-info mr-2">
                            <a :href="cliente.linkmaps" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                            </a>
                        </button>
                        <!-- Editar Cliente -->

                        <EditarCliente :id="cliente.id" :nombre="cliente.nombre" :comuna="cliente.comuna"
                            :sector="cliente.nombresector" :id_sector="cliente.id_sector" :dia="cliente.diadereparto"
                            :direccion="cliente.direccion" :telefono="cliente.telefono" :telefono2="cliente.telefono2"
                            :linkmaps="cliente.linkmaps" :frecuencia="cliente.frecuencia" />
                        <!-- Eliminar Cliente -->
                        <EliminarCliente :id="cliente.id" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import CrearCliente from "../components/CrearCliente.vue";
import EliminarCliente from "../components/EliminarCliente.vue";
import EditarCliente from "../components/EditarCliente.vue";
import CrearPedido from "../components/CrearPedido.vue";

export default {
    //Nombre del componente
    name: "Clientes",

    data() {
        return {
            //Array para guardar datos de la API
            clientes: [],
        };
    },

    methods: {},

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/api/clientes/read.php";

        axios.get(url).then((response) => (this.clientes = response.data));
    },

    components: { CrearCliente, EliminarCliente, EditarCliente, CrearPedido },
}
</script>
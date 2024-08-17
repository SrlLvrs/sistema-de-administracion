<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Clientes</h1>
            <div class="flex justify-center">
                <!-- Botón CREAR NUEVO CLIENTE -->
                <CrearCliente />

                <!-- INPUT FILTRAR -->
                <label class="input input-bordered flex items-center gap-2">
                    <input v-model="filterText" type="text" class="grow" placeholder="Nombre o dirección..." />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="h-4 w-4 opacity-70">
                        <path fill-rule="evenodd"
                            d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </label>
            </div>
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
                    <th>Teléfono</th>
                    <th>Teléfono 2</th>
                    <th>Frecuencia</th>
                    <th>Día de reparto</th>
                    <th>Observaciones</th>
                    <th>Preferencias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es ITEMS, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="item in filteredItems" :key="item.id">
                    <th> {{ item.nombre }}</th>
                    <td> {{ item.direccion }}, {{ item.nombresector }}, {{ item.comuna }}</td>
                    <td> {{ item.telefono }}</td>
                    <td> {{ item.telefono2 }}</td>
                    <td> {{ item.frecuencia }}</td>
                    <td> {{ item.diadereparto }}</td>
                    <td> {{ item.observacion }}</td>
                    <td> {{ item.preferencia }}</td>
                    <td>
                        <!-- Crear Pedido -->
                        <CrearPedido :id="item.id + 'pdd'" :cliente="item.id"/>
                        <!-- BOTÓN MAPS -->
                        <button class="btn btn-outline btn-info mr-2">
                            <a :href="item.linkmaps" target="_blank">
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
                        <EditarCliente :id="item.id" :nombre="item.nombre" :comuna="item.comuna"
                            :sector="item.nombresector" :id_sector="item.id_sector" :dia="item.diadereparto"
                            :direccion="item.direccion" :telefono="item.telefono" :telefono2="item.telefono2"
                            :linkmaps="item.linkmaps" :frecuencia="item.frecuencia" :observaciones="item.observacion"
                            :producto_preferido="item.preferencia" :id_producto_preferido="item.id_producto" />

                        <!-- Eliminar Cliente -->
                        <EliminarCliente :id="item.id" />
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
            items: [],
            filterText: '',
        };
    },

    methods: {
    },

    computed: {
        //Esta función filtra el array en base a el NOMBRE o la DIRECCION del cliente
        filteredItems() {
            return this.items.filter(item => {
                return item.nombre.toLowerCase().includes(this.filterText.toLowerCase()) || //esta linea filtra texto
                    item.direccion.toLowerCase().includes(this.filterText.toLowerCase());
                //item.edad.toString().includes(this.filterText); //esta linea filtra INTs
                //hay que añadir una de estas 2 tipos de lineas, para cada una de las columnas a filtrar

            });
        }
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/api/clientes/read.php";

        axios.get(url).then((response) => (this.items = response.data));
    },

    components: { CrearCliente, EliminarCliente, EditarCliente, CrearPedido },
}
</script>
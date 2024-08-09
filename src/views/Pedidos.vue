<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Todos los pedidos</h1>
            <div class="flex justify-center">
                <!-- Nuevo Pedido -->
                

                <!-- INPUT FILTRAR -->
                <label class="input input-bordered flex items-center gap-2 ml-2">
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
            <!-- head w-11/12 -->
            <thead>
                <tr>
                    <th>Número de pedido</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Pagado</th>
                    <th>Medio de Pago</th>
                    <th>Fecha de Entrega</th>
                    <th>Hora de Creación</th>
                    <th>Hora de Cierre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es FILTEREDITEMS, que es una función que toma el JSON y lo convierte en array, para poder filtrarlo -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="item in filteredItems" :key="item.id">
                    <td> {{ item.id }}</td>
                    <td> {{ item.cliente }}</td>
                    <td class="badge-info"> {{ item.estado }}</td>
                    <td> {{ item.pagado }}</td>
                    <td> {{ item.medio_pago }}</td>
                    <td> {{ item.fecha_entrega }}</td>
                    <td> {{ item.hora_creacion }}</td>
                    <td> {{ item.hora_cierre }}</td>
                    <td>
                        <!-- Editar Pedido -->

                        <!-- Eliminar Pedido -->
                        <EliminarPedido :id="item.id"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import EliminarPedido from "../components/EliminarPedido.vue";

export default {
    //Nombre del componente
    name: "Pedidos",

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
                return item.cliente.toLowerCase().includes(this.filterText.toLowerCase()) //esta linea filtra texto, para añadir más, usar || al final
                //    item.direccion.toLowerCase().includes(this.filterText.toLowerCase());
                //item.edad.toString().includes(this.filterText); //esta linea filtra INTs
                //hay que añadir una de estas 2 tipos de lineas, para cada una de las columnas a filtrar

            });
        }
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/api/pedidos/read.php";

        axios.get(url).then((response) => (this.items = response.data));
    },

    components: { EliminarPedido },
}
</script>
<template>
    <!-- ALERTA -->
    <div role="alert" class="alert alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Vista de clientes</span>
    </div>

    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Clientes</h1>
            <div class="flex justify-center">
                <!-- Botón CREAR NUEVO SECTOR -->
                <crearSectorModal />
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
                        -->
                    <th>ID</th>
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
                        -->
                    <td> {{ cliente.id }}</td>
                    <td> {{ cliente.nombre }}</td>
                    <td> {{ cliente.direccion }}</td>
                    <td> {{ cliente.nombresector }}</td>
                    <td> {{ cliente.comuna }}</td>
                    <td> {{ cliente.telefono }}</td>
                    <td> {{ cliente.telefono2 }}</td>
                    <td> {{ cliente.frecuencia }}</td>
                    <td> {{ cliente.diadereparto }}</td>
                    <td>
                        <button class="btn btn-outline btn-info">
                            <a :href="cliente.linkmaps" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </a>

                        </button>
                        <!-- 
                            <div>
                                <button @click="copyToClipboard(cliente.linkmaps)">Copiar texto</button>
                            </div>
                            -->
                    </td>
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
    name: "Clientes",

    data() {
        return {
            //Array para guardar datos de la API
            clientes: [],
        };
    },

    methods: {
        async copyToClipboard(maps) {
            //const text = "Este es el texto a copiar";

            if (navigator.clipboard) {
                try {
                    await navigator.clipboard.writeText(maps);
                    alert("Texto copiado al portapapeles usando CLIPBOARD!");
                } catch (err) {
                    console.error("Error al copiar el texto: ", err);
                }
            } else {
                // Fallback para navegadores que no soportan navigator.clipboard
                const textArea = document.createElement("textarea");
                textArea.value = maps;
                textArea.style.position = "absolute";
                textArea.style.left = "-9999px";
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand("copy");
                document.body.removeChild(textArea);
                alert("Texto copiado al portapapeles usando execCommand!");
            }
        }
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/demo/clientes/read.php";

        axios.get(url).then((response) => (this.clientes = response.data));
    },
}
</script>
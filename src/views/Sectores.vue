<template>
    <!-- ALERTA -->
    <div role="alert" class="alert alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Vista de sectores</span>
    </div>

    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Sectores de reparto</h1>
            <div class="flex justify-center">
                <!-- Botón CREAR NUEVO SECTOR -->
                <button class="btn btn-outline btn-success mb-4" onclick="crearSectorModal.showModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    Crear nuevo sector
                </button>
                <!-- Modal CREAR NUEVO SECTOR -->
                <dialog id="crearSectorModal" class="modal">
                    <div class="modal-box">
                        <h3 class="text-lg font-bold">Nuevo sector</h3>
                        <div class="label">
                            <span class="label-text">Nombre del sector</span>
                        </div>
                        <input v-model="nombresector" type="text" placeholder="Ingresa el nombre del sector"
                            class="input input-bordered w-full max-w-xs mb-2" />
                        <div class="label">
                            <span class="label-text">Comuna</span>
                        </div>
                        <input v-model="comuna" type="text" placeholder="Ingresa la comuna"
                            class="input input-bordered w-full max-w-xs  mb-2" />
                        <div class="label">
                            <span class="label-text">Día de reparto</span>
                        </div>
                        <select v-model="diareparto" class="select select-bordered w-full max-w-xs">
                            <option v-for="dia in dias" v-bind:value="dia.value">
                                {{ dia.text }}
                            </option>
                        </select>
                        <div class="label">
                            <span class="label-text">Orden en que se hace el reparto</span>
                        </div>
                        <input v-model="orden" type="text" placeholder="Ingresa un número"
                            class="input input-bordered w-full max-w-xs mb-2" />
                        <div class="modal-action">
                            <form method="dialog">
                                <!-- if there is a button in form, it will close the modal -->
                                <!-- AGREGAR EL NUEVO SECTOR AL ARRAY, EN VEZ DE ACTUALIZAR LA PÁGINA -->
                                <button class="btn btn-outline btn-success mr-2" @click="crearSector()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Añadir sector</button>
                                <button class="btn btn-outline btn-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>

                                    Salir</button>
                            </form>
                        </div>
                    </div>
                </dialog>
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
                    <th>Nombre del Sector</th>
                    <th>Comuna</th>
                    <th>Día de reparto</th>
                    <th>Orden de Reparto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <!-- GET -->
                <!-- el nombre del array es SECTORES, que debe ser el mismo que se define en DATA() RETURN -->
                <!-- Los resultados deben recorrerse dentro del TR -->
                <tr v-for="(sector, index) in sectores" :key="sector.id">
                    <!-- 
                        <th> {{ index }}</th>
                        <td> {{ sector.id }}</td>
                        -->
                    <td> {{ sector.nombreSector }}</td>
                    <td> {{ sector.comuna }}</td>
                    <td> {{ sector.diaReparto }}</td>
                    <td> {{ sector.orden }}</td>
                    <td>
                        <!-- Botón EDITAR SECTOR -->
                        <button class="btn btn-outline btn-warning mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            Editar Sector
                        </button>
                        <!-- Botón BORRAR SECTOR -->
                        <button class="btn btn-outline btn-error" @click="eliminarSector(sector.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            Eliminar Sector
                        </button>
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
    name: "Sectores",

    data() {
        return {
            //Array para guardar datos de la API
            sectores: [],
            nombresector: "",
            comuna: "",
            diareparto: "A",
            message: "",
            dias: [
                { text: 'Lunes', value: 'A' },
                { text: 'Martes', value: 'B' },
                { text: 'Miércoles', value: 'C' },
                { text: 'Jueves', value: 'D' },
                { text: 'Viernes', value: 'E' },
                { text: 'Sábado', value: 'F' },
                { text: 'Domingo', value: 'G' }
            ],
            orden: "",
        };
    },

    computed: {
        diadelasemana() {
            let dia = this.dias.find(dia => dia.value === this.diareparto);
            return dia ? dia.text : '';
        }
    },

    methods: {
        crearSector() {
            let nombresector = this.nombresector;
            let comuna = this.comuna;
            let orden = this.orden;
            let diareparto = this.diadelasemana;
            let url = `https://nuestrocampo.cl/api/sectores/create.php?nombresector=${nombresector}&comuna=${comuna}&diareparto=${diareparto}&orden=${orden}`
            axios.post(url);
            console.log("Sector creado");
            location.reload();
        },
        eliminarSector(id) {
            let url = `https://nuestrocampo.cl/api/sectores/delete.php?id=${id}`;
            axios.delete(url);
            console.log(id);
            console.log("Sector eliminado".id);
            location.reload();
        }
    },

    //Método para llamar a la API cuando se cree la instancia
    created() {
        //Variable con endpoint
        let url = "https://nuestrocampo.cl/api/sectores/read.php";

        axios.get(url).then((response) => (this.sectores = response.data));
    },
};
</script>
<template>
    <button class="btn btn-outline btn-success mb-4" onclick="crearSectorModal.showModal()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
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
                <option v-for="dia in diasdelasemana">
                    {{ dia }}
                </option>
            </select>
            <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button class="btn btn-outline btn-success mr-2" @click="crearSector()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Añadir sector</button>
                    <button class="btn btn-outline btn-error">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>

                        Salir</button>
                </form>
            </div>
        </div>
    </dialog>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    //Nombre del componente
    name: "CrearSectorModal",

    data() {
        return {
            //Array para guardar datos de la API
            nombresector: "",
            comuna: "",
            diareparto: "",
            diasdelasemana: ['Lunes', 'Martes', 'Miércoles', 'Jueves', "Viernes", "Sábado", "Domingo"],
        };
    },

    methods: {
        crearSector() {
            let nombresector = this.nombresector;
            let comuna = this.comuna;
            let diareparto = this.diareparto;
            let url = `https://nuestrocampo.cl/api/sectores/create.php?nombresector=${nombresector}&comuna=${comuna}&diareparto=${diareparto}`
            axios.post(url);
            location.reload();
        }
    },
};
</script>
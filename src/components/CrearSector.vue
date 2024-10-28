<template>
    <!-- Botón para abrir el modal -->
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
            <!--Nombre-->
            <div class="label">
                <span class="label-text">Nombre del sector</span>
            </div>
            <input v-model="nombresector" type="text" placeholder="Ingresa el nombre del sector"
                class="input input-bordered w-full max-w-xs mb-2" />
            
            <!--Comuna-->
                <div class="label">
                <span class="label-text">Comuna</span>
            </div>
            <input v-model="comuna" type="text" placeholder="Ingresa la comuna"
                class="input input-bordered w-full max-w-xs  mb-2" />
            
            <!--Día de reparto-->
                <div class="label">
                <span class="label-text">Día de reparto</span>
            </div>
            <select v-model="diareparto" class="select select-bordered w-full max-w-xs">
                <option v-for="dia in diasdelasemana">
                    {{ dia }}
                </option>
            </select>

            <!--Repartidor-->
                <div class="label">
                <span class="label-text">Repartidor</span>
            </div>
            <select v-model="repartidor" class="select select-bordered w-full max-w-xs">
                <option v-for="(item, index) in items" :value="item.ID">
                    {{ item.Username }}
                </option>
            </select>

            <!--Orden-->
            <div class="label">
                <span class="label-text">Orden</span>
            </div>
            <input v-model="orden" type="number" placeholder="Orden en relación a otros sectores de la misma comuna"
                class="input input-bordered w-full max-w-xs mb-2" />
            
            <!--Acciones-->
            <div class="modal-action">
                <form method="dialog">
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
            items: [],
            nombresector: "",
            comuna: "",
            diareparto: "",
            repartidor: '',
            diasdelasemana: ['Lunes', 'Martes', 'Miércoles', 'Jueves', "Viernes", "Sábado", "Domingo"],
            orden: 1, // Nuevo campo para el orden
        };
    },

    methods: {
        crearSector() {
            let ns = this.nombresector;
            let c = this.comuna;
            let dr = this.diareparto;
            let r = this.repartidor;
            let o = this.orden; // Nuevo campo orden
            let url = `https://nuestrocampo.cl/demo/sectores/create.php?nombresector=${ns}&comuna=${c}&diareparto=${dr}&repartidor=${r}&orden=${o}`
            axios.post(url);
            location.reload();
        }
    },

    mounted() {
        let url = `https://nuestrocampo.cl/demo/users/read-deliver.php`
        axios.get(url).then((response) => (this.items = response.data))
    }
};
</script>
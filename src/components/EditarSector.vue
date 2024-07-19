<template>
    {{ nombresector }}
    <!-- The button to open modal -->
    <label :for="nombre" class="btn btn-outline btn-warning mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
        Editar Sector</label>

    <!-- Put this part before </body> tag -->
    <input type="checkbox" :id="nombre" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Nuevo sector</h3>
            <div class="label">
                <span class="label-text">Nombre del sector</span>
            </div>
            <input v-model="nombresector" type="text" placeholder="Ingresa el nombre del sector"
                class="input input-bordered w-full max-w-xs mb-2" />

            <div class="modal-action">
                <label :for="nombre" class="btn">Close!</label>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    //Nombre del componente
    name: "CrearSectorModal",

    props: {
        nombre: String,
    },

    data() {
        return {
            //Array para guardar datos de la API
            nombresector: "",
            comuna: "",
            diareparto: "A",
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
        },
        propstodata(){
            this.nombresector = this.nombre;
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
            location.reload();
        }
    },
};
</script>
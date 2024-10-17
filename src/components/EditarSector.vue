<template>
    <!-- Botón para abrir el modal -->
    <label :for="id" class="btn btn-outline btn-warning">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="id" class="modal-toggle" v-model="isModalOpen" />
    <div class="modal" role="dialog">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Editar sector</h3>

            <!-- Nombre -->
            <div class="label">
                <span class="label-text">Nombre del sector</span>
            </div>
            <input v-model="localNombreSector" type="text" placeholder="Ingresa el nombre del sector"
                class="input input-bordered w-full max-w-xs mb-2" />

            <!-- Comuna -->
            <div class="label">
                <span class="label-text">Comuna</span>
            </div>
            <input v-model="localComuna" type="text" placeholder="Ingresa la comuna"
                class="input input-bordered w-full max-w-xs  mb-2" />

            <!-- Dia de reparto -->
            <div class="label">
                <span class="label-text">Día de reparto</span>
            </div>
            <select v-model="localDiaReparto" class="select select-bordered w-full max-w-xs">
                <option disabled selected> {{ localDiaReparto }} </option>
                <option v-for="dia in diasdelasemana">
                    {{ dia }}
                </option>
            </select>

            <!--Repartidor-->
            <div class="label">
                <span class="label-text">Repartidor</span>
            </div>

            <select v-model="localRepartidor" class="select select-bordered w-full max-w-xs">
                <!-- Mostrar el Username del ID seleccionado -->
                <option disabled selected> {{ selectedUsername || 'Selecciona un repartidor' }} </option>
                <!-- Listar las opciones de usernames -->
                <option v-for="(item, index) in items" :key="index" :value="item.ID">
                    {{ item.Username }}
                </option>
            </select>

            <!-- Orden -->
            <div class="label">
                <span class="label-text">Orden</span>
            </div>
            <input v-model="localOrden" type="number" placeholder="Orden en relación a otros sectores de la misma comuna"
                class="input input-bordered w-full max-w-xs mb-2" />

            <div class="modal-action">
                <label :for="id" class="btn">Salir</label>
                <button class="btn btn-outline btn-warning" @click="editarSector(id)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Editar sector
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "EditarSectorModal",

    props: {
        id: String,
        nombreSector: String,
        comuna: String,
        diareparto: String,
        repartidor: String,
        orden: String, // Nueva prop
    },

    data() {
        return {
            items: [],
            localNombreSector: this.nombreSector,
            localComuna: this.comuna,
            localDiaReparto: this.diareparto,
            localRepartidor: this.repartidor,
            localOrden: this.orden, // Nuevo campo local
            diasdelasemana: ['Lunes', 'Martes', 'Miércoles', 'Jueves', "Viernes", "Sábado", "Domingo"],
            isModalOpen: false,
        };
    },

    watch: {
        isModalOpen(newValue) {
            if (newValue) {
                this.loadData();
            }
        }
    },

    methods: {
        async loadData() {
            let url = `https://nuestrocampo.cl/api/users/read-deliver.php`
            await axios.get(url).then((response) => (this.items = response.data))
            console.log('Cargando datos para editar sector')
        },

        editarSector(id) {
            let nombre = this.localNombreSector;
            let comuna = this.localComuna;
            let dia = this.localDiaReparto;
            let repartidor = this.localRepartidor;
            let orden = this.localOrden; // Nuevo campo
            let url = `https://nuestrocampo.cl/api/sectores/update.php?id=${id}&nombresector=${nombre}&comuna=${comuna}&diareparto=${dia}&repartidor=${repartidor}&orden=${orden}`
            axios.put(url);
            location.reload();
        },
    },

    computed: {
        selectedUsername() {
            const selectedItem = this.items.find(item => item.ID === this.repartidor);
            return selectedItem ? selectedItem.Username : null;
        }
    },
};
</script>
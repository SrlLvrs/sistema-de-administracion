<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-info mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">
                Editar rol
            </h3>

            <div>
                <!-- Rol -->
                <label class="label">
                    <span class="label-text font-bold">Asignar repartidor a este sector</span>
                </label>
                <select v-model="repartidor" class="select select-bordered w-full max-w-xs">
                    <option v-for="item in items" :key="item.ID" :value="item.ID">{{ item.Username }}</option>
                </select>

                <!-- Botones del modal -->
                <div class="modal-action">
                    <label :for="label" class="btn">Salir</label>
                    <button class="btn btn-outline btn-info" @click="updateRepartidor()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        Asignar repartidor
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    name: "AsignarRepartidor",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            items: [],
            repartidor: '',
        };
    },

    methods: {
        async getRepartidores() {
            let url = `https://nuestrocampo.cl/api/users/read-deliver.php`;
            await axios.get(url).then((response) => (this.items = response.data));
        },
        async updateRepartidor() {
            let idr = this.repartidor;
            let ids = this.id;

            let url = `https://nuestrocampo.cl/api/pedidos/update-delivery.php?idsector=${ids}&idrepartidor=${idr}`
            await axios.put(url).then(function (response) {
                console.log(response.data);
            })

            location.reload()
        }
    },

    mounted() {
        this.getRepartidores()
    }
};
</script>

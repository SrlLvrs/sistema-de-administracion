<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-info mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
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
                    <span class="label-text font-bold">Nuevo Rol</span>
                </label>
                <select v-model="rol" class="select select-bordered w-full max-w-xs">
                    <option value="Administrador">Administrador</option>
                    <option value="Colaborador">Colaborador</option>
                    <option value="Repartidor">Repartidor</option>
                </select>

                <!-- Botones del modal -->
                <div class="modal-action">
                    <label :for="label" class="btn">Salir</label>
                    <button class="btn btn-outline btn-info" @click="editRol()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        Editar rol
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import bcrypt from "bcryptjs";

export default {
    name: "EditarRol",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            rol: '',
        };
    },

    methods: {
        async editRol() {
            let r = this.rol
            let id = this.id

            // Enviar datos al backend (API)
            let url = `https://nuestrocampo.cl/api/users/update-role.php?id=${id}&rol=${r}`;
            axios.put(url).then(function (response) {
                console.log(response.data);
            });

            location.reload();
        }
    },
};
</script>

<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-warning mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">
                Cargar camión
            </h3>

            <div>
                <!-- Contraseña -->
                <label class="label">
                    <span class="label-text font-bold">Nueva Contraseña</span>
                </label>
                <input type="password" v-model="password" placeholder="Contraseña"
                    class="input input-bordered w-full max-w-xs mb-2" />

                <!-- Botones del modal -->
                <div class="modal-action">
                    <label :for="label" class="btn">Salir</label>
                    <button class="btn btn-outline btn-warning" @click="editPass()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                        </svg>
                        Editar contraseña
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
    name: "EditarPass",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            password: '',
        };
    },

    methods: {
        async editPass() {
            const saltRounds = 10;
            // Encriptar la contraseña
            const hashedPassword = await bcrypt.hash(this.password, saltRounds);
            console.log(hashedPassword)

            // Enviar datos al backend (API)
            let id = this.id;
            let p = hashedPassword;
            let url = `https://nuestrocampo.cl/demo/users/update.php?id=${id}&pass=${p}`;
            axios.put(url).then(function (response) {
                console.log(response.data);
            });

            location.reload();
        }
    },
};
</script>

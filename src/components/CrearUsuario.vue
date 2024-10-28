<template>
    <!-- Botón CREAR NUEVO CLIENTE -->
    <button class="btn btn-outline btn-success mr-2 mb-4" onclick="crearClienteModal.showModal()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Crear nuevo usuario
    </button>

    <!-- Modal CREAR NUEVO CLIENTE -->
    <dialog id="crearClienteModal" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold mb-2 text-center">Nuevo usuario</h3>

            <!-- Nombre -->
            <label class="label">
                <span class="label-text font-bold">Nombre de usuario</span>
            </label>
            <input type="text" v-model="username" placeholder="Nombre"
                class="input input-bordered w-full max-w-xs mb-2" />

            <!-- Contraseña -->
            <label class="label">
                <span class="label-text font-bold">Contraseña</span>
            </label>
            <input type="password" v-model="password" placeholder="Contraseña" class="input input-bordered w-full max-w-xs mb-2" />

            <!-- Rol -->
            <label class="label">
                <span class="label-text font-bold">Rol</span>
            </label>
            <select v-model="rol" class="select select-bordered w-full max-w-xs">
                <option value="Administrador">Administrador</option>
                <option value="Colaborador">Colaborador</option>
                <option value="Repartidor">Repartidor</option>
            </select>

            <!-- Acciones del modal -->
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-outline btn-success mr-2" @click="crearUsuario()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Crear usuario</button>
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
import axios from "axios";
import bcrypt from 'bcryptjs';

export default {
    //Nombre del componente
    name: "CrearUsuarioModal",

    data() {
        return {
            username: '',
            password: '',
            rol: ''
        };
    },

    methods: {
        async crearUsuario() {
            const saltRounds = 10;
            // Encriptar la contraseña
            const hashedPassword = await bcrypt.hash(this.password, saltRounds);

            // Enviar datos al backend (API)
            let u = this.username;
            let p = hashedPassword;
            let r = this.rol;
            let url = `https://nuestrocampo.cl/demo/users/create.php?username=${u}&pass=${p}&rol=${r}`;

            axios.post(url).then(function (response) {
                console.log(response.data);
                location.reload()
            })
        }
    },
};
</script>
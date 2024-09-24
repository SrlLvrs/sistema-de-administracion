<template>
    <div class="prose max-w-none text-center p-4">
        <h1>Crea un nuevo usuario</h1>
    </div>
    <form @submit.prevent="crearUsuario()" class="space-y-6 w-full max-w-sm mx-auto">
        <!-- Nombre -->
        <div class="form-control">
            <label class="label">
                <span class="label-text font-bold">Nombre de usuario</span>
            </label>
            <input type="text" v-model="username" placeholder="Nombre" class="input input-bordered w-full" />
        </div>
        <!-- Contraseña -->
        <div class="form-control">
            <label class="label">
                <span class="label-text font-bold">Contraseña</span>
            </label>
            <input type="password" v-model="password" placeholder="Contraseña" class="input input-bordered w-full" />
        </div>
        <!-- Rol -->
        <div class="form-control">
            <label class="label">
                <span class="label-text font-bold">Rol</span>
            </label>
            <select v-model="rol" class="select select-bordered w-full">
                <option value="Repartidor">Repartidor</option>
                <option value="Colaborador">Colaborador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-outline btn-success w-full">Crear cuenta</button>
    </form>
</template>

<script>
import axios from 'axios';
import bcrypt from 'bcryptjs';

export default {
    //Nombre del componente
    name: "Admin",

    data() {
        return {
            username: '',
            password: '',
            rol: '',
        };
    },

    methods: {
        crearUsuarioXXXX() {
            let n = this.nombre;
            let r = this.rol;
            let u = this.uid;
            let url = `https://nuestrocampo.cl/api/users/create.php?name=${n}&rol=${r}&uid=${u}`;

            axios.post(url).then(function (response) {
                console.log(response.data);
            })
        },
        async crearUsuario() {
            const saltRounds = 10;
            // Encriptar la contraseña
            const hashedPassword = await bcrypt.hash(this.password, saltRounds);

            // Aquí enviarías el username y hashedPassword a tu backend (API)
            const userData = {
                username: this.username,
                password: hashedPassword
            };

            // Simulación de envío de datos
            console.log('Datos a enviar:', userData);

            let u = this.username;
            let p = hashedPassword;
            let r = this.rol;
            let url = `https://nuestrocampo.cl/api/users/create.php?name=${u}&pass=${p}&rol=${r}`;

            axios.post(url).then(function (response) {
                console.log(response.data);
            })
        }
    }
}
</script>
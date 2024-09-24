<template>
    <div class="prose max-w-none text-center p-4">
        <h1>Iniciar sesión</h1>
    </div>
    <form @submit.prevent="iniciar_sesion()" class="space-y-6 w-full max-w-sm mx-auto">
        <!-- Nombre de usuario -->
        <div class="form-control">
            <label class="label">
                <span class="label-text font-bold">Nombre de usuario</span>
            </label>
            <input type="email" v-model="username" placeholder="Nombre de usuario"
                class="input input-bordered w-full" />
        </div>
        <!-- Contraseña -->
        <div class="form-control">
            <label class="label">
                <span class="label-text font-bold">Contraseña</span>
            </label>
            <input type="password" v-model="password" placeholder="Contraseña" class="input input-bordered w-full" />
        </div>
        <button type="submit" class="btn btn-outline btn-success w-full">Iniciar sesión</button>
    </form>
</template>

<script>
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, signOut } from 'firebase/auth';
import axios from 'axios';

export default {
    //Nombre del componente
    name: "Admin",

    data() {
        return {
            username: '',
            password: '',
        };
    },

    methods: {
        iniciar_sesion() {
            console.log('iniciando sesion')
            //GET users WHERE username = this.username

            //this.storedHash = result.data

            /**
             * bcrypt.compare(inputPassword, storedHash, function(err, result) {
                    if (result) {
                        // La contraseña es correcta
                    } else {
                        // La contraseña es incorrecta
                    }
                });

             */
        }
    }
}
</script>
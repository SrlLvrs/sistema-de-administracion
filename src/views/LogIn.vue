<template>
    <div class="prose max-w-none text-center p-4">
        <h1>Iniciar sesión</h1>
    </div>
    <form @submit.prevent="iniciar_sesion()" class="space-y-6 w-full max-w-sm mx-auto">
        <div class="form-control">
            <label class="label">
                <span class="label-text font-bold">Correo electrónico</span>
            </label>
            <input type="email" v-model="email" placeholder="Correo electrónico" class="input input-bordered w-full" />
        </div>
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
            email: '',
            password: '',
        };
    },

    methods: {
        iniciar_sesion(){
            console.log('iniciando sesion')
            signInWithEmailAndPassword(getAuth(), this.email, this.password)
                .then((userCredential) => {
                    console.log("userCredential", userCredential);
                    console.log('Sesión de admin iniciada');
                })
                .then(() => {
                    this.$router.push({ name: 'Inicio' });
                })
                .catch((error) => {
                    const errorCode = error.code;
                    const errorMessage = error.message;
                    console.log(errorCode, errorMessage);
                });
        }
    }
}
</script>
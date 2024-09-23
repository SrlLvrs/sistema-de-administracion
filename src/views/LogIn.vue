<template>
    <form @submit.prevent="logIn()" class="space-y-6 w-full max-w-sm mx-auto">
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
    <button @click="currentUser()" class="btn btn-outline btn-success">Current User</button>
</template>

<script>
import { getAuth, signInWithEmailAndPassword } from 'firebase/auth';

export default {
    //Nombre del componente
    name: "Admin",

    data() {
        return {
            email: '',
            password: '',
            uid: '',
        };
    },

    methods: {
        logIn() {
            signInWithEmailAndPassword(getAuth(), this.email, this.password)
                .then((userCredential) => {
                    console.log("userCredential", userCredential);
                    this.uid = userCredential.user.uid;
                })
                .catch((error) => {
                    const errorCode = error.code;
                    const errorMessage = error.message;
                    console.log(errorCode, errorMessage);
                });
        },
        currentUser() {
            const user = getAuth().currentUser;
            if (user) {
                console.log("UID:", user.uid);
                console.log("Correo electrónico:", user.email);
            } else {
                console.log("No hay usuario actual");
            }
        },
    }
}
</script>
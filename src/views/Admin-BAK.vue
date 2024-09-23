<template>
    <div class="prose max-w-none text-center p-4">
        <h1>Crea un nuevo usuario</h1>
    </div>
    <form @submit.prevent="createUser_old()" class="space-y-6 w-full max-w-sm mx-auto">
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
        <button type="submit" class="btn btn-outline btn-success w-full">Crear cuenta</button>
    </form>
    <button @click="currentUser()" class="btn btn-outline btn-success">Current User</button>
    <button @click="signOut()" class="btn btn-outline btn-error">Sign Out</button>
</template>

<script>
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, signOut } from 'firebase/auth';

export default {
    //Nombre del componente
    name: "Admin",

    data() {
        return {
            email: '',
            password: '',
            uid: '',
            email_admin: 'iolivares0505@gmail.com',
            password_admin: 'asdjkl1289',
        };
    },

    methods: {
        createUser_old() {
            createUserWithEmailAndPassword(getAuth(), this.email, this.password)
                .then((userCredential) => {
                    console.log("userCredential", userCredential);
                    this.uid = userCredential.user.uid;
                    console.log('Usuario creado. UID:', this.uid);
                    return signOut(getAuth());
                })
                .then(() => {
                    console.log('Usuario creado, pero la sesión se cerró');
                })
                .then(() => {
                    signInWithEmailAndPassword(getAuth(), this.email_admin, this.password_admin)
                        .then((userCredential) => {
                            console.log("userCredential", userCredential);
                            console.log('Sesión de admin iniciada');
                        })  
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
        signOut() {
            signOut(getAuth())
                .then(() => {
                    console.log('Sesion cerrada');
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>
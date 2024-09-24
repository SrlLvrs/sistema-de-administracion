<template>
    <div class="prose max-w-none text-center p-4">
        <h1>Crea un nuevo usuario</h1>
    </div>
    <form @submit.prevent="createUser_old()" class="space-y-6 w-full max-w-sm mx-auto">
        <div class="form-control">
            <label class="label">
                <span class="label-text font-bold">Nombre</span>
            </label>
            <input type="text" v-model="nombre" placeholder="Nombre" class="input input-bordered w-full" />
        </div>
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
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, signOut } from 'firebase/auth';
import axios from 'axios';

export default {
    //Nombre del componente
    name: "Admin",

    data() {
        return {
            nombre: '',
            email: '',
            password: '',
            rol: '',
            uid: '',
            ea: 'iolivares0505@gmail.com',
            pa: 'asdjkl1289',
        };
    },

    methods: {
        user_to_db() {
            let n = this.nombre;
            let r = this.rol;
            let u = this.uid;
            let url = `https://nuestrocampo.cl/api/users/create.php?name=${n}&rol=${r}&uid=${u}`;

            axios.post(url).then(function (response) {
                console.log(response.data);
            })
        },
        createUser_old() {
            createUserWithEmailAndPassword(getAuth(), this.email, this.password)
                .then((userCredential) => {
                    console.log("userCredential", userCredential);
                    this.uid = userCredential.user.uid;
                    console.log('Usuario creado. UID:', this.uid);
                    return signOut(getAuth());
                })
                .then(() => {
                    this.user_to_db()
                })
                .then(() => {
                    console.log('Usuario creado, pero la sesión se cerró');
                })
                .then(() => {
                    signInWithEmailAndPassword(getAuth(), this.ea, this.pa)
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
    }
}
</script>
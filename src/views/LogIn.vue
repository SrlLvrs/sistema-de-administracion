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
            <input type="text" v-model="username" placeholder="Nombre de usuario" class="input input-bordered w-full" />
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
import axios from 'axios';
import bcrypt from 'bcryptjs';

export default {
    //Nombre del componente
    name: "Admin",

    data() {
        return {
            username: '',
            password: '',
            storedHash: '',
            rol: '',
        };
    },

    methods: {
        async iniciar_sesion() {
            let self = this;
            let u = this.username
            let url = `https://nuestrocampo.cl/api/users/read-hash.php?username=${u}`

            await axios.get(url).then((response) => {
                console.log(response.data)
                this.storedHash = response.data[0].pass;
                this.rol = response.data[0].rol // Ahora this se refiere al contexto correcto
                console.log(this.storedHash)
                console.log(this.rol)
            })

            bcrypt.compare(this.password, this.storedHash, function (err, result) {
                if (result) {
                    console.log('La contraseña es correcta')
                    // Almacenar la información de inicio de sesión en localStorage
                    const sessionData = {
                        username: u,
                        rol: self.rol,
                        loginTime: new Date().toISOString()
                    };

                    // Guardar en localStorage como lo hace Firebase Auth
                    localStorage.setItem('authUser', JSON.stringify(sessionData));

                    console.log('Inicio de sesión exitoso, datos guardados:', sessionData);

                    self.$router.push({ name: 'Inicio' });
                } else {
                    alert('La contraseña es incorrecta')
                    self.password = ''
                }
            });
        }
    }
}
</script>
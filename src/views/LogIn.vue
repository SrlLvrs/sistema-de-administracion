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
            id: '',
        };
    },

    methods: {
        async iniciar_sesion() {
            let self = this;
            let u = this.username
            let url = `https://nuestrocampo.cl/demo/users/read-hash.php?username=${u}`

            await axios.get(url).then((response) => {
                this.storedHash = response.data[0].Pass;
                this.rol = response.data[0].Rol;
                this.id = response.data[0].ID;
            })

            bcrypt.compare(this.password, this.storedHash, function (err, result) {
                if (result) {
                    // Almacenar la información de inicio de sesión en localStorage
                    const sessionData = {
                        id: self.id,
                        username: u,
                        rol: self.rol,
                        loginTime: new Date().toISOString()
                    };

                    localStorage.setItem('authUser', JSON.stringify(sessionData));

                    console.log('Inicio de sesión exitoso, datos guardados:', sessionData);

                    if (sessionData.rol === 'Colaborador' || sessionData.rol === 'Administrador') {
                        //Redirecionar a la página de inicio
                        self.$router.push({ name: 'Inicio' });
                    } else if (sessionData.rol === 'Repartidor') {
                        //Redirecionar a la página de repartidor
                        self.$router.push({ name: 'PedidosOrdenar' });
                    }
                } else {
                    alert('La contraseña es incorrecta')
                    self.password = ''
                }
            });
        }
    }
}
</script>
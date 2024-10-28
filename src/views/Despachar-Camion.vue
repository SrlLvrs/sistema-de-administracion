<template>
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Despachar camión</h1>
        </div>
        <div class="flex justify-center mb-4">
            <select class="select select-bordered w-full max-w-xs" v-model="selectedUser">
                <option disabled selected>Selecciona un repartidor</option>
                <option v-for="usuario in usuarios.filter(u => u.Rol === 'Repartidor')" :key="usuario.ID"
                    :value="usuario.ID">
                    {{ usuario.Username }}
                </option>
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <!-- Tabla de productos y cantidad -->
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th>Productos</th>
                    <th>Cantidad a despachar</th>
                    <th>Cantidad de ayer</th>
                    <th>Cantidad entregada</th>
                    <th>Cantidad restante</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(producto, rowIndex) in productos" :key="rowIndex">
                    <td>{{ producto.descripcion }}</td>
                    <td>
                        <input v-model.number="producto.cantidad" type="number" placeholder="Ingresa la cantidad"
                            class="input input-bordered w-full max-w-xs mb-2" />
                    </td>
                    <td>{{ getCantidadAyer(producto.id) }}</td>
                    <td>{{ getCantidadEntregada(producto.id) }}</td>
                    <td>{{ getCantidadRestante(producto.id) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end p-6">
            <button class="btn btn-outline btn-success" @click="despacharCamion">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                Despachar camión</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            productos: [],
            usuarios: [],
            selectedUser: null,
            despachos: [],
            despachados: [],
        };
    },
    async mounted() {
        await this.getProductos();
        await this.getUsers();
        await this.getDespachos();
        await this.getDespachados();
    },
    methods: {
        async getProductos() {
            const url = 'https://nuestrocampo.cl/demo/productos/read.php';

            try {
                const response = await axios.get(url);
                this.productos = response.data.map(producto => ({
                    ...producto,
                    cantidad: 0
                }));
            } catch (error) {
                console.error('Error al obtener los productos: ', error);
            }
        },
        async getUsers() {
            let url = `https://nuestrocampo.cl/demo/users/read.php`;
            await axios.get(url).then((response) => (this.usuarios = response.data));
        },
        async getDespachos() {
            const url = 'https://nuestrocampo.cl/demo/despacho/read.php';
            try {
                const respuesta = await axios.get(url);
                this.despachos = respuesta.data;
            } catch (error) {
                console.error('Error al obtener los despachos:', error);
            }
        },
        async getDespachados() {
            const url = 'https://nuestrocampo.cl/demo/despacho/read-entregados.php';
            try {
                const respuesta = await axios.get(url);
                this.despachados = respuesta.data;
            } catch (error) {
                console.error('Error al obtener los productos despachados:', error);
            }
        },
        async despacharCamion() {
            // Aquí implementarías la lógica para despachar el camión
            console.log('Despachando camión...');

            if (!this.selectedUser) {
                alert('No se ha seleccionado un repartidor');
                return;
            }

            // Obtener la fecha de hoy en formato MySQL (YYYY-MM-DD)
            const fechaHoy = new Date().toISOString().slice(0, 10);

            // Ejemplo: enviar los datos de cantidad, repartidor y fecha al servidor
            for (let producto of this.productos) {
                if (producto.cantidad > 0) {
                    try {
                        await axios.post(`https://nuestrocampo.cl/demo/despacho/create.php?idproducto=${producto.id}&cantidad=${producto.cantidad}&idrepartidor=${this.selectedUser}&fecha=${fechaHoy}`);
                    } catch (error) {
                        console.error(`Error al despachar ${producto.descripcion}:`, error);
                    }
                }
            }
            // Reiniciar las cantidades después del despacho
            this.productos.forEach(producto => producto.cantidad = 0);
            // Reiniciar el repartidor seleccionado
            this.selectedUser = null;

            location.reload();
        },
        getCantidadAyer(idProducto) {
            if (!this.selectedUser) return 0;

            const despachoAyer = this.despachos.find(d =>
                d.IDRepartidor == this.selectedUser && d.IDProducto == idProducto
            );

            return despachoAyer ? Number(despachoAyer.CantidadAyer) : 0;
        },
        getCantidadEntregada(idProducto) {
            if (!this.selectedUser) return 0;

            const despachado = this.despachados.find(d =>
                d.IDRepartidor == this.selectedUser && d.IDProducto == idProducto
            );

            return despachado ? Number(despachado.cantidadEntregada) : 0;
        },
        getCantidadRestante(idProducto) {
            if (!this.selectedUser) return '-';

            const cantidadAyer = this.getCantidadAyer(idProducto);
            const cantidadEntregada = this.getCantidadEntregada(idProducto);
            const cantidadADespachar = Number(this.productos.find(p => p.id === idProducto).cantidad || 0);

            return cantidadADespachar + cantidadAyer - cantidadEntregada;
        },
        getCantidadHoy(idProducto) {
            if (!this.selectedUser) return 0;
            const despachoHoy = this.despachos.find(d =>
                d.IDRepartidor == this.selectedUser && d.IDProducto == idProducto
            );
            return despachoHoy ? despachoHoy.CantidadHoy : 0;
        },
        actualizarCantidades() {
            this.productos.forEach(producto => {
                producto.cantidad = this.getCantidadHoy(producto.id);
            });
        },
    },
    watch: {
        selectedUser() {
            this.actualizarCantidades();
            this.$forceUpdate();
        }
    }
}
</script>

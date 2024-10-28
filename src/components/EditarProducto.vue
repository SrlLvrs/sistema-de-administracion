<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-warning">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box">
            <h3 class="text-lg font-bold mb-2 text-center">Editar producto</h3>
            
            <!-- Descripción -->
            <div class="label">
                <span class="label-text font-bold">Descripción</span>
            </div>
            <input v-model="localDescripcion" type="text" placeholder="Huevos 1era Categoría, Bandeja 30un"
                class="input input-bordered w-full max-w-xs mb-2" />

            <!-- Precio -->
            <div class="label">
                <span class="label-text font-bold">Precio</span>
            </div>
            <input v-model="localPrecio" type="number" placeholder="7500"
                class="input input-bordered w-full max-w-xs mb-2" />

            <!-- Acciones -->
            <div class="modal-action">
                <label :for="label" class="btn">Salir</label>

                <button class="btn btn-outline btn-warning" @click="editarProducto(id)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Editar producto
                </button>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    //Nombre del componente
    name: "EditarProducto",

    props: {
        label: String,
        id: Number,
        descripcion: String,
        precio: Number,
    },

    data() {
        return {
            localDescripcion: this.descripcion,
            localPrecio: this.precio,
        };
    },

    methods: {
        editarProducto(id) {
            let d = this.localDescripcion;
            let p = this.localPrecio;
            let url = `https://nuestrocampo.cl/demo/productos/update.php?id=${id}&descripcion=${d}&precio=${p}`
            axios.put(url);
            location.reload();
        },
    },
};
</script>
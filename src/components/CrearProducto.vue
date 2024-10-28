<template>
    <button class="btn btn-outline btn-success mb-4" onclick="crearProductoModal.showModal()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>

        Crear nuevo producto
    </button>
    <!-- Modal CREAR NUEVO PRODUCTO -->

    <dialog id="crearProductoModal" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Nuevo producto</h3>

            <div class="label">
                <span class="label-text">Descripción</span>
            </div>
            <input v-model="descripcion" type="text" placeholder="Huevos 1era Categoría, Bandeja 30un"
                class="input input-bordered w-full max-w-xs mb-2" />

                <div class="label">
                <span class="label-text">Precio</span>
            </div>
            <input v-model="precio" type="number" placeholder="7500"
                class="input input-bordered w-full max-w-xs mb-2" />


            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-outline btn-success mr-2" @click="crearProducto()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Crear Producto</button>
                    <button class="btn btn-outline btn-error">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Salir</button>
                </form>
            </div>
        </div>
    </dialog>
</template>

<script>
import axios from "axios";

export default {
    //Nombre del componente
    name: "CrearProducto",

    data() {
        return {
            descripcion: '',
            precio: '',
        };
    },

    computed: {
        
    },

    methods: {
        crearProducto() {
            let d = this.descripcion;
            let p = this.precio;
            let url = `https://nuestrocampo.cl/demo/productos/create.php?descripcion=${d}&precio=${p}`
            axios.post(url);
            location.reload();
        },
    },
};
</script>
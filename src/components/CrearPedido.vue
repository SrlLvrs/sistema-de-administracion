<template>
    <!-- Modal CREAR NUEVO PEDIDO-->
    <!-- Botón para abrir el modal -->
    <!-- ACÁ SE NECESITA UN ID UNICO PARA PODER ABRIR EL MODAL, SINO ABRIRÁ EL MODAL DE ELIMINAR CL -->
    <label :for="id" class="btn btn-outline btn-success mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="id" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Nuevo Pedido</h3>
            <p> ACÁ VA LA INFO DEL PEDIDO </p>
            <div class="modal-action">
                <label :for="id" class="btn">Salir</label>
                <button class="btn btn-outline btn-success" @click="editarSector(id)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Crear Pedido
                </button>
            </div>
        </div>
    </div>


</template>

<script>
import axios from "axios";

export default {
    //Nombre del componente
    name: "CrearPedidoModal",

    props: {
        id: Number,
    },

    data() {
        return {
            //Array para guardar datos de la API
            sectores: [],
        };
    },

    computed: {

    },

    methods: {

    },

    created() {

    },
};
</script>
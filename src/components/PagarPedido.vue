<template>
    <!-- Botón para abrir el modal -->
    <label :for="label" class="btn btn-outline btn-success mr-2" @click="pagarPedido()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
    </label>

    <!-- Modal -->
    <input type="checkbox" :id="label" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box modal-pedido">
            <h3 class="text-lg font-bold mb-2 text-center">Pagar el pedido {{ id }}</h3>

            <p>¿Quieres marcar el pedido {{ id }} como pagado?</p>

            <!-- Acciones -->
            <div class="modal-action">
                <label :for="label" class="btn btn-outline btn-error mr-2">No!</label>
                <label class="btn btn-outline btn-success mr-2">Si, marcar como pagado</label>
            </div>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
    //Nombre del componente
    name: "PagarPedido",

    props: {
        id: Number,
        label: String,
    },

    data() {
        return {
            items: [],
        };
    },

    methods: {
        //Obtiene el detalle del pedido desde la Base de Datos
        pagarPedido() {
            console.log('pedido pagado!')
            console.log(this.id)
        },
    },
};
</script>
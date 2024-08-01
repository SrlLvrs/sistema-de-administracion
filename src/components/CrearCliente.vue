<template>
    <button class="btn btn-outline btn-success mb-4" onclick="crearSectorModal.showModal()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>

        Crear nuevo cliente
    </button>
    <!-- Modal CREAR NUEVO SECTOR -->

    <dialog id="crearSectorModal" class="modal">
        <div class="modal-box">

            <h3 class="text-lg font-bold mb-2 text-center">Nuevo cliente</h3>

            <div class="label">
                <span class="label-text font-bold">Nombre</span>
            </div>
            <input v-model="nombre" type="text" placeholder="Ingresa el nombre del cliente"
                class="input input-bordered w-full max-w-xs mb-2" />

            <div class="label">
                <span class="label-text font-bold">Comuna</span>
            </div>
            <select v-model="ciudadSeleccionada" class="select select-bordered w-full max-w-xs">
                <option v-for="ciudad in ciudades" :key="ciudad" :value="ciudad">{{ ciudad }}</option>
            </select>

            <div class="label">
                <span class="label-text font-bold">Sector</span>
            </div>
            <select v-model="sectorSeleccionado" class="select select-bordered w-full max-w-xs">
                <option v-for="sector in sectoresFiltrados" :key="sector.id" :value="sector.id">{{
                sector.nombreSector }}</option>
            </select>
            <p v-if="diaReparto">Día de reparto sugerido: {{ diaReparto }}</p>

            <div class="label">
                <span class="label-text font-bold">Día de reparto excepcional</span>
            </div>
            <select v-model="diareparto" class="select select-bordered w-full max-w-xs">
                <option v-for="dia in diasdelasemana">
                    {{ dia }}
                </option>
            </select>

            <div class="label">
                <span class="label-text font-bold">Dirección</span>
            </div>
            <input v-model="direccion" type="text" placeholder="Ingresa la dirección del cliente"
                class="input input-bordered w-full max-w-xs mb-2">

            <div class="label">
                <span class="label-text font-bold">Teléfono</span>
            </div>
            <input v-model="telefono" type="text" placeholder="Ej. 56922446688"
                class="input input-bordered w-full max-w-xs mb-2">

            <div class="label">
                <span class="label-text font-bold">Teléfono 2</span>
            </div>
            <input v-model="telefono2" type="text" placeholder="Ej. 56922446688"
                class="input input-bordered w-full max-w-xs mb-2">

            <div class="label">
                <span class="label-text font-bold">Link de Google Maps</span>
            </div>
            <input v-model="linkmaps" type="text" placeholder="https://maps.app.goo.gl/..."
                class="input input-bordered w-full max-w-xs mb-2">

            <div class="label">
                <span class="label-text font-bold">Frecuencia</span>
            </div>
            <select v-model="frecuenciaSeleccionada" class="select select-bordered w-full max-w-xs">
                <option v-for="frecuencia in frecuencias">
                    {{ frecuencia }}
                </option>
            </select>

            <div class="label">
                <span class="label-text font-bold">Observaciones</span>
            </div>
            <input v-model="observaciones" type="text" placeholder="Casa esquina. Árbol afuera."
                class="input input-bordered w-full max-w-xs mb-2">

            <div class="label">
                <span class="label-text font-bold">Preferencia</span>
            </div>
            <select v-model="productoSeleccionado" class="select select-bordered w-full max-w-xs">
                <option v-for="producto in productos" :key="producto" :value="producto.id">{{ producto.descripcion }}</option>
            </select>

            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-outline btn-success mr-2" @click="crearCliente()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Añadir cliente</button>
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
    name: "CrearClienteModal",

    data() {
        return {
            //Array para guardar datos de la API
            sectores: [],
            productos: [],
            nombre: "", //Nombre del cliente
            nombresector: "",
            comuna: "",
            direccion: "",
            telefono: "",
            telefono2: "",
            linkmaps: "",
            diareparto: '', //dre
            observaciones: '',
            frecuenciaSeleccionada: "Ninguna",
            frecuencias: ['Ninguna', 'Cada 1 semana', 'Cada 2 semanas', 'Cada 3 semanas', '1 vez al mes'],
            diasdelasemana: ['Lunes', 'Martes', 'Miércoles', 'Jueves', "Viernes", "Sábado", "Domingo"],
            ciudadSeleccionada: null,
            sectorSeleccionado: null, //ID del sector
            productoSeleccionado: 1,
        };
    },

    computed: {
        ciudades() {
            // Obtener una lista única de comunas (ciudades)
            const ciudadesUnicas = new Set(this.sectores.map(sector => sector.comuna));
            return Array.from(ciudadesUnicas);
        },
        sectoresFiltrados() {
            return this.sectores.filter(sector => sector.comuna === this.ciudadSeleccionada);
        },
        diaReparto() {
            const sector = this.sectores.find(sector => sector.id === this.sectorSeleccionado);
            return sector ? sector.diaReparto : null;
        },
    },

    methods: {
        crearCliente() {
            let n = this.nombre;
            let d = this.direccion;
            let ids = this.sectorSeleccionado;
            let t = this.telefono;
            let t2 = this.telefono2;
            let l = this.linkmaps;
            let f = this.frecuenciaSeleccionada;
            let o = this.observaciones;
            let p = this.productoSeleccionado;
            let dre = this.diareparto;

            if (dre === "") {
                // Si diareparto está vacío, ejecuta el código aquí
                let url = `https://nuestrocampo.cl/api/clientes/create.php?nombre=${n}&direccion=${d}&idsector=${ids}&telefono=${t}&telefono2=${t2}&linkmaps=${l}&frecuencia=${f}&observacion=${o}&preferencia=${p}`
                axios.post(url);
                location.reload();
            } else {
                // Si diareparto no está vacío, ejecuta el código aquí
                let url = `https://nuestrocampo.cl/api/clientes/create.php?nombre=${n}&direccion=${d}&idsector=${ids}&telefono=${t}&telefono2=${t2}&linkmaps=${l}&frecuencia=${f}&observacion=${o}&preferencia=${p}&dre=${dre}`
                console.log("diareparto no está vacío");
                axios.post(url);
                location.reload();
            }
        },
    },

    created() {
        //Leer sectores
        let url = "https://nuestrocampo.cl/api/sectores/read.php";

        axios.get(url).then((response) => (this.sectores = response.data));
        
        let url2 = "https://nuestrocampo.cl/api/productos/read.php";
        
        axios.get(url2).then((response) => (this.productos = response.data));
    },
};
</script>
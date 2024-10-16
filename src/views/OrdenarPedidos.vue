<template>
  <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
  <!-- ENCABEZADO -->
  <div class="prose max-w-none">
    <div class="grid grid-cols-1">
      <h1 class="text-center p-4 m-0">Ordenar pedidos</h1>
      <p class="text-center p-4 m-0">Arrastra los pedidos para ordenarlos y presiona en guardar</p>
      <div class="flex justify-center mb-4">
        <label class="btn btn-outline btn-info" @click="guardar()">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
          </svg>
          Guardar</label>
      </div>
    </div>
  </div>
  <!-- Resultados -->
  <div v-if="items.length > 0" class="overflow-x-auto">
    <table class="table table-zebra">
      <thead>
        <tr>
          <th></th>
          <th>Número de pedido</th>
          <th>Cliente</th>
          <th>Dirección</th>
          <th>Teléfono</th>
          <th>Teléfono 2</th>
          <th>Pagado</th>
          <th>Fecha de Entrega</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in items" :key="index" 
            draggable="true" 
            @dragstart="dragStart(index)"
            @drop="drop(index)" 
            @dragover.prevent
            @touchstart="touchStart(index)"
            @touchmove.prevent="touchMove"
            @touchend="touchEnd(index)">
          <td>
            <div v-if="item.IDPA" class="tooltip tooltip-right" data-tip="Pedido creado automáticamente">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
              </svg>
            </div>
          </td>
          <td> {{ item.ID }}</td>
          <td> {{ item.Nombre }}</td>
          <td> {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }} </td>
          <td> {{ item.Telefono }} </td>
          <td> {{ item.Telefono2 }} </td>
          <td> {{ item.Pagado }} </td>  
          <td> {{ item.FechaEntrega }} </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div v-else class="flex justify-center items-center h-screen">
    <p class="text-center">No hay pedidos asignados a ti, o no eres repartidor. Si crees que es un error, comunícate con
      el administrador.</p>
  </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";

export default {
  data() {
    return {
      items: [],
      draggedIndex: null,
      ids: [],
      touchStartIndex: null,
      touchEndIndex: null,
    };
  },
  methods: {
    checkUserSession() {
      const sessionData = JSON.parse(localStorage.getItem('authUser'));
      return sessionData ? sessionData : null;
    },
    dragStart(index) {
      this.draggedIndex = index;
    },
    drop(index) {
      const draggedItem = this.items.splice(this.draggedIndex, 1)[0];
      this.items.splice(index, 0, draggedItem);
      this.draggedIndex = null;
    },
    touchStart(index) {
      this.touchStartIndex = index;
    },
    touchMove(event) {
      // Opcional: puedes agregar lógica aquí si quieres feedback visual durante el movimiento
    },
    touchEnd(index) {
      this.touchEndIndex = index;
      if (this.touchStartIndex !== null && this.touchEndIndex !== null) {
        const itemToMove = this.items[this.touchStartIndex];
        this.items.splice(this.touchStartIndex, 1);
        this.items.splice(this.touchEndIndex, 0, itemToMove);
      }
      this.touchStartIndex = null;
      this.touchEndIndex = null;
    },
    guardar() {
      for (let i = 0; i < this.items.length; i++) {
        console.log('index: ' + i)
        let url = `https://nuestrocampo.cl/api/pedidos/order.php?orden=${i}&idp=${this.items[i].ID}`;
        axios.put(url)        
      }

      location.reload()
    }
  },

  mounted() {
        const sessionData = this.checkUserSession();
        if (sessionData) {
            console.log('Sesión iniciada');
            let id_user = sessionData.id;
            if (sessionData.rol === 'Repartidor') {
                let url = `https://nuestrocampo.cl/api/pedidos/read-week-deliver.php?id=${id_user}`;
                axios.get(url).then((response) => (this.items = response.data));
            } else {
                console.log('El usuario no es repartidor');
            }
        } else {
            console.log('No hay sesión iniciada');
            this.$router.push({ name: 'LogIn' });
        }
    },
};
</script>

<style scoped>
tr {
  cursor: move;
}
</style>

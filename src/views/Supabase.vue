// App.vue
<template>
  <div>
    <h1>Datos de Sectores</h1>
    {{ sector }}
    <ul>
      <li v-for="item in sector" :key="item.id">{{ item.nombre }}</li>
    </ul>
    <p v-if="error">Error al obtener los datos: {{ error.message }}</p>
  </div>
</template>

<script>
import { supabase } from '../supabase';

export default {
  name: 'App',
  data() {
    return {
      sector: [],  // Almacena los datos de sectores
      error: null  // Almacena el error, si existe
    };
  },
  mounted() {
    this.fetchData(); // Llama a la función para obtener datos cuando el componente se monta
  },
  methods: {
    async fetchData() {
      const { data, error } = await supabase
        .from('sector')
        .select('*');

      if (error) {
        console.error('Error al obtener los datos:', error);
        this.error = error; // Guarda el error en la propiedad
      } else {
        this.sector = data; // Guarda los datos en la propiedad
      }

      console.log(this.sector)
    }
  }
};
</script>

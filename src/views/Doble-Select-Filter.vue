<template>
    <div>
      <!-- Filtro de días de la semana -->
      <select v-model="selectedDay">
        <option value="">Todos los días</option>
        <option value="lunes">Lunes</option>
        <option value="martes">Martes</option>
        <option value="miércoles">Miércoles</option>
        <option value="jueves">Jueves</option>
        <option value="viernes">Viernes</option>
        <option value="sábado">Sábado</option>
        <option value="domingo">Domingo</option>
      </select>
  
      <!-- Filtro de colores -->
      <select v-model="selectedColor">
        <option value="">Todos los colores</option>
        <option value="rojo">Rojo</option>
        <option value="azul">Azul</option>
        <option value="verde">Verde</option>
        <option value="amarillo">Amarillo</option>
      </select>
  
      <ul>
        <li v-for="item in filteredItems" :key="item.id">{{ item.nombre }} - {{ item.dia }} - {{ item.color }}</li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        selectedDay: "",
        selectedColor: "",
        items: [
          { id: 1, nombre: "Reunión", dia: "lunes", color: "rojo" },
          { id: 2, nombre: "Gym", dia: "martes", color: "azul" },
          { id: 3, nombre: "Supermercado", dia: "miércoles", color: "verde" },
          { id: 4, nombre: "Doctor", dia: "jueves", color: "amarillo" },
          { id: 5, nombre: "Cine", dia: "viernes", color: "rojo" },
          { id: 6, nombre: "Compras", dia: "sábado", color: "azul" },
          { id: 7, nombre: "Descanso", dia: "domingo", color: "verde" }
          // Puedes añadir más elementos y colores primarios (rojo, azul, verde, amarillo) de manera pseudo aleatoria.
        ]
      };
    },
    computed: {
      filteredItems() {
        const normalizeText = (text) => {
          return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        };
  
        const selectedDayNormalized = normalizeText(this.selectedDay);
        const selectedColorNormalized = normalizeText(this.selectedColor);
  
        return this.items.filter(item => {
          const matchesDay = !selectedDayNormalized || normalizeText(item.dia) === selectedDayNormalized;
          const matchesColor = !selectedColorNormalized || normalizeText(item.color) === selectedColorNormalized;
          return matchesDay && matchesColor;
        });
      }
    }
  };
  </script>
  
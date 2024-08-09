<template>
  <VDatePicker v-model.string="fecha" :masks="masks" :is-dark="true" transparent/>
</template>

<script>
export default {
   props: {
    fecha_reparto: String,
  },
   
  data() {
    return {
      fecha: this.fecha_reparto,
      masks: {
        modelValue: 'YYYY-MM-DD'
      }
    };
  },
};
</script>
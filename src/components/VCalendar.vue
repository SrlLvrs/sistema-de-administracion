<template>
  <VDatePicker v-model.string="fecha" :masks="masks" :is-dark="true" transparent/>
</template>

<script>
export default {
  data() {
    return {
      fecha: '',
      masks: {
        modelValue: 'YYYY-MM-DD'
      }
    };
  },
};
</script>
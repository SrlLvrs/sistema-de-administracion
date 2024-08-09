<template>
  <VDatePicker v-model.string="fecha.fecha" :masks="masks" />
  <p>Fecha de reparto desde la BD: {{ fecha_reparto }}</p>
  {{ fecha.fecha }}
</template>

<script setup>
import { reactive, ref } from 'vue';

const props = defineProps({
  fecha_reparto: String
})

const fecha = reactive({
  fecha: '',
});

const masks = ref({
  modelValue: 'DD-MM-YYYY',
});

</script>
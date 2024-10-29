<template>
  <VDatePicker v-model="date" transparent>
    <template #default="{ inputValue, inputEvents }">
      <input
        :value="inputValue"
        v-on="inputEvents"
        class="input input-bordered w-full max-w-xs"
        placeholder="Selecciona una fecha"
      />
    </template>
  </VDatePicker>
</template>

<script>
import { ref } from 'vue';

export default {
  setup() {
    const date = ref(new Date());
    return { date };
  }
}
</script>

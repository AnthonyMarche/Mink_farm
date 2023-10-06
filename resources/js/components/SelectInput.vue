<script setup>
import { ref } from 'vue';

defineProps({
  options: {
    type: Object,
    required: true,
  },
  defaultOption: {
    type: String,
    required: true
  }
});

const selected = ref(null);

defineExpose({ focus: () => selected.value.focus() });
</script>

<template>
  <select
      v-model="selected"
      :disabled="options.length === 0"
      :class="{'disabled-select': options.length === 0}"
  >
    <option :value=null selected>{{ defaultOption }}</option>
    <option v-for="option in options" :value="option.id">
      {{ option.name }}
    </option>
  </select>
</template>

<style>
.disabled-select {
  background-color: #bfbfbf;
}
</style>

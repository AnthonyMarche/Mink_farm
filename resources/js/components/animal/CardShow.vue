<template>
  <div class="flex flex-col">
    <h3 class="text-center text-xl">
      {{animal.name}} - {{animal.age}} {{ animal.age <= 1 ? 'an' : 'ans' }}
    </h3>
    <div>
      <ul class="flex justify-around py-6">
        <li>Type : {{animal.breed.type.name}}</li>
        <li>Race : {{animal.breed.name}}</li>
        <li>Prix : {{animal.price_TTC}} €</li>
      </ul>
      {{animal.description}}
    </div>
  </div>
</template>

<script setup>
defineProps({
  animal: {
    type: Object,
  },
});
</script>

<style scoped>

</style>

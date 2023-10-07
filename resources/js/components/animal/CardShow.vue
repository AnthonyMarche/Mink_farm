<script setup>
import DeleteAnimal from "@/components/animal/DeleteAnimal.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import {router, usePage} from "@inertiajs/vue3";

defineProps({
  animal: {
    type: Object,
  },
});
const user = usePage().props.auth.user;

function redirectToUpdate(animalId) {
    router.visit('/animals/' + animalId + '/edit');
}
</script>

<template>
  <div class="flex flex-col">
    <div class="flex justify-end">
      <div v-if="animal.sale_status === 'vendu'" class="bg-red-700 w-fit p-2 rounded-lg">VENDU</div>
    </div>
    <h3 class="text-center text-xl">
      {{animal.name}} - {{animal.age}} {{ animal.age <= 1 ? 'an' : 'ans' }}
    </h3>
    <div>
      <ul class="flex justify-around py-6">
        <li>Type : {{animal.breed.type.name}}</li>
        <li>Race : {{animal.breed.name}}</li>
        <li>Prix {{user ? 'HT : ' + animal.price_ht : 'TTC : ' + animal.price_TTC}} €</li>
      </ul>
      {{animal.description}}
      <div v-if="user" class="flex justify-end mt-4 mx-2">
          <SecondaryButton @click="redirectToUpdate(animal.id)" class="mr-5">Modifier</SecondaryButton>
          <delete-animal :animal-id="animal.id" @refreshAnimals="$emit('refreshAnimals')" />
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>

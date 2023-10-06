<script setup>
import {Head} from '@inertiajs/vue3';
import ShowAnimals from "@/components/animal/ShowAnimals.vue";
import {ref} from "vue";

const props = defineProps({
  types: {
    type: Object,
  },
  breeds: {
    type: Object,
  },
});

const animals = ref([]);

function updateAnimals(newAnimals) {
  animals.value = newAnimals;
}
</script>

<template>
  <Head title="Accueil"/>
  <div
      class="body"
      :class="{
    'h-screen': !animals.length,
    'h-full': animals.length
  }">
    <div>
      <img src="/storage/images/ferme_background.png" alt="ferme image" class="background-img">
      <h1 class="text-center text-5xl font-bold title">Mink Farm</h1>
    </div>
    <div class="flex justify-between p-2 mx-14 items-center sell-section">
      <h2 class="text-center font-semibold text-2xl">Nos animaux en vente</h2>
      <p>Pour plus de renseignements, <br> contactez nous au : <a href="tel:+33734567890">07.34.56.78.90</a></p>
    </div>
    <show-animals :breeds="breeds" :types="types" @updateAnimals="updateAnimals" />
  </div>
</template>

<style>
.body {
  background-color: #f7dd8d;
  padding-bottom: 3rem;
}

.background-img {
  max-width: initial;
  width: 100%;
}

.title {
  margin-top: -20%;
  color: #d4a71a;
}

.sell-section {
  margin-top: 18%;
}
</style>

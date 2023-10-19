<script setup>
import {Head, router} from '@inertiajs/vue3';
import ShowAnimals from "@/components/animal/ShowAnimals.vue";
import {ref} from "vue";
import SecondaryButton from "@/components/SecondaryButton.vue";

const props = defineProps({
    types: {
        type: Object,
        required: true
    },
    breeds: {
        type: Object,
        required: true
    },
    animals: {
        type: Object,
        required: true
    },
});
</script>

<template>
    <Head title="Accueil"/>
    <div
        class="body"
        :class="{
    'h-screen': !animals.length,
    'h-full': animals.length
  }">
        <div class="background-container">
            <div class="flex justify-end items-end">
                <SecondaryButton @click="router.visit('/login')">Administration</SecondaryButton>
            </div>
            <div class="h-full flex justify-center items-center">
                <h1 class="text-center text-5xl font-bold title">Mink Farm</h1>
            </div>
        </div>

        <div class="flex justify-between p-2 mx-14 items-center sell-section">
            <h2 class="text-center font-semibold text-2xl">Nos animaux en vente</h2>
            <p>Pour plus de renseignements, <br> contactez nous au : <a href="tel:+33734567890">07.34.56.78.90</a></p>
        </div>
        <show-animals :animals="animals" :types="types" :breeds="breeds"/>
    </div>
</template>

<style>
.body {
    background-color: #fff7de;
    padding-bottom: 3rem;
}

.background-container {
    background-image: url('/storage/images/ferme_background.png');
    background-size: cover;
    height: 62vh;
}

.title {
    @apply p-6 mb-24;
    color: #d4a71a;
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 45%;
}
</style>

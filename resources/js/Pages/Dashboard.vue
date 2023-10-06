<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
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
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <show-animals :types="types" :breeds="breeds" @updateAnimals="updateAnimals"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

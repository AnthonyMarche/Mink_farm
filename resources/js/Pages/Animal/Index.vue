<script setup>
import {Head} from '@inertiajs/vue3';
import ShowAnimals from "@/components/animal/ShowAnimals.vue";
import {ref} from "vue";
import AdminAnimalLayout from "@/Layouts/AdminAnimalLayout.vue";

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
    <Head title="Animaux"/>

    <AdminAnimalLayout>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <show-animals :animals="animals" :types="types" :breeds="breeds"/>
                </div>
            </div>
        </div>
    </AdminAnimalLayout>
</template>

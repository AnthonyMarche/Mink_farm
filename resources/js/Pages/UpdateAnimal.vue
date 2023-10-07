<script setup>

import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AnimalForm from "@/components/animal/AnimalForm.vue";

defineProps({
    animal: {
        type: Object,
    },
    types: {
        type: Object,
        required: true
    },
    breeds: {
        type: Object,
        required: true
    },
});

</script>

<template>
    <Head title="Modifier"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ animal ? 'Modifier' : 'Créer' }}
                animal</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <AnimalForm :breeds="breeds" :types="types" :animal="animal"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>

<script setup>

import {Head} from "@inertiajs/vue3";
import AdminBreedLayout from "@/Layouts/AdminBreedLayout.vue";
import BreedForm from "@/components/Breed/BreedForm.vue";

const props = defineProps({
    types: {
        type: Object,
        required: true
    },
    breed: {
        type: Object,
    },
});

</script>

<template>
    <Head :title="breed ? 'Modifier race' : 'Créer race'"/>

    <AdminBreedLayout>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <BreedForm :types="types" :breed="breed" />
                </div>
            </div>
        </div>
    </AdminBreedLayout>
</template>

<style scoped>

</style>

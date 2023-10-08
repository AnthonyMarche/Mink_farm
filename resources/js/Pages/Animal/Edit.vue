<script setup>

import {Head} from "@inertiajs/vue3";
import AnimalForm from "@/components/animal/AnimalForm.vue";
import AdminAnimalLayout from "@/Layouts/AdminAnimalLayout.vue";
import {toRefs} from "vue";

const props = defineProps({
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

const {animal} = toRefs(props);

</script>

<template>
    <Head :title="animal ? 'Modifier animal' : 'Créer animal'"/>

    <AdminAnimalLayout>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <AnimalForm :breeds="breeds" :types="types" :animal="animal"/>
                </div>
            </div>
        </div>
    </AdminAnimalLayout>
</template>

<style scoped>

</style>

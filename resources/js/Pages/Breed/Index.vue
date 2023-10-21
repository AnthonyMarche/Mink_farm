<script setup>
import {Head, router} from '@inertiajs/vue3';
import {ref, watchEffect} from "vue";
import AdminBreedLayout from "@/Layouts/AdminBreedLayout.vue";
import DeleteWithConfirm from "@/components/Breed/DeleteWithConfirm.vue";
import InputLabel from "@/components/InputLabel.vue";
import SelectInput from "@/components/SelectInput.vue";

const props = defineProps({
    types: {
        type: Object,
    },
    breeds: {
        type: Object,
    },
});

const selectedType = ref(null);
const validBreed = ref(props.breeds)

watchEffect(() => {
    if (selectedType.value === null) {
        validBreed.value = props.breeds;
        return;
    }

    validBreed.value = props.breeds.filter(
        breed => breed.type_id === selectedType.value
    );
});

</script>

<template>
    <Head title="Races"/>

    <AdminBreedLayout>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg pb-8 px-8">
                    <h3 class="text-center font-semibold my-6 mx-12">Mes races d'animaux</h3>
                    <div>
                        <div class="flex items-center px-6">
                            <InputLabel class="mr-4" for="type" value="Type :"/>
                            <SelectInput
                                id="type"
                                :options="types"
                                default-option="Tous les types"
                                v-model="selectedType"
                            />
                        </div>
                        <ul class="mt-10 inline-flex flex-wrap">
                            <li v-for="breed in validBreed"
                                class="flex space-x-6 m-6 px-8 py-4 items-center shadow-lg rounded-lg bg-gray-200">
                                <p class="mr-4">{{ breed.name }}</p>
                                <i @click="router.get(route('breeds.edit', { breed : breed.id }))"
                                   class="fa-solid fa-pen-to-square cursor-pointer"></i>
                                <DeleteWithConfirm
                                    :url="route('breeds.destroy', { breed: breed.id})"
                                    :item="breed"
                                    table="breed"
                                />
                            </li>
                        </ul>
                        <div v-if="!validBreed.length" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <p class="text-center">Aucune race ne correspond à vos critères</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminBreedLayout>
</template>
<style scoped>

</style>

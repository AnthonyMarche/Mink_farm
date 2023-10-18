<script setup>
import DeleteAnimal from "@/components/animal/DeleteAnimal.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import {router} from "@inertiajs/vue3";

defineProps({
    animal: {
        type: Object,
    },
    isAdmin: {
        type: Boolean,
        required: true
    }
});

</script>

<template>
    <div class="flex flex-col">
        <div v-if="isAdmin" class="flex justify-end">
            <div v-if="animal.sale_status === 'vendu'" class="bg-red-700 w-fit p-2 rounded-lg">VENDU</div>
        </div>
        <h3 class="text-center text-xl">
            {{ animal.name }} - {{ animal.age }} {{ animal.age <= 1 ? 'an' : 'ans' }}
        </h3>
        <div>
            <div class="flex justify-around mt-10">
                <img v-if="animal.image_path" :src="'/storage' + animal.image_path" alt="Photo animal" class="w-80 rounded-lg">
                <div class="flex flex-col w-1/2">
                    <ul class="flex justify-around space-x-40 my-12">
                        <li class="flex">
                            <p class="font-bold mr-2">Type :</p> {{ animal.breed.type.name }}
                        </li>
                        <li class="flex">
                            <p class="font-bold mr-2">Race :</p> {{ animal.breed.name }}
                        </li>
                        <li class="flex">
                            <p class="font-bold mr-2">Prix :</p> {{ isAdmin ? 'HT : ' + animal.price_ht : 'TTC : ' + animal.price_TTC }} €
                        </li>
                    </ul>
                    <div class="flex flex-col items-center">
                        <p class="font-bold mb-4">Description :</p>
                        <p class="w-4/5">{{ animal.description }}</p>
                    </div>
                </div>
            </div>
            <div v-if="isAdmin" class="flex justify-end mt-4 mx-2">
                <SecondaryButton
                    @click="router.visit(route('animals.edit', {animal: animal.id}))"
                    class="mr-5"
                >
                    Modifier
                </SecondaryButton>
                <delete-animal :animal-id="animal.id" @refreshAnimals="$emit('refreshAnimals')"/>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>

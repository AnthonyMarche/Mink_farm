<script setup>

import {router, useForm} from "@inertiajs/vue3";
import {ref, toRefs, watch} from "vue";
import TextInput from "@/components/TextInput.vue";
import InputLabel from "@/components/InputLabel.vue";
import InputError from "@/components/InputError.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import Checkbox from "@/components/Checkbox.vue";
import SelectInput from "@/components/SelectInput.vue";
import TextAreaInput from "@/components/TextAreaInput.vue";
import {useToast} from "vue-toastification";

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
const selectedType = ref(null);
const {breeds} = toRefs(props);
const validBreed = ref([]);
const toast = useToast();

watch(selectedType, (newType) => {
    if (!newType) {
        validBreed.value = [];
        return;
    }

    validBreed.value = breeds.value.filter(
        breed => breed.type_id === newType
    );
});

const form = useForm({
    name: animal.value ? animal.value.name : '',
    age: animal.value ? animal.value.age : '',
    description: animal.value ? animal.value.description : '',
    price_ht: animal.value ? animal.value.price_ht : '',
    sale_status: animal.value ? animal.value.sale_status : 1,
    breed_id: animal.value ? animal.value.breed_id : null
});

const submitForm = () => {
    if (animal.value) {
        if (form.sale_status === 'en vente') {
            form.sale_status = 1;
        }
        if (form.sale_status === 'vendu') {
            form.sale_status = 0;
        }

        axios.patch(`/animals/${animal.value.id}`, form)
            .then(response => {
                toast.success("Animal modifié avec succès");
                router.visit('/dashboard');
            })
            .catch(error => {
                const errors = error.response.data.errors;
                for (const field in errors) {
                    form.errors[field] = errors[field][0];
                }
            });
    } else {
        axios.post('/animals', form)
            .then(response => {
                toast.success("Animal Créé avec succès");
                router.visit('/dashboard');
            })
            .catch(error => {
                const errors = error.response.data.errors;
                for (const field in errors) {
                    form.errors[field] = errors[field][0];
                }
            });
    }
};

</script>

<template>
    <form @submit.prevent="submitForm" class="mt-6 space-y-6 flex flex-col items-center">
        <div class="flex flex-col items-center w-3/4">
            <InputLabel for="name" value="Nom" class="w-1/2"/>
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-1/2"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
            />
            <InputError class="mt-2" :message="form.errors.name"/>
        </div>
        <div class="flex flex-col items-center w-3/4">
            <InputLabel for="description" value="Description" class="w-1/2"/>
            <TextAreaInput
                id="name"
                type="text"
                class="mt-1 block w-1/2"
                v-model="form.description"
                required
                autofocus
                autocomplete="description"
            />
            <InputError class="mt-2" :message="form.errors.description"/>
        </div>
        <div class="flex flex-col items-center w-3/4">
            <InputLabel for="age" value="Age"  class="w-1/2"/>
            <TextInput
                id="age"
                type="text"
                class="mt-1 block w-1/2"
                v-model="form.age"
                required
                autocomplete="age"
            />
            <InputError class="mt-2" :message="form.errors.age"/>
        </div>
        <div class="flex flex-col items-center w-3/4">
            <InputLabel class="mr-4 w-1/2" for="type" value="Type :" />
            <SelectInput
                id="type"
                class="mt-1 block w-1/2"
                :options="types"
                :required-option=true
                default-option="Choisissez un type"
                v-model="selectedType"
            />
        </div>
        <div class="flex flex-col items-center w-3/4">
            <InputLabel class="mr-4 w-1/2" for="breed" value="Race :"/>
            <SelectInput
                id="breed"
                class="mt-1 block w-1/2"
                :options="validBreed"
                :required-option=true
                default-option="Choisissez une race"
                v-model="form.breed_id"
            />
            <InputError class="mt-2" :message="form.errors.breed_id"/>
        </div>
        <div class="flex flex-col items-center w-3/4">
            <InputLabel for="price" value="Prix HT" class="w-1/2"/>
            <TextInput
                id="price"
                type="text"
                class="mt-1 block w-1/2"
                v-model="form.price_ht"
                required
                autocomplete="price_HT"
            />
            <InputError class="mt-2" :message="form.errors.price_ht"/>
        </div>
        <div class="flex flex-col items-center w-3/4">
            <InputLabel for="saleStatus" value="Status de vente "/>
            <Checkbox
                id="saleStatus"
                class="mt-1 block"
                v-model="form.sale_status"
                :checked="form.sale_status === 'en vente'"
            />
            <InputError class="mt-2" :message="form.errors.sale_status"/>
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
        </div>
    </form>

</template>

<style scoped>

</style>

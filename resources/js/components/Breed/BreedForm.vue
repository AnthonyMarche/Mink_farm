<script setup>
import {router, useForm} from "@inertiajs/vue3";
import TextInput from "@/components/TextInput.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import {useToast} from "vue-toastification";
import {toRefs} from "vue";
import SelectInput from "@/components/SelectInput.vue";

const props = defineProps({
    types: {
        type: Object,
        required: true
    },
    breed: {
        type: Object
    }
});

const {breed} = toRefs(props);
const toast = useToast();
const form = useForm({
    name: breed.value ? breed.value.name : '',
    type_id: breed.value ? breed.value.type.id : null
});

const submit = () => {
    if (breed.value) {
        axios.put(route('breeds.update', {id: breed.value.id}), form)
            .then(() => {
                toast.success("Race modifiée avec succès")
                router.visit(route('breeds.index'))
            })
            .catch(error => {
                toast.error("Erreur lors de la modification")
                const errors = error.response.data.errors;
                for (const field in errors) {
                    form.errors[field] = errors[field][0];
                }
            })
    } else {
        axios.post(route('breeds.store'), form)
            .then(() => {
                toast.success("Race créée avec succès")
                router.visit(route('breeds.index'))
            })
            .catch(error => {
                toast.error("Erreur lors de la création")
                const errors = error.response.data.errors;
                for (const field in errors) {
                    form.errors[field] = errors[field][0];
                }
            })
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col items-center py-8">
        <div class="w-1/2">
            <InputLabel for="type" value="Type"/>
            <select-input
                v-model="form.type_id"
                id="type"
                class="mt-1 block w-full"
                default-option="Selectionnez un type"
                :options="types"
            />
            <InputError class="mt-2" :message="form.errors.type_id"/>

            <InputLabel for="name" value="Nom de la race" class="mt-4"/>
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
            />
            <InputError class="mt-2" :message="form.errors.name"/>
        </div>
        <div class="flex items-center justify-end mt-6 space-x-8">
            <SecondaryButton @click="router.visit(route('breeds.index'))">Annuler</SecondaryButton>
            <PrimaryButton>Enregistrer</PrimaryButton>
        </div>
    </form>
</template>

<style scoped>

</style>

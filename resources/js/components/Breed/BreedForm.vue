<script setup>
import {router, useForm} from "@inertiajs/vue3";
import TextInput from "@/components/TextInput.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import {useToast} from "vue-toastification";
import {computed, toRefs} from "vue";

const props = defineProps({
    types: {
        type: Object,
        required: true
    },
    breed: {
        type: Object
    }
});

const {types} = toRefs(props);
const toast = useToast();
const form = useForm({
    name: props.breed?.name ?? '',
    type_id: props.breed?.type.id ?? null
});

const typesOption = computed(() => {
    if (props.breed) {
        return types.value.filter(
            type => type.name !== props.breed.type.name
        );
    }

    return props.types;
});

const defaultType = computed(() => {
    if (props.breed) {
        return props.breed.type;
    }

    return {
        name: 'Selectionnez un type',
        id: null
    }
});

const submit = () => {
    if (props.breed) {
        form.put(route('breeds.update', {breed: props.breed.id}), {
            onError: () => {
                toast.error("Erreur lors de la modification")
            },
        });
    } else {
        form.post(route('breeds.store'), {
            onError: () => {
                toast.error("Erreur lors de la création")
            },
        });
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col items-center py-8">
        <div class="w-1/2">
            <InputLabel for="type" value="Type d'animal "/>
            <select
                v-model="form.type_id"
                id="type"
                class="mt-1 block w-full"
                autofocus
            >
                <option :value="defaultType.id" selected>{{ defaultType.name }}</option>
                <option v-for="type in typesOption" :value="type.id">
                    {{ type.name }}
                </option>
            </select>
            <InputError class="mt-2" :message="form.errors.type_id"/>

            <InputLabel for="name" value="Race d'animal" class="mt-4"/>
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autocomplete="on"
            />
            <InputError class="mt-2" :message="form.errors.name"/>
        </div>
        <div class="flex items-center justify-end mt-6 space-x-8">
            <SecondaryButton @click="router.visit(route('breeds.index'))">Annuler</SecondaryButton>
            <PrimaryButton type="submit" :disabled="form.processing">Enregistrer</PrimaryButton>
        </div>
    </form>
</template>

<style scoped>

</style>

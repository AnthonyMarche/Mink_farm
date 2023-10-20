<script setup>
import {router, useForm} from "@inertiajs/vue3";
import TextInput from "@/components/TextInput.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import {useToast} from "vue-toastification";

const props = defineProps({
    type: {
        type: Object,
    }
});

const toast = useToast();
const form = useForm({
    name: props.type?.name ?? '',
});

const submit = () => {
    if (props.type) {
        form.put(route('types.update', {type: props.type.id}), {
            onError: () => {
                toast.error("Erreur lors de la modification")
            },
        });
    } else {
        form.post(route('types.store'), {
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
            <InputLabel for="name" value="Type d'animal"/>
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autofocus
                autocomplete="on"
            />
            <InputError class="mt-2" :message="form.errors.name"/>
        </div>

        <div class="flex items-center justify-end mt-6 space-x-8">
            <SecondaryButton @click="router.visit(route('types.index'))">Annuler</SecondaryButton>
            <PrimaryButton type="submit" :disabled="form.processing">Enregistrer</PrimaryButton>
        </div>
    </form>
</template>

<style scoped>

</style>

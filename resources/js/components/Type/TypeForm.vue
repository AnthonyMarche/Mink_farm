<script setup>
import {router, useForm} from "@inertiajs/vue3";
import TextInput from "@/components/TextInput.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import {useToast} from "vue-toastification";
import {toRefs} from "vue";

const props = defineProps({
  type: {
    type: Object,
  }
});

const {type} = toRefs(props);
const toast = useToast();
const form = useForm({
  name: type.value ? type.value.name : '',
});

const submit = () => {
  if (type.value) {
    axios.put(route('types.update', {id: type.value.id}), form)
        .then(() => {
          toast.success("Type modifié avec succès")
          router.visit(route('types.index'))
        })
        .catch(error => {
          toast.error("Erreur lors de la modification")
          form.errors = error.response.data.errors.name[0];
        })
  } else {
    axios.post(route('types.store'), form)
        .then(() => {
          toast.success("Type créé avec succès")
          router.visit(route('types.index'))
        })
        .catch(error => {
          toast.error("Erreur lors de la création")
          form.errors = error.response.data.errors.name[0];
        })
  }
};
</script>

<template>
  <form @submit.prevent="submit" class="flex flex-col items-center py-8">
    <div class="w-1/2">
      <InputLabel for="name" value="Nom du type"/>
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
      <SecondaryButton @click="router.visit(route('types.index'))">Annuler</SecondaryButton>
      <PrimaryButton>Enregistrer</PrimaryButton>
    </div>
  </form>
</template>

<style scoped>

</style>

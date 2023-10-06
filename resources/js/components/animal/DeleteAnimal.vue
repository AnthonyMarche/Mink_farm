<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {ref} from 'vue';
import {useToast} from "vue-toastification";

const props = defineProps({
    animalId: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['refreshAnimals']);
const confirmDeletion = ref(false);
const toast = useToast();
const confirmDelete = () => {
    confirmDeletion.value = true;
};

const deleteAnimal = (animalId) => {
    axios.delete('/animal/' + animalId)
        .then(res => {
            confirmDeletion.value = false;
            toast.success("Animal supprimé avec succès")
            emit('refreshAnimals');
        })
        .catch(error => {
            console.error('Erreur lors de la suppression de l\'animal', error);
        });
};

const closeModal = () => {
    confirmDeletion.value = false;
};
</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirmDelete">Supprimer</DangerButton>

        <Modal :show="confirmDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Etes vous sur de vouloir supprimer cet animal ?
                </h2>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Annuler</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        @click="deleteAnimal(animalId)"
                    >
                        Supprimer l'animal
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

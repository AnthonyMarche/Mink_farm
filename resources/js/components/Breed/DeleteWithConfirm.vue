<script setup>
import DangerButton from '@/components/DangerButton.vue';
import Modal from '@/components/Modal.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import {ref} from 'vue';
import {useToast} from "vue-toastification";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    item: {
        type: Object,
        required: true
    },
    url: {
        type: String,
        required: true
    },
    table: {
        type: String,
        required: true
    }
});

const confirmDeletion = ref(false);
const toast = useToast();
const confirmDelete = () => {
    confirmDeletion.value = true;
};

const deleteItem = () => {
    router.delete(props.url, {
        onError: () => {
            toast.error('Erreur lors de la suppression')
        },
    })
};

const closeModal = () => {
    confirmDeletion.value = false;
};
</script>

<template>
    <section class="space-y-6">
        <i @click="confirmDelete" class="fa-solid fa-trash cursor-pointer" style="color: #ff0000;"></i>

        <Modal :show="confirmDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Etes vous sur de vouloir supprimer <span class="font-semibold">{{ item.name }}</span> ?
                </h2>

                <p v-if="table === 'type'" class="mt-4 text-sm text-gray-600">
                    La suppression de {{ item.name }} entrainera également la suppression des races
                    associées et des animaux liés à ces races
                </p>
                <p v-if="table === 'breed'" class="mt-4 text-sm text-gray-600">
                    La suppression de {{ item.name }} entrainera également la suppression
                    des animaux associés à cette races
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Annuler</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        @click="deleteItem()"
                    >
                        Supprimer
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

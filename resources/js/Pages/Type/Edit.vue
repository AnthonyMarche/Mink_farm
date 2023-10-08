<script setup>

import {Head} from "@inertiajs/vue3";
import TypeForm from "@/components/Type/TypeForm.vue";
import AdminTypeLayout from "@/Layouts/AdminTypeLayout.vue";

const props = defineProps({
    type: {
      type: Object,
    }
});

</script>

<template>
    <Head :title="type ? 'Modifier type' : 'Créer type'"/>

    <AdminTypeLayout>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <TypeForm :type="type"/>
                </div>
            </div>
        </div>
    </AdminTypeLayout>
</template>

<style scoped>

</style>

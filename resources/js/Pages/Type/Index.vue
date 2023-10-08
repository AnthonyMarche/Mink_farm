<script setup>
import {Head, router} from '@inertiajs/vue3';
import DeleteWithConfirm from "@/components/Breed/DeleteWithConfirm.vue";
import AdminTypeLayout from "@/Layouts/AdminTypeLayout.vue";

defineProps({
  types: {
    type: Object,
    required: true
  }
});

</script>

<template>
  <Head title="Types"/>

  <AdminTypeLayout>
    <div class="py-12 px-5">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg pb-8 px-8">
          <h3 class="text-center font-semibold my-6 mx-12">Mes types d'animaux</h3>
          <div>
              <ul class="mt-10 inline-flex flex-wrap">
              <li v-for="type in types" class="flex space-x-6 m-6 px-8 py-4 items-center shadow-lg rounded-lg bg-gray-200">
                <p class="mr-4">{{ type.name }}</p>
                <i @click="router.get(route('types.edit', { type : type.id }))" class="fa-solid fa-pen-to-square cursor-pointer"></i>
                <DeleteWithConfirm
                    :url="route('types.destroy', { type: type.id})"
                    :item="type"
                    table="type"
                />
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AdminTypeLayout>
</template>

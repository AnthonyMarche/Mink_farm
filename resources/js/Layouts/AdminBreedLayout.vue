<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {router} from '@inertiajs/vue3';
</script>

<template>
    <AuthenticatedLayout>
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex space-x-12">
          <h2 @click="router.visit(route('breeds.index'))" class="text-gray-800 leading-tight cursor-pointer">
            Mes races d'animaux
          </h2>
          <h2 @click="router.visit(route('breeds.create'))" class="text-gray-800 leading-tight cursor-pointer">
            Ajouter une race d'animal
          </h2>
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </AuthenticatedLayout>
</template>

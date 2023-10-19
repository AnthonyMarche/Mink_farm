<script setup>
import CardShow from "@/components/animal/CardShow.vue";
import SelectInput from "@/components/SelectInput.vue";
import {computed, ref, watch, watchEffect} from "vue";
import InputLabel from "@/components/InputLabel.vue";
import {router, usePage} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";

const props = defineProps({
    types: {
        type: Object,
    },
    breeds: {
        type: Object,
    },
    animals: {
        type: Object,
    },
});

const toast = useToast();

const selectedType = ref(null);
const selectedBreed = ref(null);
const selectedOrderBy = ref('created_at');
const selectedSaleStatus = ref('')
const validBreed = ref([]);
const orderByOptions = {
    created_at: "Les plus récents",
    name: "Nom",
    price_ht: "Prix"
};
const saleStatusOptions = {
    '': "Tous",
    1: "En vente",
    0: "Vendu",
};

const user = usePage().props.auth.user;
const isAdmin = computed(() => {
    return user !== null && router.page.url === '/animals';
});

watch(selectedType, (newType) => {
    selectedBreed.value = null;

    if (!newType) {
        validBreed.value = [];
        return;
    }

    validBreed.value = props.breeds.filter(
        breed => breed.type_id === newType
    );
});

const buildQueryParams = () => {
    const queryParams = {
        type: selectedType.value,
        breed: selectedBreed.value,
        orderBy: selectedOrderBy.value,
        sale_status: selectedSaleStatus.value,
    };

    for (const key in queryParams) {
        if (queryParams[key] === null || queryParams[key] === "") {
            delete queryParams[key];
        }
    }

    return new URLSearchParams(queryParams).toString();
};


const refreshAnimalsFiltered = () => {
    router.visit(router.page.props.ziggy.location + '?' + buildQueryParams(), {
        method: 'GET',
        only: ['animals'],
        preserveState: true,
        preserveScroll: true,
        onError(Errors) {
            toast.error("Une erreur est survenue")
            console.log(Errors)
        },
    })
};

watchEffect(() => {
    refreshAnimalsFiltered();
});
</script>

<template>
    <div class="flex mt-5">
        <div class="flex justify-center w-1/2">
            <div class="flex items-center px-6">
                <InputLabel class="mr-4" for="type" value="Type :"/>
                <SelectInput
                    id="type"
                    :options="types"
                    default-option="Tous les types"
                    v-model="selectedType"
                />
            </div>
            <div class="flex items-center px-6">
                <InputLabel class="mr-4" for="breed" value="Race :"/>
                <SelectInput
                    id="breed"
                    :options="validBreed"
                    default-option="Toutes les races"
                    v-model="selectedBreed"/>
            </div>
            <div v-if="user" class="flex items-center">
                <InputLabel class="mr-4" for="sale_status" value="Status de vente :"/>
                <select id="sale_status" v-model="selectedSaleStatus">
                    <option v-for="(label, value) in saleStatusOptions" :value="value">
                        {{ label }}
                    </option>
                </select>
            </div>
        </div>
        <div class="w-1/2 flex items-center justify-end px-28">
            <InputLabel class="mr-4" for="orderBy" value="Trier par :"/>
            <select id="orderBy" v-model="selectedOrderBy">
                <option v-for="(label, value) in orderByOptions" :value="value">
                    {{ label }}
                </option>
            </select>
        </div>
    </div>
    <div class="card mx-auto sm:px-6 lg:px-8 space-y-6 mt-10">
        <div class="p-4 sm:p-6 bg-gray-100 shadow sm:rounded-lg border" v-for="animal in animals">
            <CardShow :animal="animal" @refreshAnimals="fetchFilteredAnimals" :is-admin="isAdmin"></CardShow>
        </div>
        <div v-if="!animals.length" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <p class="text-center">Aucun animal ne correspond à vos critères</p>
        </div>
    </div>
</template>

<style>
.card {
    max-width: 95rem;
}
</style>

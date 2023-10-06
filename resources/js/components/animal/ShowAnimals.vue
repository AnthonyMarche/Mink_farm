<script setup>
import CardShow from "@/components/animal/CardShow.vue";
import SelectInput from "@/components/SelectInput.vue";
import {ref, toRefs, watch, watchEffect} from "vue";
import InputLabel from "@/components/InputLabel.vue";

const props = defineProps({
  types: {
    type: Object,
  },
  breeds: {
    type: Object,
  },
});

const selectedType = ref(null);
const selectedBreed = ref(null);
const selectedOrderBy = ref('created_at');
const emit = defineEmits(['updateAnimals']);
const animals = ref([]);
const {breeds} = toRefs(props);
const validBreed = ref([]);
const orderByOptions = {
  created_at: "Les plus récents",
  name: "Nom",
  price_HT: "Prix"
};

watch(selectedType, (newType) => {
  selectedBreed.value = null;

  if (!newType) {
    validBreed.value = [];
    return;
  }

  validBreed.value = breeds.value.filter(
      breed => breed.type_id === newType
  );
});

const fetchFilteredAnimals = () => {
  axios.get('/filterAnimal', {
    params: {
      selectedType: selectedType.value,
      selectedBreed: selectedBreed.value,
      selectedOrderBy: selectedOrderBy.value
    },
  })
      .then(response => {
        animals.value = response.data.animals;
        emit('updateAnimals', animals.value);
      })
      .catch(error => {
        console.error(error);
      });
};

watchEffect(() => {
  fetchFilteredAnimals();
});
</script>

<template>
    <div class="flex mt-5">
      <div class="flex justify-center ml-14 w-1/2">
        <div class="flex items-center mr-5">
          <InputLabel class="mr-5" for="type" value="Type :"/>
          <SelectInput
              id="type"
              :options="types"
              default-option="Tous les types"
              v-model="selectedType"
          />
        </div>
        <div class="flex items-center">
          <InputLabel class="mr-5" for="breed" value="Race :"/>
          <SelectInput
              id="breed"
              :options="validBreed"
              default-option="Toutes les races"
              v-model="selectedBreed"/>
        </div>
      </div>
      <div class="w-1/2 flex items-center justify-center">
        <InputLabel class="mr-5" for="orderBy" value="Trier par :"/>
        <select v-model="selectedOrderBy">
          <option v-for="(label, value) in orderByOptions" :value="value">
            {{ label }}
          </option>
        </select>
      </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-10">
      <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" v-for="animal in animals">
        <CardShow :animal="animal"></CardShow>
      </div>
      <div v-if="!animals.length" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <p class="text-center">Aucun animal ne correspond à vos critères</p>
      </div>
    </div>
</template>

<style>
</style>

<script setup>

import {router, useForm, usePage} from "@inertiajs/vue3";
import {computed, ref, toRefs, watch} from "vue";
import TextInput from "@/components/TextInput.vue";
import InputLabel from "@/components/InputLabel.vue";
import InputError from "@/components/InputError.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import Checkbox from "@/components/Checkbox.vue";
import TextAreaInput from "@/components/TextAreaInput.vue";
import {useToast} from "vue-toastification";
import SecondaryButton from "@/components/SecondaryButton.vue";

const props = defineProps({
    animal: {
        type: Object,
    },
    types: {
        type: Object,
        required: true
    },
    breeds: {
        type: Object,
        required: true
    },
});

const defaultType = computed(() => {
    if (props.animal) {
        return props.animal.breed.type;
    }

    return {
        name: 'Selectionnez un type',
        id: null
    }
});

const typesOption = computed(() => {
    if (props.animal) {
        return types.value.filter(
            type => type.name !== props.animal.breed.type.name
        );
    }

    return props.types;
});

const selectedType = ref(defaultType.value.id);

const defaultBreed = computed(() => {
    if (props.animal && props.animal.breed.type.id === selectedType.value) {
        return props.animal.breed;
    }

    return {
        name: 'Selectionnez une race',
        id: null
    }
});

const {breeds} = toRefs(props);
const breedsOption = ref(getBreedsOption())
const {types} = toRefs(props);
const selectedBreed = ref(defaultBreed.value.id);
const toast = useToast();
let currentImage = ref(
    props.animal && props.animal.image_path ? '/storage/' + props.animal.image_path : null
)

watch(selectedType, () => {
    breedsOption.value = getBreedsOption();
    selectedBreed.value = defaultBreed.value.id;
});

watch(selectedBreed, () => {
    form.breed_id = selectedBreed.value
});

function getBreedsOption() {
    if (selectedType.value) {

        return breeds.value.filter(
            breed => breed.type.id === selectedType.value && breed.type.id !== defaultBreed.value.id
        );
    }

    return [];
}

/* Image change function */
function onChangeImage(e) {
    form.image = e.target.files[0];
    currentImage.value = URL.createObjectURL(e.target.files[0]);
}

const inputFile = ref(null)

function deleteImage() {
    inputFile.value.type = 'text';
    inputFile.value.type = 'file';
    currentImage.value = null;
    form.image = null
}
/* End image change function */

/* Form */
const form = useForm({
    image: props.animal?.image ?? '',
    name: props.animal?.name ?? '',
    age: props.animal?.age ?? '',
    description: props.animal?.description ?? '',
    price_ht: props.animal?.price_ht ?? '',
    sale_status: props.animal?.sale_status ?? 0,
    breed_id: selectedBreed.value,
    user_id: usePage().props.auth.user.id
});

const submitForm = () => {
    if (props.animal) {
        router.post(route('animals.update', {animal: props.animal.id}), {
            _method: 'put',
            ...form,
            onError: () => {
                toast.error("Erreur lors de la modification")
            },
        });
    } else {
        form.post(route('animals.store'), {
            onError: () => {
                toast.error("Erreur lors de la création")
            },
        });
    }
};
</script>

<template>
    <form @submit.prevent="submitForm" enctype="multipart/form-data" class="my-8">
        <div class="flex justify-around">
            <div class="space-y-6 w-2/5">
                <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                        <InputLabel for="image" value="Photo" class="w-full"/>
                        <i
                            v-if="currentImage"
                            @click="deleteImage"
                            class="fa-solid fa-xmark fa-xl cross"
                        >
                        </i>
                    </div>
                    <div class="flex flex-col items-center -mt-6">
                        <input
                            ref="inputFile"
                            id="image"
                            type="file"
                            class="mt-1 block"
                            @change="onChangeImage"
                        />
                        <InputError class="mt-2" :message="form.errors.image"/>
                        <img v-if="currentImage" :src=currentImage alt="Photo animal" class="image-size mt-5">
                    </div>
                </div>
                <div class="flex flex-col">
                    <InputLabel for="name" value="Nom" class="w-1/2"/>
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="on"
                    />
                    <InputError class="mt-2" :message="form.errors.name"/>
                </div>
                <div class="flex flex-col">
                    <InputLabel for="age" value="Age" class="w-1/2"/>
                    <TextInput
                        id="age"
                        type="text"
                        class="mt-1 block"
                        v-model="form.age"
                        required
                        autocomplete="on"
                    />
                    <InputError class="mt-2" :message="form.errors.age"/>
                </div>
            </div>
            <div class="space-y-6 w-2/5">
                <div class="flex flex-col">
                    <InputLabel for="description" value="Description" class="w-1/2"/>
                    <TextAreaInput
                        id="name"
                        type="text"
                        class="mt-1 block"
                        v-model="form.description"
                        required
                        autocomplete="on"
                    />
                    <InputError class="mt-2" :message="form.errors.description"/>
                </div>
                <div class="flex flex-col">
                    <InputLabel class="mr-4 w-1/2" for="type" value="Type :"/>
                    <select
                        v-model="selectedType"
                        id="type"
                        class="mt-1 block w-full"
                    >
                        <option :value="defaultType.id" selected>{{ defaultType.name }}</option>
                        <option v-for="type in typesOption" :value="type.id">
                            {{ type.name }}
                        </option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <InputLabel class="mr-4 w-1/2" for="breed" value="Race :"/>
                    <select
                        id="breed"
                        class="mt-1 block w-full"
                        v-model="selectedBreed"
                        :disabled="breedsOption.length === 0"
                        :class="{'disabled-select': breedsOption.length === 0}"
                    >
                        <option :value="defaultBreed.id" selected>{{ defaultBreed.name }}</option>
                        <option v-for="breed in breedsOption" :value="breed.id">
                            {{ breed.name }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.breed_id"/>
                </div>
                <div class="flex flex-col">
                    <InputLabel for="price" value="Prix HT" class="w-1/2"/>
                    <TextInput
                        id="price"
                        type="text"
                        class="mt-1 block"
                        v-model="form.price_ht"
                        required
                        autocomplete="on"
                    />
                    <InputError class="mt-2" :message="form.errors.price_ht"/>
                </div>
                <div class="flex space-x-5">
                    <InputLabel for="saleStatus" value="Status de vente "/>
                    <Checkbox
                        id="saleStatus"
                        class="mt-1 block"
                        :true-value=1
                        :false-value=0
                        v-model:checked="form.sale_status"
                    />
                    <InputError :message="form.errors.sale_status"/>
                </div>
            </div>
        </div>
        <div class="flex justify-center gap-4 my-10">
            <SecondaryButton @click="router.visit(route('admin.home'))">Annuler</SecondaryButton>
            <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
        </div>
    </form>

</template>

<style scoped>
.image-size {
    width: 345px;
    height: auto;
}

.cross {
    color: #ff0000;
    z-index: 10;
    cursor: pointer
}

.disabled-select {
    background-color: #bfbfbf;
}
</style>

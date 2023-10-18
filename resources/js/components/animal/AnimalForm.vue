<script setup>

import {router, useForm, usePage} from "@inertiajs/vue3";
import {ref, toRefs, watch} from "vue";
import TextInput from "@/components/TextInput.vue";
import InputLabel from "@/components/InputLabel.vue";
import InputError from "@/components/InputError.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import Checkbox from "@/components/Checkbox.vue";
import SelectInput from "@/components/SelectInput.vue";
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

const {animal} = toRefs(props);
const selectedType = ref(null);
const validBreed = ref([]);
const toast = useToast();
let currentImage = ref(
    animal.value && animal.value.image_path ? '/storage/' + animal.value.image_path : null
)

watch(selectedType, (newType) => {
    if (!newType) {
        validBreed.value = [];
        return;
    }

    validBreed.value = props.breeds.filter(
        breed => breed.type_id === newType
    );
});

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

const form = useForm({
    image: animal.value ? animal.value.image : '',
    name: animal.value ? animal.value.name : '',
    age: animal.value ? animal.value.age : '',
    description: animal.value ? animal.value.description : '',
    price_ht: animal.value ? animal.value.price_ht : '',
    sale_status: animal.value ? animal.value.sale_status === 'en vente' : false,
    breed_id: animal.value ? animal.value.breed_id : null,
    user_id: usePage().props.auth.user.id
});

const submitForm = () => {
    let formData = new FormData()
    for (const key in form) {
        formData.append(key, form[key]);
    }
    formData.set('sale_status', form.sale_status === true ? '1' : '0');

    if (animal.value) {
        const headers = {
            'Content-Type': 'multipart/form-data',
            'enctype': 'multipart/form-data',
        }
        formData.append("_method", 'PATCH');
        axios({
            method: "POST",
            url: route('animals.update', {animal: animal.value.id}),
            data: formData,
            headers: headers,
        })
            .then(response => {
                toast.success("Animal modifié avec succès");
                router.visit(route('admin.home'));
            })
            .catch(error => {
                const errors = error.response.data.errors;
                for (const field in errors) {
                    form.errors[field] = errors[field][0];
                }
            });
    } else {
        axios.post(route('animals.store'), formData)
            .then(response => {
                toast.success("Animal Créé avec succès");
                router.visit(route('admin.home'));
            })
            .catch(error => {
                const errors = error.response.data.errors;
                for (const field in errors) {
                    form.errors[field] = errors[field][0];
                }
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
                            autofocus
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
                        autocomplete="name"
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
                        autocomplete="age"
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
                        autofocus
                        autocomplete="description"
                    />
                    <InputError class="mt-2" :message="form.errors.description"/>
                </div>
                <div class="flex flex-col">
                    <InputLabel class="mr-4 w-1/2" for="type" value="Type :"/>
                    <SelectInput
                        id="type"
                        class="mt-1 block"
                        :options="types"
                        :required-option=true
                        default-option="Choisissez un type"
                        v-model="selectedType"
                    />
                </div>
                <div class="flex flex-col">
                    <InputLabel class="mr-4 w-1/2" for="breed" value="Race :"/>
                    <SelectInput
                        id="breed"
                        class="mt-1 block"
                        :options="validBreed"
                        :required-option=true
                        default-option="Choisissez une race"
                        v-model="form.breed_id"
                    />
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
                        autocomplete="price_HT"
                    />
                    <InputError class="mt-2" :message="form.errors.price_ht"/>
                </div>
                <div class="flex space-x-5">
                    <InputLabel for="saleStatus" value="Status de vente "/>
                    <Checkbox
                        id="saleStatus"
                        class="mt-1 block"
                        v-model="form.sale_status"
                        :checked="form.sale_status"
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
</style>

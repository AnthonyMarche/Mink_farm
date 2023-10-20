<script setup>
import {useToast} from "vue-toastification";
import {watch} from "vue";

const props = defineProps({
    message: {
        type: Object,
        required: true
    }
});

const toast = useToast();

const displayToast = () => {
    if (props.message) {
        toast(props.message.text, {
            type: props.message.type
        });
    }
};

watch(() => props.message, (newMessage) => {
    if (newMessage) {
        displayToast()
    }
});

displayToast();
</script>
<template/>

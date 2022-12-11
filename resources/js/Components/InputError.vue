<script setup>
defineProps({
    'message': {
        type: String,
        default: '',
        required: true,
    },
    'replaceFrom': {
        type: String,
        default: '',
    },
    'replaceTo': {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600">
            {{ message?.replace(replaceFrom, replaceTo) }}
        </p>
    </div>
</template>

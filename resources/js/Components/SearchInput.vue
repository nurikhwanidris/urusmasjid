<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: String,
    placeholder: {
        type: String,
        default: 'Search...',
    },
    debounce: {
        type: Number,
        default: 300,
    },
});

const emit = defineEmits(['update:modelValue']);

const input = ref(props.modelValue);
const timeout = ref(null);

watch(input, (value) => {
    clearTimeout(timeout.value);
    timeout.value = setTimeout(() => {
        emit('update:modelValue', value);
    }, props.debounce);
});
</script>

<template>
    <div class="relative">
        <input
            type="text"
            v-model="input"
            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-12 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500"
            :placeholder="placeholder"
        />
    </div>
</template>

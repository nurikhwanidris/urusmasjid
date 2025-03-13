<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    rows: {
        type: Number,
        default: 4,
    },
});

defineEmits(['update:modelValue']);

const textarea = ref(null);

onMounted(() => {
    if (textarea.value.hasAttribute('autofocus')) {
        textarea.value.focus();
    }
});

defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea
        ref="textarea"
        class="rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        :value="modelValue"
        :rows="rows"
        @input="$emit('update:modelValue', $event.target.value)"
    ></textarea>
</template>

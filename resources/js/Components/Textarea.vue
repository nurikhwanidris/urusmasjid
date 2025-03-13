<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    rows: {
        type: Number,
        default: 4,
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
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
        :required="required"
        :disabled="disabled"
        @input="$emit('update:modelValue', $event.target.value)"
    ></textarea>
</template>

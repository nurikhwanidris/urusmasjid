<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: [String, Number],
        required: true,
    },
    options: {
        type: Array,
        required: true,
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

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        ref="input"
        class="rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        :value="modelValue"
        :required="required"
        :disabled="disabled"
        @change="$emit('update:modelValue', $event.target.value)"
    >
        <option v-if="!required" value="">Sila Pilih</option>
        <option
            v-for="option in options"
            :key="option.value"
            :value="option.value"
        >
            {{ option.label }}
        </option>
    </select>
</template>

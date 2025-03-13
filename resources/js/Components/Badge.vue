<script setup>
import { computed } from 'vue';

const props = defineProps({
    color: {
        type: String,
        default: 'secondary',
        validator: (value) => {
            return [
                'primary',
                'secondary',
                'success',
                'danger',
                'warning',
                'info',
            ].includes(value);
        },
    },
});

const colorClasses = computed(() => {
    switch (props.color) {
        case 'primary':
            return 'bg-blue-100 text-blue-800';
        case 'success':
            return 'bg-green-100 text-green-800';
        case 'danger':
            return 'bg-red-100 text-red-800';
        case 'warning':
            return 'bg-yellow-100 text-yellow-800';
        case 'info':
            return 'bg-indigo-100 text-indigo-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
});
</script>

<template>
    <span
        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
        :class="colorClasses"
    >
        <slot></slot>
    </span>
</template>

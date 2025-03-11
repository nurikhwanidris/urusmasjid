<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full rounded-md py-2 pl-3 pr-4 text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-all duration-200 ease-in-out'
        : 'block w-full rounded-md py-2 pl-3 pr-4 text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-gray-50 transition-all duration-200 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot></slot>
    </Link>
</template>

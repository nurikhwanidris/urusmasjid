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
        ? 'relative block w-full pl-3 pr-4 py-2.5 border-l-4 border-emerald-500 text-left text-base font-medium text-emerald-700 bg-emerald-50 focus:outline-none focus:text-emerald-800 focus:bg-emerald-100 transition-all duration-200 ease-in-out'
        : 'relative block w-full pl-3 pr-4 py-2.5 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-emerald-600 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-emerald-700 focus:bg-gray-50 transition-all duration-200 ease-in-out',
);

const iconClasses = computed(() =>
    props.active
        ? 'text-emerald-600'
        : 'text-gray-500 group-hover:text-emerald-500',
);
</script>

<template>
    <Link :href="href" :class="[classes, 'group']">
        <div class="flex w-full items-center">
            <!-- Icon slot -->
            <div :class="['mr-3', iconClasses]" v-if="$slots.icon">
                <slot name="icon"></slot>
            </div>

            <span class="truncate">
                <slot></slot>
            </span>
        </div>
    </Link>
</template>

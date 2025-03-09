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
    icon: {
        type: String,
        default: '',
    },
    collapsed: {
        type: Boolean,
        default: false,
    },
});

const classes = computed(() =>
    props.active
        ? 'relative flex items-center rounded-md px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-all duration-200 ease-in-out'
        : 'relative flex items-center rounded-md px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-gray-50 transition-all duration-200 ease-in-out',
);

const iconClasses = computed(() =>
    props.active
        ? 'text-emerald-600'
        : 'text-gray-500 group-hover:text-emerald-500',
);
</script>

<template>
    <Link :href="href" :class="[classes, 'group']">
        <div
            v-if="active"
            class="absolute inset-y-0 left-0 w-1 rounded-r-md bg-emerald-500"
        ></div>
        <div class="flex items-center">
            <!-- Icon slot -->
            <div
                :class="[
                    'flex-shrink-0 transition-all duration-200 ease-in-out',
                    collapsed ? 'mx-auto' : 'mr-3',
                    iconClasses,
                ]"
                v-if="$slots.icon"
            >
                <slot name="icon"></slot>
            </div>

            <!-- Text content -->
            <span
                v-if="!collapsed"
                class="truncate transition-opacity duration-200"
            >
                <slot></slot>
            </span>
        </div>

        <!-- Tooltip for collapsed state -->
        <div
            v-if="collapsed"
            class="absolute left-full top-1/2 ml-2 -translate-y-1/2 transform rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100"
        >
            <slot></slot>
        </div>
    </Link>
</template>

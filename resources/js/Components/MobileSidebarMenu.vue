<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    icon: {
        type: String,
        default: '',
    },
    routes: {
        type: Array,
        default: () => [],
    },
});

const isOpen = ref(false);

// Check if any child route is active
const isActive = computed(() => {
    return props.routes.some((routeName) => route().current(routeName));
});

// Toggle submenu
const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};

// Classes for the menu header
const menuClasses = computed(() =>
    isActive.value
        ? 'flex w-full items-center rounded-md px-4 py-3 text-base font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-all duration-200 ease-in-out'
        : 'flex w-full items-center rounded-md px-4 py-3 text-base font-medium text-gray-600 hover:text-emerald-600 hover:bg-gray-50 transition-all duration-200 ease-in-out',
);

// Classes for the icon
const iconClasses = computed(() =>
    isActive.value
        ? 'text-emerald-600'
        : 'text-gray-500 group-hover:text-emerald-500',
);
</script>

<template>
    <div class="space-y-1">
        <!-- Menu Header -->
        <button
            type="button"
            @click="toggleMenu"
            :class="[menuClasses, 'group']"
        >
            <div class="flex w-full items-center">
                <!-- Icon -->
                <div :class="['mr-3 flex-shrink-0', iconClasses]">
                    <div v-html="icon"></div>
                </div>

                <!-- Text content -->
                <span class="flex-1 truncate text-left">
                    {{ title }}
                </span>

                <!-- Dropdown arrow -->
                <svg
                    :class="[
                        'ml-2 h-5 w-5 transition-transform duration-200',
                        isOpen ? 'rotate-180 transform' : '',
                        isActive ? 'text-emerald-600' : 'text-gray-400',
                    ]"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </div>
        </button>

        <!-- Submenu Items -->
        <div v-if="isOpen" class="ml-8 space-y-1 border-l border-gray-100 pl-2">
            <slot></slot>
        </div>
    </div>
</template>

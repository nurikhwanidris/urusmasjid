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
    collapsed: {
        type: Boolean,
        default: false,
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
        ? 'relative flex w-full items-center rounded-md px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-all duration-200 ease-in-out'
        : 'relative flex w-full items-center rounded-md px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-gray-50 transition-all duration-200 ease-in-out',
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
            :class="[menuClasses, 'group', collapsed ? 'justify-center' : '']"
        >
            <div
                v-if="isActive"
                class="absolute inset-y-0 left-0 w-1 rounded-r-md bg-emerald-500"
            ></div>
            <div
                class="flex w-full items-center"
                :class="{ 'justify-center': collapsed }"
            >
                <!-- Icon -->
                <div
                    :class="[
                        'flex-shrink-0 transition-all duration-200 ease-in-out',
                        collapsed ? 'mx-auto' : 'mr-3',
                        iconClasses,
                    ]"
                >
                    <div v-html="icon"></div>
                </div>

                <!-- Text content -->
                <span
                    v-if="!collapsed"
                    class="flex-1 truncate text-left transition-opacity duration-200"
                >
                    {{ title }}
                </span>

                <!-- Dropdown arrow (only when not collapsed) -->
                <svg
                    v-if="!collapsed"
                    :class="[
                        'ml-2 h-4 w-4 transition-transform duration-200',
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

            <!-- Tooltip for collapsed state -->
            <div
                v-if="collapsed"
                class="absolute left-full top-1/2 z-50 ml-2 -translate-y-1/2 transform rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100"
            >
                {{ title }}
            </div>
        </button>

        <!-- Submenu Items (only visible when not collapsed and menu is open) -->
        <div
            v-if="!collapsed && isOpen"
            class="ml-8 space-y-1 border-l border-gray-100 pl-2"
        >
            <slot></slot>
        </div>

        <!-- For collapsed state, show submenu items in a tooltip on hover -->
        <div
            v-if="collapsed && isOpen"
            class="absolute left-full top-0 z-50 ml-2 mt-12 w-48 rounded-md bg-white py-1 opacity-0 shadow-lg ring-1 ring-black ring-opacity-5 group-hover:opacity-100"
        >
            <slot></slot>
        </div>
    </div>
</template>

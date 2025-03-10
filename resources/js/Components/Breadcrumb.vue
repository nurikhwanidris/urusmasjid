<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    items: {
        type: Array,
        required: true,
        // Each item should have { label, url, active }
    },
});

// Ensure the last item is marked as active
const breadcrumbItems = computed(() => {
    if (props.items.length === 0) return [];

    return props.items.map((item, index) => ({
        ...item,
        active: index === props.items.length - 1,
    }));
});
</script>

<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-1 md:space-x-2">
            <li class="flex items-center">
                <Link
                    :href="route('dashboard')"
                    class="text-sm font-medium text-gray-500 hover:text-emerald-600"
                >
                    <span class="hidden md:inline">Home</span>
                </Link>
            </li>

            <template v-for="(item, index) in breadcrumbItems" :key="index">
                <li class="flex items-center">
                    <svg
                        class="h-4 w-4 flex-shrink-0 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>

                    <Link
                        v-if="!item.active && item.url"
                        :href="item.url"
                        class="ml-1 text-sm font-medium text-gray-500 hover:text-emerald-600 md:ml-2"
                    >
                        {{ item.label }}
                    </Link>

                    <span
                        v-else
                        class="ml-1 text-sm font-medium text-emerald-600 md:ml-2"
                    >
                        {{ item.label }}
                    </span>
                </li>
            </template>
        </ol>
    </nav>
</template>

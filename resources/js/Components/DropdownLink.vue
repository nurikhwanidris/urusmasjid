<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    method: {
        type: String,
        default: 'get',
    },
    as: {
        type: String,
        default: 'a',
    },
});
</script>

<template>
    <div>
        <Link
            v-if="as === 'a'"
            :href="href"
            :method="method"
            class="block w-full px-4 py-2.5 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
        >
            <slot />
        </Link>

        <button
            v-else-if="as === 'button'"
            type="button"
            class="block w-full px-4 py-2.5 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
            @click="router.post(href, {}, { preserveScroll: true })"
        >
            <slot />
        </button>
    </div>
</template>

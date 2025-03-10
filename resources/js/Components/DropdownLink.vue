<script setup>
import { Link } from '@inertiajs/vue3';

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
    className: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div>
        <Link
            v-if="as === 'link'"
            :href="href"
            :class="[
                'flex w-full items-center px-4 py-2.5 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none',
                className,
            ]"
        >
            <slot />
        </Link>

        <button
            v-else
            type="submit"
            :class="[
                'flex w-full items-center px-4 py-2.5 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none',
                className,
            ]"
            @click="$inertia.post(href, {}, { method })"
        >
            <slot />
        </button>
    </div>
</template>

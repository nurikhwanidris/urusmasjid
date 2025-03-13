<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="-mb-1 flex flex-wrap">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="mb-1 mr-1 rounded border px-4 py-2 text-sm leading-4 text-gray-400"
                    v-html="link.label"
                />
                <Link
                    v-else
                    class="mb-1 mr-1 rounded border px-4 py-2 text-sm leading-4 hover:bg-emerald-50 focus:border-emerald-500 focus:text-emerald-500"
                    :class="{ 'bg-emerald-50 text-emerald-600': link.active }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>

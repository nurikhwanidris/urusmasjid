<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Badge.vue';

const props = defineProps({
    mosque: Object,
    announcement: Object,
});

// Helper function to format dates
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('ms-MY', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getStatusClass = (status) => {
    switch (status) {
        case 'published':
            return 'bg-green-100 text-green-800';
        case 'draft':
            return 'bg-gray-100 text-gray-800';
        case 'archived':
            return 'bg-blue-100 text-blue-800';
        case 'expired':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getTypeClass = (type) => {
    switch (type) {
        case 'general':
            return 'bg-blue-100 text-blue-800';
        case 'important':
            return 'bg-yellow-100 text-yellow-800';
        case 'emergency':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getTypeLabel = (type) => {
    switch (type) {
        case 'general':
            return 'Umum';
        case 'important':
            return 'Penting';
        case 'emergency':
            return 'Kecemasan';
        default:
            return type;
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'published':
            return 'Diterbitkan';
        case 'draft':
            return 'Draf';
        case 'archived':
            return 'Arkib';
        case 'expired':
            return 'Tamat Tempoh';
        default:
            return status;
    }
};
</script>

<template>
    <AuthenticatedLayout :title="`${announcement.title} - ${mosque.name}`">
        <Head :title="`${announcement.title} - ${mosque.name}`" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Header with actions -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-900">
                                    {{ announcement.title }}
                                </h2>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <Badge :class="getTypeClass(announcement.type)">
                                        {{ getTypeLabel(announcement.type) }}
                                    </Badge>
                                    <Badge :class="getStatusClass(announcement.status)">
                                        {{ getStatusLabel(announcement.status) }}
                                    </Badge>
                                </div>
                            </div>
                            <div class="mt-4 sm:mt-0 flex space-x-3">
                                <Link
                                    :href="route('masjid.pengumuman.edit', [mosque.id, announcement.id])"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </Link>
                                <Link
                                    :href="route('masjid.pengumuman.index', mosque.id)"
                                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 disabled:opacity-25 transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali
                                </Link>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div v-if="announcement.featured_image" class="mb-6">
                            <img
                                :src="`/storage/${announcement.featured_image}`"
                                :alt="announcement.title"
                                class="w-full max-h-96 object-cover rounded-lg"
                            />
                        </div>

                        <!-- Announcement Content -->
                        <div class="prose max-w-none mb-6">
                            <div class="whitespace-pre-wrap">{{ announcement.content }}</div>
                        </div>

                        <!-- Announcement Details -->
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Maklumat Pengumuman</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Dicipta oleh</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ announcement.creator?.name || 'Tidak diketahui' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tarikh Cipta</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(announcement.created_at) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tarikh Terbit</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ announcement.published_at ? formatDate(announcement.published_at) : 'Belum diterbitkan' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tarikh Tamat</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ announcement.expires_at ? formatDate(announcement.expires_at) : 'Tiada tarikh tamat' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Terakhir Dikemaskini</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(announcement.updated_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

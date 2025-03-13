<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import SearchInput from '@/Components/SearchInput.vue';
import Badge from '@/Components/Badge.vue';

const props = defineProps({
    mosque: Object,
    announcements: Object,
    filters: Object,
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

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || 'published');
const type = ref(props.filters.type || '');

const statusOptions = [
    { value: 'published', label: 'Diterbitkan' },
    { value: 'draft', label: 'Draf' },
    { value: 'archived', label: 'Arkib' },
    { value: 'expired', label: 'Tamat Tempoh' },
];

const typeOptions = [
    { value: '', label: 'Semua Jenis' },
    { value: 'general', label: 'Umum' },
    { value: 'important', label: 'Penting' },
    { value: 'emergency', label: 'Kecemasan' },
];

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

const updateFilters = () => {
    router.get(
        route('masjid.pengumuman.index', props.mosque.id),
        {
            search: search.value,
            status: status.value,
            type: type.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

watch(status, updateFilters);
watch(type, updateFilters);

const handleSearch = () => {
    updateFilters();
};

const truncateContent = (content, maxLength = 100) => {
    if (content.length <= maxLength) return content;
    return content.substring(0, maxLength) + '...';
};
</script>

<template>
    <AuthenticatedLayout :title="`Pengumuman - ${mosque.name}`">
        <Head :title="`Pengumuman - ${mosque.name}`" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div
                            class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between"
                        >
                            <h2
                                class="mb-4 text-2xl font-semibold text-gray-900 sm:mb-0"
                            >
                                Pengumuman
                            </h2>
                            <Link
                                :href="
                                    route('masjid.pengumuman.create', mosque.id)
                                "
                                class="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-emerald-700 focus:border-emerald-800 focus:outline-none focus:ring focus:ring-emerald-200 active:bg-emerald-800 disabled:opacity-25"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mr-2 h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                                Cipta Pengumuman
                            </Link>
                        </div>

                        <!-- Filters -->
                        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label
                                    for="status"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Status</label
                                >
                                <select
                                    id="status"
                                    v-model="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 sm:text-sm"
                                >
                                    <option
                                        v-for="option in statusOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    for="type"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Jenis</label
                                >
                                <select
                                    id="type"
                                    v-model="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 sm:text-sm"
                                >
                                    <option
                                        v-for="option in typeOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    for="search"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Carian</label
                                >
                                <SearchInput
                                    id="search"
                                    v-model="search"
                                    @search="handleSearch"
                                    placeholder="Cari pengumuman..."
                                />
                            </div>
                        </div>

                        <!-- Announcements List -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Tajuk
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Kandungan
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Jenis
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Status
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Tarikh Terbit
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Tarikh Tamat
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Tindakan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr
                                        v-for="announcement in announcements.data"
                                        :key="announcement.id"
                                    >
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ announcement.title }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="max-w-xs text-sm text-gray-500"
                                            >
                                                {{
                                                    truncateContent(
                                                        announcement.content,
                                                    )
                                                }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <Badge
                                                :class="
                                                    getTypeClass(
                                                        announcement.type,
                                                    )
                                                "
                                            >
                                                {{
                                                    getTypeLabel(
                                                        announcement.type,
                                                    )
                                                }}
                                            </Badge>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <Badge
                                                :class="
                                                    getStatusClass(
                                                        announcement.status,
                                                    )
                                                "
                                            >
                                                {{
                                                    getStatusLabel(
                                                        announcement.status,
                                                    )
                                                }}
                                            </Badge>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-500">
                                                {{
                                                    announcement.published_at
                                                        ? formatDate(
                                                              announcement.published_at,
                                                          )
                                                        : '-'
                                                }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-500">
                                                {{
                                                    announcement.expires_at
                                                        ? formatDate(
                                                              announcement.expires_at,
                                                          )
                                                        : '-'
                                                }}
                                            </div>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'masjid.pengumuman.show',
                                                        [
                                                            mosque.id,
                                                            announcement.id,
                                                        ],
                                                    )
                                                "
                                                class="mr-3 text-emerald-600 hover:text-emerald-900"
                                            >
                                                Lihat
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'masjid.pengumuman.edit',
                                                        [
                                                            mosque.id,
                                                            announcement.id,
                                                        ],
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Edit
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="announcements.data.length === 0">
                                        <td
                                            colspan="7"
                                            class="px-6 py-4 text-center text-gray-500"
                                        >
                                            Tiada pengumuman dijumpai.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            <Pagination :links="announcements.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

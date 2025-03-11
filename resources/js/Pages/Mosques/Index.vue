<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Badge from '@/Components/Badge.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { states } from '@/Utils/states';
import { zones } from '@/Utils/zones';
import SearchInput from '@/Components/SearchInput.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    mosques: Object,
});

const search = ref('');
const filterState = ref('all');

const filteredMosques = computed(() => {
    const searchTerm = search.value.toLowerCase();
    return props.mosques.data.filter((mosque) => {
        const matchesSearch =
            !searchTerm || mosque.name.toLowerCase().includes(searchTerm);
        const matchesState =
            filterState.value === 'all' || mosque.state === filterState.value;
        return matchesSearch && matchesState;
    });
});

// Helper function to get status badge color
const getStatusColor = (status) => {
    switch (status) {
        case 'verified':
            return 'success';
        case 'pending':
            return 'warning';
        case 'rejected':
            return 'danger';
        default:
            return 'secondary';
    }
};

// Helper function to format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const stateOptions = [
    { label: 'Semua', value: 'all' },
    ...states.map((state) => ({
        label: state.label,
        value: state.value,
    })),
];

const getZoneDetails = (zoneCode) => {
    const zone = zones.find((z) => z.jakimCode === zoneCode);
    if (zone) {
        return `${zone.jakimCode} - ${zone.negeri.length > 20 ? zone.negeri.slice(0, 20) + '...' : zone.negeri} (${zone.daerah.length > 20 ? zone.daerah.slice(0, 20) + '...' : zone.daerah})`;
    }
    return zoneCode;
};
</script>

<template>
    <Head title="Senarai Masjid" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <Link :href="route('masjid.create')">
                    <PrimaryButton>Daftar Masjid</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Search and filter -->
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <SearchInput
                                    v-model="search"
                                    placeholder="Cari masjid..."
                                    class="w-64"
                                />
                                <SelectInput
                                    v-model="filterState"
                                    :options="stateOptions"
                                    class="w-48"
                                />
                            </div>
                        </div>

                        <div
                            v-if="mosques.data.length === 0"
                            class="py-8 text-center"
                        >
                            <p class="text-gray-500">Tiada masjid ditemui.</p>
                            <Link
                                :href="route('masjid.create')"
                                class="mt-4 inline-block text-emerald-600 hover:text-emerald-500"
                            >
                                Daftar masjid anda
                            </Link>
                        </div>

                        <div v-else>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Nama
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
                                                Zon JAKIM
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
                                                Dicipta pada
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
                                            v-for="mosque in filteredMosques"
                                            :key="mosque.id"
                                        >
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    class="font-medium text-gray-900"
                                                >
                                                    {{ mosque.name }}
                                                </div>
                                                <div
                                                    v-if="
                                                        mosque.registration_number
                                                    "
                                                    class="text-xs text-gray-500"
                                                >
                                                    Reg:
                                                    {{
                                                        mosque.registration_number
                                                    }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                            >
                                                {{
                                                    mosque.type === 'masjid'
                                                        ? 'Masjid'
                                                        : 'Surau'
                                                }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                            >
                                                <div class="text-xs">
                                                    Zon:
                                                    {{
                                                        getZoneDetails(
                                                            mosque.jakim_zone,
                                                        )
                                                    }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm"
                                            >
                                                <Badge
                                                    :color="
                                                        getStatusColor(
                                                            mosque.verification_status,
                                                        )
                                                    "
                                                >
                                                    {{
                                                        mosque.verification_status
                                                            .charAt(0)
                                                            .toUpperCase() +
                                                        mosque.verification_status.slice(
                                                            1,
                                                        )
                                                    }}
                                                </Badge>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                            >
                                                {{
                                                    formatDate(
                                                        mosque.created_at,
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'masjid.show',
                                                            mosque.id,
                                                        )
                                                    "
                                                    class="text-emerald-600 hover:text-emerald-900"
                                                >
                                                    Lihat
                                                </Link>
                                                <span class="mx-1">|</span>
                                                <Link
                                                    :href="
                                                        route(
                                                            'masjid.edit',
                                                            mosque.id,
                                                        )
                                                    "
                                                    class="text-emerald-600 hover:text-emerald-900"
                                                >
                                                    Kemaskini
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div
                                v-if="mosques.links && mosques.links.length > 3"
                                class="mt-4 flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6"
                            >
                                <div
                                    class="flex flex-1 justify-between sm:hidden"
                                >
                                    <Link
                                        v-if="mosques.prev_page_url"
                                        :href="mosques.prev_page_url"
                                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Sebelum
                                    </Link>
                                    <Link
                                        v-if="mosques.next_page_url"
                                        :href="mosques.next_page_url"
                                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Seterusnya
                                    </Link>
                                </div>
                                <div
                                    class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
                                >
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            Memaparkan
                                            <span class="font-medium">{{
                                                mosques.from
                                            }}</span>
                                            hingga
                                            <span class="font-medium">{{
                                                mosques.to
                                            }}</span>
                                            daripada
                                            <span class="font-medium">{{
                                                mosques.total
                                            }}</span>
                                            rekod
                                        </p>
                                    </div>
                                    <div>
                                        <nav
                                            class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                                            aria-label="Pagination"
                                        >
                                            <Link
                                                v-for="(
                                                    link, i
                                                ) in mosques.links"
                                                :key="i"
                                                v-html="link.label"
                                                :href="link.url"
                                                :class="[
                                                    link.active
                                                        ? 'relative z-10 inline-flex items-center bg-emerald-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600'
                                                        : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0',
                                                    i === 0
                                                        ? 'rounded-l-md'
                                                        : '',
                                                    i ===
                                                    mosques.links.length - 1
                                                        ? 'rounded-r-md'
                                                        : '',
                                                ]"
                                            >
                                            </Link>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

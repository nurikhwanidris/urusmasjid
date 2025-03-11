<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SelectInput from '@/Components/SelectInput.vue';

const props = defineProps({
    mosque: Object,
    events: Object,
});

// Search functionality
const search = ref('');
const filteredEvents = computed(() => {
    if (!search.value) return props.events.data;

    const searchTerm = search.value.toLowerCase();
    return props.events.data.filter(
        (event) =>
            event.title.toLowerCase().includes(searchTerm) ||
            event.description?.toLowerCase().includes(searchTerm) ||
            event.location?.toLowerCase().includes(searchTerm),
    );
});

// Helper function to parse ISO date string to Date object
const parseDate = (dateString) => {
    return new Date(dateString);
};

// Helper functions to compare dates
const isAfterDate = (date1, date2) => {
    return date1 > date2;
};

const isBeforeDate = (date1, date2) => {
    return date1 < date2;
};

const isSameDay = (date1, date2) => {
    return (
        date1.getFullYear() === date2.getFullYear() &&
        date1.getMonth() === date2.getMonth() &&
        date1.getDate() === date2.getDate()
    );
};

// Filter by event status
const statusFilter = ref('all');
const filteredByStatus = computed(() => {
    if (statusFilter.value === 'all') return filteredEvents.value;

    const today = new Date();

    return filteredEvents.value.filter((event) => {
        const startDate = parseDate(event.start_date);
        const endDate = parseDate(event.end_date);

        switch (statusFilter.value) {
            case 'upcoming':
                return isAfterDate(startDate, today);
            case 'ongoing':
                return (
                    (isAfterDate(today, startDate) ||
                        isSameDay(today, startDate)) &&
                    (isBeforeDate(today, endDate) || isSameDay(today, endDate))
                );
            case 'past':
                return isBeforeDate(endDate, today);
            case 'active':
                return event.status === 'active';
            case 'cancelled':
                return event.status === 'cancelled';
            case 'completed':
                return event.status === 'completed';
            default:
                return true;
        }
    });
});

// Format date for display
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('ms-MY', options);
};

// Get event status class
const getStatusClass = (event) => {
    const today = new Date();
    const startDate = parseDate(event.start_date);
    const endDate = parseDate(event.end_date);

    if (event.status === 'cancelled') {
        return 'bg-red-100 text-red-800';
    }

    if (isAfterDate(startDate, today)) {
        return 'bg-blue-100 text-blue-800';
    }

    if (
        (isAfterDate(today, startDate) || isSameDay(today, startDate)) &&
        (isBeforeDate(today, endDate) || isSameDay(today, endDate))
    ) {
        return 'bg-green-100 text-green-800';
    }

    return 'bg-gray-100 text-gray-800';
};

// Get event status text
const getStatusText = (event) => {
    const today = new Date();
    const startDate = parseDate(event.start_date);
    const endDate = parseDate(event.end_date);

    if (event.status === 'cancelled') {
        return 'Dibatalkan';
    }

    if (isAfterDate(startDate, today)) {
        return 'Akan Datang';
    }

    if (
        (isAfterDate(today, startDate) || isSameDay(today, startDate)) &&
        (isBeforeDate(today, endDate) || isSameDay(today, endDate))
    ) {
        return 'Sedang Berlangsung';
    }

    return 'Telah Berlalu';
};

const statusOptions = [
    { label: 'Semua', value: 'all' },
    { label: 'Akan Datang', value: 'upcoming' },
    { label: 'Sedang Berlangsung', value: 'ongoing' },
    { label: 'Telah Berlalu', value: 'past' },
];
</script>

<template>
    <Head :title="`Acara - ${mosque.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <Link
                    :href="route('masjid.acara.create', mosque.id)"
                    class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    Tambah Acara Baru
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Search and Filter -->
                        <div
                            class="mb-6 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0"
                        >
                            <div class="w-full sm:w-1/3">
                                <InputLabel
                                    for="search"
                                    value="Cari Acara"
                                    class="sr-only"
                                />
                                <TextInput
                                    id="search"
                                    v-model="search"
                                    type="text"
                                    class="block w-full"
                                    placeholder="Cari acara..."
                                />
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-700"
                                    >Tapis:</span
                                >
                                <SelectInput
                                    v-model="statusFilter"
                                    :options="statusOptions"
                                    class="w-40"
                                />
                            </div>
                        </div>

                        <!-- Events List -->
                        <div
                            v-if="filteredByStatus.length > 0"
                            class="overflow-hidden bg-white shadow sm:rounded-md"
                        >
                            <ul role="list" class="divide-y divide-gray-200">
                                <li
                                    v-for="event in filteredByStatus"
                                    :key="event.id"
                                >
                                    <Link
                                        :href="
                                            route('masjid.acara.show', [
                                                mosque.id,
                                                event.id,
                                            ])
                                        "
                                        class="block hover:bg-gray-50"
                                    >
                                        <div class="px-4 py-4 sm:px-6">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div
                                                    class="truncate text-sm font-medium text-emerald-600"
                                                >
                                                    {{ event.title }}
                                                </div>
                                                <div
                                                    class="ml-2 flex flex-shrink-0"
                                                >
                                                    <span
                                                        :class="[
                                                            getStatusClass(
                                                                event,
                                                            ),
                                                            'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                        ]"
                                                    >
                                                        {{
                                                            getStatusText(event)
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-2 sm:flex sm:justify-between"
                                            >
                                                <div class="sm:flex">
                                                    <p
                                                        class="flex items-center text-sm text-gray-500"
                                                    >
                                                        <svg
                                                            class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                                            viewBox="0 0 20 20"
                                                            fill="currentColor"
                                                        >
                                                            <path
                                                                fill-rule="evenodd"
                                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                                clip-rule="evenodd"
                                                            />
                                                        </svg>
                                                        {{
                                                            formatDate(
                                                                event.start_date,
                                                            )
                                                        }}
                                                        <span
                                                            v-if="
                                                                event.start_date !==
                                                                event.end_date
                                                            "
                                                        >
                                                            -
                                                            {{
                                                                formatDate(
                                                                    event.end_date,
                                                                )
                                                            }}
                                                        </span>
                                                    </p>
                                                    <p
                                                        v-if="event.location"
                                                        class="mt-2 flex items-center text-sm text-gray-500 sm:ml-6 sm:mt-0"
                                                    >
                                                        <svg
                                                            class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                                            viewBox="0 0 20 20"
                                                            fill="currentColor"
                                                        >
                                                            <path
                                                                fill-rule="evenodd"
                                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                                clip-rule="evenodd"
                                                            />
                                                        </svg>
                                                        {{ event.location }}
                                                    </p>
                                                </div>
                                                <div
                                                    v-if="
                                                        event.registration_required
                                                    "
                                                    class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0"
                                                >
                                                    <svg
                                                        class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                    Pendaftaran Diperlukan
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Empty State -->
                        <div
                            v-else
                            class="flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 p-12 text-center"
                        >
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                Tiada acara
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{
                                    search || statusFilter !== 'all'
                                        ? 'Tiada acara yang sepadan dengan carian anda.'
                                        : 'Mulakan dengan mencipta acara baru.'
                                }}
                            </p>
                            <div class="mt-6">
                                <Link
                                    :href="
                                        route('masjid.acara.create', mosque.id)
                                    "
                                    class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                >
                                    <svg
                                        class="-ml-1 mr-2 h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"
                                        />
                                    </svg>
                                    Tambah Acara Baru
                                </Link>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="events.data.length > 0" class="mt-6">
                            <Pagination :links="events.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Badge.vue';
import Pagination from '@/Components/Pagination.vue';
import SearchInput from '@/Components/SearchInput.vue';

const props = defineProps({
    mosques: Object,
});

const search = ref('');
const statusFilter = ref('all');

const filteredMosques = computed(() => {
    let result = props.mosques.data;

    // Apply status filter
    if (statusFilter.value !== 'all') {
        result = result.filter(
            (mosque) => mosque.verification_status === statusFilter.value,
        );
    }

    // Apply search filter
    if (search.value) {
        const searchTerm = search.value.toLowerCase();
        result = result.filter(
            (mosque) =>
                mosque.name.toLowerCase().includes(searchTerm) ||
                (mosque.location &&
                    mosque.location.toLowerCase().includes(searchTerm)) ||
                (mosque.user &&
                    mosque.user.name &&
                    mosque.user.name.toLowerCase().includes(searchTerm)),
        );
    }

    return result;
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

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="All Mosques" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link :href="route('admin.dashboard')">
                        <button
                            class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            Back to Dashboard
                        </button>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div
                            class="mb-6 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0"
                        >
                            <h3 class="text-lg font-medium text-gray-900">
                                Mosque Directory
                            </h3>
                            <div
                                class="flex flex-col space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0"
                            >
                                <div class="flex items-center">
                                    <label
                                        for="status-filter"
                                        class="mr-2 text-sm font-medium text-gray-700"
                                        >Status:</label
                                    >
                                    <select
                                        id="status-filter"
                                        v-model="statusFilter"
                                        class="rounded-md border-gray-300 py-1 pl-3 pr-10 text-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500"
                                    >
                                        <option value="all">All</option>
                                        <option value="pending">Pending</option>
                                        <option value="verified">
                                            Verified
                                        </option>
                                        <option value="rejected">
                                            Rejected
                                        </option>
                                    </select>
                                </div>
                                <SearchInput
                                    v-model="search"
                                    placeholder="Search mosques..."
                                    class="w-full sm:w-64"
                                />
                            </div>
                        </div>

                        <div
                            v-if="mosques.data.length === 0"
                            class="py-8 text-center text-gray-500"
                        >
                            No mosques found.
                        </div>

                        <div
                            v-else-if="filteredMosques.length === 0"
                            class="py-8 text-center text-gray-500"
                        >
                            No mosques match your filters.
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
                                                Mosque Name
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Location
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
                                                Verified By
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Actions
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
                                                    class="text-xs text-gray-500"
                                                >
                                                    {{
                                                        mosque.type === 'masjid'
                                                            ? 'Masjid'
                                                            : 'Surau'
                                                    }}
                                                    <span
                                                        v-if="
                                                            mosque.registration_number
                                                        "
                                                    >
                                                        •
                                                        {{
                                                            mosque.registration_number
                                                        }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    class="text-sm text-gray-900"
                                                >
                                                    {{ mosque.location }}
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500"
                                                >
                                                    {{ mosque.state }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
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
                                                <div
                                                    v-if="mosque.verified_at"
                                                    class="mt-1 text-xs text-gray-500"
                                                >
                                                    {{
                                                        formatDate(
                                                            mosque.verified_at,
                                                        )
                                                    }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    v-if="mosque.verifier"
                                                    class="text-sm text-gray-900"
                                                >
                                                    {{ mosque.verifier.name }}
                                                </div>
                                                <div
                                                    v-else
                                                    class="text-sm text-gray-500"
                                                >
                                                    -
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex justify-end space-x-2"
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'masjid.show',
                                                                mosque.id,
                                                            )
                                                        "
                                                        class="text-emerald-600 hover:text-emerald-900"
                                                        target="_blank"
                                                    >
                                                        View
                                                    </Link>
                                                    <span class="mr-1">|</span>
                                                    <Link
                                                        v-if="
                                                            mosque.verification_status ===
                                                            'pending'
                                                        "
                                                        :href="
                                                            route(
                                                                'admin.mosques.verify',
                                                                mosque.id,
                                                            )
                                                        "
                                                        class="text-emerald-600 hover:text-emerald-900"
                                                    >
                                                        Verify
                                                    </Link>
                                                    <Link
                                                        v-else
                                                        :href="
                                                            route(
                                                                'admin.mosques.verify',
                                                                mosque.id,
                                                            )
                                                        "
                                                        class="text-emerald-600 hover:text-emerald-900"
                                                    >
                                                        Update
                                                    </Link>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                <Pagination :links="mosques.links" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

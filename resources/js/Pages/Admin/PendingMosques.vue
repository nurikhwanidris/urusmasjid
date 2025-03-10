<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import SearchInput from '@/Components/SearchInput.vue';

const props = defineProps({
    mosques: Object,
});

const search = ref('');
const filteredMosques = computed(() => {
    if (!search.value) return props.mosques.data;

    const searchTerm = search.value.toLowerCase();
    return props.mosques.data.filter(
        (mosque) =>
            mosque.name.toLowerCase().includes(searchTerm) ||
            (mosque.location &&
                mosque.location.toLowerCase().includes(searchTerm)) ||
            (mosque.user &&
                mosque.user.name &&
                mosque.user.name.toLowerCase().includes(searchTerm)),
    );
});

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
    <Head title="Pending Mosques" />

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
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">
                                Mosques Pending Verification
                            </h3>
                            <SearchInput
                                v-model="search"
                                placeholder="Search mosques..."
                                class="w-64"
                            />
                        </div>

                        <div
                            v-if="mosques.data.length === 0"
                            class="py-8 text-center text-gray-500"
                        >
                            No pending mosques found.
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
                                                Submitted By
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Date Submitted
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
                                                <div
                                                    class="text-sm text-gray-900"
                                                >
                                                    {{
                                                        mosque.user
                                                            ? mosque.user.name
                                                            : 'Unknown'
                                                    }}
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500"
                                                >
                                                    {{
                                                        mosque.user
                                                            ? mosque.user.email
                                                            : ''
                                                    }}
                                                </div>
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
                                                    <Link
                                                        :href="
                                                            route(
                                                                'admin.mosques.verify',
                                                                mosque.id,
                                                            )
                                                        "
                                                        class="text-blue-600 hover:text-blue-900"
                                                    >
                                                        Verify
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

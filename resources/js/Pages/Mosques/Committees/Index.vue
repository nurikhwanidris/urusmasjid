<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Badge from '@/Components/Badge.vue';

defineProps({
    mosque: Object,
    committees: Array,
});

// Helper function to get status badge color
const getStatusColor = (status) => {
    switch (status) {
        case 'active':
            return 'success';
        case 'pending':
            return 'warning';
        case 'inactive':
            return 'secondary';
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
</script>

<template>
    <Head :title="`${mosque.name} - AJK`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link
                        :href="route('masjid.jawatankuasa.create', mosque.id)"
                    >
                        <PrimaryButton>Tambah AJK</PrimaryButton>
                    </Link>
                    <Link :href="route('masjid.show', mosque.id)">
                        <SecondaryButton>Kembali ke Masjid</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div
                            v-if="committees.length === 0"
                            class="py-8 text-center"
                        >
                            <p class="text-gray-500">Tiada AJK ditemui.</p>
                            <Link
                                :href="
                                    route(
                                        'masjid.jawatankuasa.create',
                                        mosque.id,
                                    )
                                "
                                class="mt-4 inline-block text-emerald-600 hover:text-emerald-500"
                            >
                                Tambah AJK pertama
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
                                                Jawatan
                                            </th>
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
                                                Maklumat Hubungan
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Tempoh
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Status
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
                                            v-for="committee in committees"
                                            :key="committee.id"
                                        >
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900"
                                            >
                                                {{ committee.role }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    class="font-medium text-gray-900"
                                                >
                                                    {{ committee.name }}
                                                </div>
                                                <div
                                                    v-if="committee.ic_number"
                                                    class="text-xs text-gray-500"
                                                >
                                                    IC:
                                                    {{ committee.ic_number }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                            >
                                                <div
                                                    v-if="
                                                        committee.phone_number
                                                    "
                                                >
                                                    {{ committee.phone_number }}
                                                </div>
                                                <div
                                                    v-if="committee.email"
                                                    class="text-xs"
                                                >
                                                    {{ committee.email }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-xs text-gray-500"
                                            >
                                                <div
                                                    v-if="committee.start_date"
                                                >
                                                    Mula:
                                                    {{
                                                        formatDate(
                                                            committee.start_date,
                                                        )
                                                    }}
                                                </div>
                                                <div
                                                    v-if="committee.end_date"
                                                    class="text-xs"
                                                >
                                                    Tamat:
                                                    {{
                                                        formatDate(
                                                            committee.end_date,
                                                        )
                                                    }}
                                                </div>
                                                <div
                                                    v-else-if="
                                                        committee.start_date
                                                    "
                                                    class="text-xs"
                                                >
                                                    Tamat: Tiada
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm"
                                            >
                                                <Badge
                                                    :color="
                                                        getStatusColor(
                                                            committee.status,
                                                        )
                                                    "
                                                >
                                                    {{
                                                        committee.status ===
                                                        'active'
                                                            ? 'Aktif'
                                                            : committee.status ===
                                                                'pending'
                                                              ? 'Menunggu'
                                                              : 'Tidak Aktif'
                                                    }}
                                                </Badge>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'masjid.jawatankuasa.show',
                                                            [
                                                                mosque.id,
                                                                committee.id,
                                                            ],
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
                                                            'masjid.jawatankuasa.edit',
                                                            [
                                                                mosque.id,
                                                                committee.id,
                                                            ],
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

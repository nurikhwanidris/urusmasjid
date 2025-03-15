<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    mosque: {
        type: Object,
        required: true,
    },
    donations: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({
            search: '',
            status: '',
            payment_method: '',
        }),
    },
});

const statuses = [
    { value: '', label: 'Semua Status' },
    { value: 'pending', label: 'Belum Selesai' },
    { value: 'completed', label: 'Selesai' },
    { value: 'failed', label: 'Gagal' },
];

const paymentMethods = [
    { value: '', label: 'Semua Kaedah' },
    { value: 'duitnow_qr', label: 'DuitNow QR' },
    { value: 'cash', label: 'Tunai' },
    { value: 'bank_transfer', label: 'Pemindahan Bank' },
];

const search = ref(props.filters.search);
const status = ref(props.filters.status);
const paymentMethod = ref(props.filters.payment_method);

const filter = () => {
    router.get(
        route('masjid.donations.index', props.mosque.id),
        {
            search: search.value,
            status: status.value,
            payment_method: paymentMethod.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const getStatusColor = (status) => {
    switch (status) {
        case 'completed':
            return 'bg-emerald-100 text-emerald-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'failed':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ms-MY', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
    });
};

const formatAmount = (amount) => {
    return new Intl.NumberFormat('ms-MY', {
        style: 'currency',
        currency: 'MYR',
    }).format(amount);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Senarai Derma - ' + mosque.name" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">
                                    Senarai Derma
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Senarai semua derma yang diterima
                                </p>
                            </div>
                            <Link
                                :href="
                                    route('masjid.donations.show', mosque.id)
                                "
                                class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                            >
                                Derma Sekarang
                            </Link>
                        </div>

                        <!-- Filters -->
                        <div class="mb-6 grid gap-4 md:grid-cols-3">
                            <div>
                                <TextInput
                                    v-model="search"
                                    type="search"
                                    class="w-full"
                                    placeholder="Cari nama penderma, no. resit..."
                                    @input="filter"
                                />
                            </div>
                            <select
                                v-model="status"
                                @change="filter"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                            >
                                <option
                                    v-for="status in statuses"
                                    :key="status.value"
                                    :value="status.value"
                                >
                                    {{ status.label }}
                                </option>
                            </select>
                            <select
                                v-model="paymentMethod"
                                @change="filter"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                            >
                                <option
                                    v-for="method in paymentMethods"
                                    :key="method.value"
                                    :value="method.value"
                                >
                                    {{ method.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Table -->
                        <div class="relative overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                    <tr>
                                        <th
                                            scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                        >
                                            No. Resit
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Tarikh
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Penderma
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Tujuan
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Jumlah
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Kaedah
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr
                                        v-for="donation in donations.data"
                                        :key="donation.id"
                                    >
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                        >
                                            {{ donation.receipt_number }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{
                                                formatDate(donation.created_at)
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <div>
                                                <div>
                                                    {{
                                                        donation.donor_name ||
                                                        'Tanpa Nama'
                                                    }}
                                                </div>
                                                <div
                                                    v-if="donation.donor_phone"
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ donation.donor_phone }}
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{ donation.purpose || '-' }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{ formatAmount(donation.amount) }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <span
                                                class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600"
                                            >
                                                {{
                                                    paymentMethods.find(
                                                        (m) =>
                                                            m.value ===
                                                            donation.payment_method,
                                                    )?.label
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <span
                                                class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium"
                                                :class="
                                                    getStatusColor(
                                                        donation.status,
                                                    )
                                                "
                                            >
                                                {{
                                                    statuses.find(
                                                        (s) =>
                                                            s.value ===
                                                            donation.status,
                                                    )?.label
                                                }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <Pagination :links="donations.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

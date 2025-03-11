<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import SelectInput from '@/Components/SelectInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    mosque: Object,
    event: Object,
    registrations: Object,
});

// Search functionality
const search = ref('');
const filteredRegistrations = computed(() => {
    if (!search.value) return props.registrations.data;

    const searchTerm = search.value.toLowerCase();
    return props.registrations.data.filter(
        (registration) =>
            registration.name.toLowerCase().includes(searchTerm) ||
            registration.email?.toLowerCase().includes(searchTerm) ||
            registration.phone?.toLowerCase().includes(searchTerm) ||
            registration.registration_number.toLowerCase().includes(searchTerm),
    );
});

// Filter by status
const statusFilter = ref('all');
const filteredByStatus = computed(() => {
    if (statusFilter.value === 'all') return filteredRegistrations.value;

    return filteredRegistrations.value.filter((registration) => {
        if (statusFilter.value === 'attended') {
            return registration.attendance_status === 'attended';
        } else if (statusFilter.value === 'absent') {
            return registration.attendance_status === 'absent';
        } else if (statusFilter.value === 'registered') {
            return (
                registration.attendance_status === 'registered' ||
                !registration.attendance_status
            );
        } else {
            return registration.status === statusFilter.value;
        }
    });
});

// Format date for display
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const options = {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return date.toLocaleDateString('ms-MY', options);
};

// Delete confirmation modal
const showDeleteModal = ref(false);
const registrationToDelete = ref(null);

const confirmDelete = (registration) => {
    registrationToDelete.value = registration;
    showDeleteModal.value = true;
};

const deleteRegistration = () => {
    if (!registrationToDelete.value) return;

    router.delete(
        route('masjid.acara.pendaftaran.destroy', [
            props.mosque.id,
            props.event.id,
            registrationToDelete.value.id,
        ]),
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                registrationToDelete.value = null;
            },
        },
    );
};

// Mark attendance
const markAttendance = (registration, status) => {
    router.patch(
        route('masjid.acara.pendaftaran.attendance', [
            props.mosque.id,
            props.event.id,
            registration.id,
        ]),
        {
            attendance_status: status,
        },
    );
};

// Get status badge class
const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'confirmed':
            return 'bg-green-100 text-green-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

// Get attendance status badge class
const getAttendanceBadgeClass = (status) => {
    switch (status) {
        case 'attended':
            return 'bg-green-100 text-green-800';
        case 'absent':
            return 'bg-red-100 text-red-800';
        case 'registered':
        default:
            return 'bg-blue-100 text-blue-800';
    }
};

const statusOptions = [
    { label: 'Semua', value: 'all' },
    { label: 'Disahkan', value: 'confirmed' },
    { label: 'Menunggu', value: 'pending' },
    { label: 'Dibatalkan', value: 'cancelled' },
    { label: 'Hadir', value: 'attended' },
    { label: 'Tidak Hadir', value: 'absent' },
    { label: 'Belum Hadir', value: 'registered' },
];
</script>

<template>
    <Head :title="`Pendaftaran - ${event.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link
                        :href="
                            route('masjid.acara.show', [mosque.id, event.id])
                        "
                        class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Kembali ke Acara
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Event Summary -->
                        <div class="mb-6 rounded-lg bg-gray-50 p-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Maklumat Acara
                            </h3>
                            <div
                                class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-3"
                            >
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Nama Acara
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ event.title }}
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Tarikh
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            formatDate(event.start_date).split(
                                                ',',
                                            )[0]
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
                                                ).split(',')[0]
                                            }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Jumlah Pendaftaran
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ registrations.total }}
                                    </p>
                                </div>
                                <div v-if="event.max_participants">
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Kapasiti
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ event.max_participants }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filter -->
                        <div
                            class="mb-6 flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0"
                        >
                            <div class="w-full sm:w-1/3">
                                <InputLabel
                                    for="search"
                                    value="Cari Pendaftaran"
                                    class="sr-only"
                                />
                                <TextInput
                                    id="search"
                                    v-model="search"
                                    type="text"
                                    class="block w-full"
                                    placeholder="Cari nama, emel, telefon, atau nombor pendaftaran..."
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

                        <!-- Registrations Table -->
                        <div
                            v-if="filteredByStatus.length > 0"
                            class="overflow-x-auto"
                        >
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                        >
                                            Nama
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Nombor Pendaftaran
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Emel / Telefon
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Status
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Kehadiran
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Tarikh Daftar
                                        </th>
                                        <th
                                            scope="col"
                                            class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                        >
                                            <span class="sr-only"
                                                >Tindakan</span
                                            >
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr
                                        v-for="registration in filteredByStatus"
                                        :key="registration.id"
                                    >
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                        >
                                            {{ registration.name }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{
                                                registration.registration_number
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <div v-if="registration.email">
                                                {{ registration.email }}
                                            </div>
                                            <div v-if="registration.phone">
                                                {{ registration.phone }}
                                            </div>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <span
                                                :class="[
                                                    getStatusBadgeClass(
                                                        registration.status,
                                                    ),
                                                    'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                ]"
                                            >
                                                {{
                                                    registration.status ===
                                                    'confirmed'
                                                        ? 'Disahkan'
                                                        : registration.status ===
                                                            'pending'
                                                          ? 'Menunggu'
                                                          : registration.status ===
                                                              'cancelled'
                                                            ? 'Dibatalkan'
                                                            : registration.status
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <span
                                                v-if="
                                                    registration.attendance_status
                                                "
                                                :class="[
                                                    getAttendanceBadgeClass(
                                                        registration.attendance_status,
                                                    ),
                                                    'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                ]"
                                            >
                                                {{
                                                    registration.attendance_status ===
                                                    'attended'
                                                        ? 'Hadir'
                                                        : registration.attendance_status ===
                                                            'absent'
                                                          ? 'Tidak Hadir'
                                                          : registration.attendance_status ===
                                                              'registered'
                                                            ? 'Belum Hadir'
                                                            : registration.attendance_status
                                                }}
                                            </span>
                                            <span
                                                v-else
                                                class="inline-flex rounded-full bg-blue-100 px-2 text-xs font-semibold leading-5 text-blue-800"
                                            >
                                                Belum Hadir
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{
                                                formatDate(
                                                    registration.created_at,
                                                )
                                            }}
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                        >
                                            <div class="flex space-x-2">
                                                <button
                                                    @click="
                                                        markAttendance(
                                                            registration,
                                                            'attended',
                                                        )
                                                    "
                                                    class="text-emerald-600 hover:text-emerald-900"
                                                    :class="{
                                                        'font-bold':
                                                            registration.attendance_status ===
                                                            'attended',
                                                    }"
                                                >
                                                    Hadir
                                                </button>
                                                <button
                                                    @click="
                                                        markAttendance(
                                                            registration,
                                                            'absent',
                                                        )
                                                    "
                                                    class="text-red-600 hover:text-red-900"
                                                    :class="{
                                                        'font-bold':
                                                            registration.attendance_status ===
                                                            'absent',
                                                    }"
                                                >
                                                    Tidak Hadir
                                                </button>
                                                <button
                                                    @click="
                                                        confirmDelete(
                                                            registration,
                                                        )
                                                    "
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Padam
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                Tiada pendaftaran
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{
                                    search || statusFilter !== 'all'
                                        ? 'Tiada pendaftaran yang sepadan dengan carian anda.'
                                        : 'Belum ada pendaftaran untuk acara ini.'
                                }}
                            </p>
                        </div>

                        <!-- Pagination -->
                        <div v-if="registrations.data.length > 0" class="mt-6">
                            <Pagination :links="registrations.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Adakah anda pasti mahu memadamkan pendaftaran ini?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Tindakan ini tidak boleh dibatalkan. Semua maklumat
                    berkaitan pendaftaran ini akan dipadam secara kekal.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="showDeleteModal = false"
                        >Batal</SecondaryButton
                    >
                    <DangerButton
                        @click="deleteRegistration"
                        :class="{
                            'opacity-25': $page.props.jetstream?.processing,
                        }"
                        :disabled="$page.props.jetstream?.processing"
                    >
                        Padam Pendaftaran
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

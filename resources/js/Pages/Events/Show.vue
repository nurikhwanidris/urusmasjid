<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    mosque: Object,
    event: Object,
    registrationCount: Number,
    userRegistered: Boolean,
    remainingSlots: Number,
    canRegister: Boolean,
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

// Format date for display
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return date.toLocaleDateString('ms-MY', options);
};

// Format time for display
const formatTime = (timeString) => {
    if (!timeString) return '';
    return timeString;
};

// Get event status
const getEventStatus = computed(() => {
    const today = new Date();
    const startDate = parseDate(props.event.start_date);
    const endDate = parseDate(props.event.end_date);

    if (props.event.status === 'cancelled') {
        return {
            text: 'Dibatalkan',
            class: 'bg-red-100 text-red-800',
        };
    }

    if (isAfterDate(startDate, today)) {
        return {
            text: 'Akan Datang',
            class: 'bg-blue-100 text-blue-800',
        };
    }

    if (
        (isAfterDate(today, startDate) || isSameDay(startDate, today)) &&
        (isBeforeDate(today, endDate) || isSameDay(endDate, today))
    ) {
        return {
            text: 'Sedang Berlangsung',
            class: 'bg-green-100 text-green-800',
        };
    }

    return {
        text: 'Telah Berlalu',
        class: 'bg-gray-100 text-gray-800',
    };
});

// Helper functions for event registration
const isRegistrationOpen = () => {
    if (!props.event.registration_required) return false;

    const today = new Date();

    // If there's a registration deadline, check if today is before or on the deadline
    if (props.event.registration_deadline) {
        const deadline = parseDate(props.event.registration_deadline);
        return isBeforeDate(today, deadline) || isSameDay(today, deadline);
    }

    // If no deadline, check if the event hasn't ended yet
    const endDate = parseDate(props.event.end_date);
    return isBeforeDate(today, endDate) || isSameDay(today, endDate);
};

const isFull = () => {
    if (!props.event.max_participants) return false;
    return props.registrationCount >= props.event.max_participants;
};

// Delete confirmation modal
const showDeleteModal = ref(false);
const confirmDelete = () => {
    router.delete(
        route('masjid.acara.destroy', [props.mosque.id, props.event.id]),
        {
            onSuccess: () => {
                showDeleteModal.value = false;
            },
        },
    );
};
</script>

<template>
    <Head :title="`${event.title} - ${mosque.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link
                        :href="route('masjid.acara.index', mosque.id)"
                        class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Kembali ke Senarai
                    </Link>
                    <Link
                        v-if="
                            $page.props.auth.user.id === event.created_by ||
                            $page.props.auth.user.can.update_mosque
                        "
                        :href="
                            route('masjid.acara.edit', [mosque.id, event.id])
                        "
                        class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Edit
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Event Status -->
                        <div class="mb-6 flex items-center justify-between">
                            <span
                                :class="[
                                    getEventStatus.class,
                                    'inline-flex rounded-full px-3 py-1 text-sm font-semibold',
                                ]"
                            >
                                {{ getEventStatus.text }}
                            </span>
                            <div
                                v-if="
                                    $page.props.auth.user.id ===
                                        event.created_by ||
                                    $page.props.auth.user.can.update_mosque
                                "
                            >
                                <DangerButton @click="showDeleteModal = true">
                                    Padam Acara
                                </DangerButton>
                            </div>
                        </div>

                        <!-- Event Image -->
                        <div
                            v-if="event.featured_image"
                            class="mb-6 overflow-hidden rounded-lg"
                        >
                            <img
                                :src="`/storage/${event.featured_image}`"
                                :alt="event.title"
                                class="h-64 w-full object-cover"
                            />
                        </div>

                        <!-- Event Details -->
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                            <!-- Main Content -->
                            <div class="lg:col-span-2">
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ event.title }}
                                </h3>
                                <p
                                    v-if="event.category"
                                    class="mt-1 text-sm text-gray-500"
                                >
                                    Kategori: {{ event.category }}
                                </p>

                                <div class="mt-6 space-y-6">
                                    <div
                                        v-if="event.description"
                                        class="prose max-w-none"
                                    >
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Penerangan
                                        </h4>
                                        <p class="whitespace-pre-line">
                                            {{ event.description }}
                                        </p>
                                    </div>

                                    <div
                                        v-if="
                                            event.is_online && event.online_url
                                        "
                                    >
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            URL Acara Dalam Talian
                                        </h4>
                                        <a
                                            :href="event.online_url"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-emerald-600 hover:text-emerald-500"
                                        >
                                            {{ event.online_url }}
                                        </a>
                                    </div>

                                    <div v-if="event.registration_required">
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Pendaftaran
                                        </h4>
                                        <div class="mt-2 space-y-2">
                                            <p
                                                v-if="
                                                    event.registration_deadline
                                                "
                                                class="text-sm text-gray-500"
                                            >
                                                Tarikh Tutup Pendaftaran:
                                                {{
                                                    formatDate(
                                                        event.registration_deadline,
                                                    )
                                                }}
                                            </p>
                                            <p
                                                v-if="event.max_participants"
                                                class="text-sm text-gray-500"
                                            >
                                                Bilangan Peserta Maksimum:
                                                {{ event.max_participants }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Pendaftaran Semasa:
                                                {{ registrationCount }} peserta
                                            </p>
                                            <p
                                                v-if="remainingSlots !== null"
                                                class="text-sm text-gray-500"
                                            >
                                                Slot Tersedia:
                                                {{ remainingSlots }}
                                            </p>

                                            <div class="mt-4">
                                                <div
                                                    v-if="userRegistered"
                                                    class="rounded-md bg-green-50 p-4"
                                                >
                                                    <div class="flex">
                                                        <div
                                                            class="flex-shrink-0"
                                                        >
                                                            <svg
                                                                class="h-5 w-5 text-green-400"
                                                                viewBox="0 0 20 20"
                                                                fill="currentColor"
                                                            >
                                                                <path
                                                                    fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd"
                                                                />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p
                                                                class="text-sm font-medium text-green-800"
                                                            >
                                                                Anda telah
                                                                mendaftar untuk
                                                                acara ini.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-else-if="canRegister">
                                                    <Link
                                                        :href="
                                                            route(
                                                                'masjid.acara.pendaftaran.create',
                                                                [
                                                                    mosque.id,
                                                                    event.id,
                                                                ],
                                                            )
                                                        "
                                                        class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                                    >
                                                        Daftar Sekarang
                                                    </Link>
                                                </div>
                                                <div
                                                    v-else-if="
                                                        !isRegistrationOpen()
                                                    "
                                                    class="rounded-md bg-yellow-50 p-4"
                                                >
                                                    <div class="flex">
                                                        <div
                                                            class="flex-shrink-0"
                                                        >
                                                            <svg
                                                                class="h-5 w-5 text-yellow-400"
                                                                viewBox="0 0 20 20"
                                                                fill="currentColor"
                                                            >
                                                                <path
                                                                    fill-rule="evenodd"
                                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd"
                                                                />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p
                                                                class="text-sm font-medium text-yellow-800"
                                                            >
                                                                Pendaftaran
                                                                telah ditutup.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    v-else-if="isFull()"
                                                    class="rounded-md bg-red-50 p-4"
                                                >
                                                    <div class="flex">
                                                        <div
                                                            class="flex-shrink-0"
                                                        >
                                                            <svg
                                                                class="h-5 w-5 text-red-400"
                                                                viewBox="0 0 20 20"
                                                                fill="currentColor"
                                                            >
                                                                <path
                                                                    fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                    clip-rule="evenodd"
                                                                />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p
                                                                class="text-sm font-medium text-red-800"
                                                            >
                                                                Acara ini telah
                                                                penuh.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        v-if="
                                            $page.props.auth.user.id ===
                                                event.created_by ||
                                            $page.props.auth.user.can
                                                .update_mosque
                                        "
                                    >
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Pengurusan Acara
                                        </h4>
                                        <div class="mt-2 space-y-2">
                                            <Link
                                                v-if="
                                                    event.registration_required
                                                "
                                                :href="
                                                    route(
                                                        'masjid.acara.pendaftaran.index',
                                                        [mosque.id, event.id],
                                                    )
                                                "
                                                class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                            >
                                                Lihat Pendaftaran ({{
                                                    registrationCount
                                                }})
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="lg:col-span-1">
                                <div
                                    class="rounded-lg bg-gray-50 p-6 shadow-sm"
                                >
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Maklumat Acara
                                    </h4>
                                    <dl class="mt-4 space-y-4">
                                        <div>
                                            <dt
                                                class="text-sm font-medium text-gray-500"
                                            >
                                                Tarikh
                                            </dt>
                                            <dd
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                {{
                                                    formatDate(event.start_date)
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
                                            </dd>
                                        </div>

                                        <div v-if="event.start_time">
                                            <dt
                                                class="text-sm font-medium text-gray-500"
                                            >
                                                Masa
                                            </dt>
                                            <dd
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                {{
                                                    formatTime(event.start_time)
                                                }}
                                                <span v-if="event.end_time">
                                                    -
                                                    {{
                                                        formatTime(
                                                            event.end_time,
                                                        )
                                                    }}
                                                </span>
                                            </dd>
                                        </div>

                                        <div v-if="event.location">
                                            <dt
                                                class="text-sm font-medium text-gray-500"
                                            >
                                                Lokasi
                                            </dt>
                                            <dd
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                {{ event.location }}
                                            </dd>
                                        </div>

                                        <div v-if="event.address">
                                            <dt
                                                class="text-sm font-medium text-gray-500"
                                            >
                                                Alamat
                                            </dt>
                                            <dd
                                                class="mt-1 whitespace-pre-line text-sm text-gray-900"
                                            >
                                                {{ event.address }}
                                            </dd>
                                        </div>

                                        <div v-if="event.is_online">
                                            <dt
                                                class="text-sm font-medium text-gray-500"
                                            >
                                                Jenis Acara
                                            </dt>
                                            <dd
                                                class="mt-1 text-sm text-gray-900"
                                            >
                                                Dalam Talian
                                            </dd>
                                        </div>
                                    </dl>

                                    <div
                                        class="mt-6 border-t border-gray-200 pt-6"
                                    >
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Maklumat Hubungan
                                        </h4>
                                        <dl class="mt-4 space-y-4">
                                            <div v-if="event.contact_person">
                                                <dt
                                                    class="text-sm font-medium text-gray-500"
                                                >
                                                    Pegawai Hubungan
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm text-gray-900"
                                                >
                                                    {{ event.contact_person }}
                                                </dd>
                                            </div>

                                            <div v-if="event.contact_phone">
                                                <dt
                                                    class="text-sm font-medium text-gray-500"
                                                >
                                                    Nombor Telefon
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm text-gray-900"
                                                >
                                                    {{ event.contact_phone }}
                                                </dd>
                                            </div>

                                            <div v-if="event.contact_email">
                                                <dt
                                                    class="text-sm font-medium text-gray-500"
                                                >
                                                    Emel
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm text-gray-900"
                                                >
                                                    <a
                                                        :href="`mailto:${event.contact_email}`"
                                                        class="text-emerald-600 hover:text-emerald-500"
                                                    >
                                                        {{
                                                            event.contact_email
                                                        }}
                                                    </a>
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Adakah anda pasti mahu memadamkan acara ini?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Tindakan ini tidak boleh dibatalkan. Semua maklumat
                    berkaitan acara ini akan dipadam secara kekal.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="showDeleteModal = false"
                        >Batal</SecondaryButton
                    >
                    <DangerButton
                        @click="confirmDelete"
                        :class="{
                            'opacity-25': $page.props.jetstream?.processing,
                        }"
                        :disabled="$page.props.jetstream?.processing"
                    >
                        Padam Acara
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

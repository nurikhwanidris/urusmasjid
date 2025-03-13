<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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

// Notification function
const notificationSent = ref(false);
const notificationChannel = ref('');
const notificationMessage = ref('');

const sendNotification = (channel) => {
    // In a real implementation, this would call an API endpoint to send notifications
    // For now, we'll just simulate the notification being sent
    notificationChannel.value = channel;

    let channelName = '';
    switch (channel) {
        case 'whatsapp':
            channelName = 'WhatsApp';
            break;
        case 'telegram':
            channelName = 'Telegram';
            break;
        case 'email':
            channelName = 'Email';
            break;
    }

    notificationMessage.value = `Notifikasi telah dihantar melalui ${channelName} kepada semua peserta.`;
    notificationSent.value = true;

    // Hide the notification message after 3 seconds
    setTimeout(() => {
        notificationSent.value = false;
    }, 3000);
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

                                    <!-- Speaker Information (New Section) -->
                                    <div v-if="event.speaker">
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Penceramah / Pembentang
                                        </h4>
                                        <div class="mt-2 flex items-start">
                                            <div
                                                v-if="event.speaker_image"
                                                class="flex-shrink-0"
                                            >
                                                <img
                                                    :src="
                                                        event.speaker_image
                                                            ? '/images/default-avatar.png'
                                                            : `/storage/${event.speaker_image}`
                                                    "
                                                    :alt="event.speaker"
                                                    class="h-12 w-12 rounded-full object-cover"
                                                />
                                            </div>
                                            <div class="ml-3">
                                                <p
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ event.speaker }}
                                                </p>
                                                <p
                                                    v-if="event.speaker_bio"
                                                    class="mt-1 text-sm text-gray-500"
                                                >
                                                    {{ event.speaker_bio }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Volunteers Information (New Section) -->
                                    <div
                                        v-if="
                                            event.volunteers &&
                                            event.volunteers.length > 0
                                        "
                                    >
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Sukarelawan
                                        </h4>
                                        <ul class="mt-2 space-y-2">
                                            <li
                                                v-for="volunteer in event.volunteers"
                                                :key="volunteer.id"
                                                class="text-sm text-gray-700"
                                            >
                                                {{ volunteer.name }} -
                                                {{
                                                    volunteer.role ||
                                                    'Sukarelawan'
                                                }}
                                            </li>
                                        </ul>
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

                                            <!-- Notification Options (New Section) -->
                                            <div class="mt-4">
                                                <h5
                                                    class="text-sm font-medium text-gray-700"
                                                >
                                                    Hantar Notifikasi Kepada
                                                    Peserta
                                                </h5>
                                                <div
                                                    class="mt-2 flex flex-wrap gap-2"
                                                >
                                                    <button
                                                        class="inline-flex items-center rounded-md bg-green-500 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                                        @click="
                                                            sendNotification(
                                                                'whatsapp',
                                                            )
                                                        "
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="mr-1.5 h-4 w-4"
                                                            fill="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"
                                                            />
                                                        </svg>
                                                        WhatsApp
                                                    </button>
                                                    <button
                                                        class="inline-flex items-center rounded-md bg-blue-500 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                                        @click="
                                                            sendNotification(
                                                                'telegram',
                                                            )
                                                        "
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="mr-1.5 h-4 w-4"
                                                            fill="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                d="M12 0c-6.626 0-12 5.372-12 12 0 6.627 5.374 12 12 12 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12zm3.224 17.871c.188.133.43.166.619.098.189-.068.34-.25.416-.439.728-1.841 2.261-7.268 2.686-9.144.068-.302-.037-.531-.288-.674-.253-.143-.566-.091-.756.068-1.833 1.523-7.358 6.244-8.073 6.859-.082.071-.275.005-.274-.151.003-.242.016-2.927.026-4.043.005-.517-.46-.966-.984-.929-.524.036-.904.511-.889 1.027.031 1.047.268 6.342.313 7.337.009.199.143.358.341.38.199.023.388-.105.443-.292.142-.481.509-1.773.669-2.33.028-.099.102-.145.197-.129.096.016.164.088.172.185.013.167.376 3.037.468 3.377.077.276.305.488.59.541.285.053.571-.075.732-.304.255-.363.754-1.265.944-1.607z"
                                                            />
                                                        </svg>
                                                        Telegram
                                                    </button>
                                                    <button
                                                        class="inline-flex items-center rounded-md bg-gray-500 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                                        @click="
                                                            sendNotification(
                                                                'email',
                                                            )
                                                        "
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="mr-1.5 h-4 w-4"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                            />
                                                        </svg>
                                                        Email
                                                    </button>
                                                </div>

                                                <!-- Notification Success Message -->
                                                <div
                                                    v-if="notificationSent"
                                                    class="mt-3 rounded-md bg-green-50 p-3"
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
                                                                {{
                                                                    notificationMessage
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { usePrayerTimes } from '@/Composables/usePrayerTimes';

// Dummy data for dashboard
const upcomingEvents = ref([
    {
        id: 1,
        title: 'Solat Jumaat',
        date: '2023-06-09',
        time: '12:30 PM',
        location: 'Masjid Utama',
        description: 'Solat Jumaat',
        attendees: 120,
    },
    {
        id: 2,
        title: 'Kelas Quran',
        date: '2023-06-10',
        time: '8:00 PM',
        location: 'Kelas 2',
        description: 'Kelas Quran mingguan dan pembicaraan',
        attendees: 25,
    },
    {
        id: 3,
        title: 'Gotong Royong',
        date: '2023-06-11',
        time: '7:15 PM',
        location: 'Persekitaran Masjid',
        description: 'Gotong royong bersama ahli khariah masjid',
        attendees: 85,
    },
]);

const recentDonations = ref([
    {
        id: 1,
        amount: 'RM 500',
        donor: 'Anonymous',
        date: '2023-06-08',
        category: 'General Fund',
    },
    {
        id: 2,
        amount: 'RM 1,200',
        donor: 'Ahmad bin Abdullah',
        date: '2023-06-07',
        category: 'Building Maintenance',
    },
    {
        id: 3,
        amount: 'RM 300',
        donor: 'Fatimah binti Hassan',
        date: '2023-06-06',
        category: 'Charity Fund',
    },
]);

const masjidStats = ref({
    totalMembers: 450,
    averageAttendance: 320,
    monthlyDonations: 'RM 15,000',
    upcomingEvents: 8,
    activeCourses: 5,
});

const announcements = ref([
    {
        id: 1,
        title: 'Jadual Solat Bulan Ramadan',
        date: '05-03-2025',
        content:
            'Jadual solat Ramadan telah diperbarui. Sila semak papan pengumuman untuk maklumat lanjut.',
        priority: 'high',
    },
    {
        id: 2,
        title: 'Kerja Pembaikan',
        date: '04-03-2025',
        content:
            'Area parkir akan ditutup untuk pembaikan dari 15-17 Jun. Sila gunakan parkir alternatif.',
        priority: 'medium',
    },
    {
        id: 3,
        title: 'Lantikan Imam Baru',
        date: '02-03-2025',
        content:
            'Ustaz Ahmad telah dipilih sebagai imam baru kami. Sila semak papan pengumuman untuk maklumat lanjut.',
        priority: 'normal',
    },
]);

const tasks = ref([
    { id: 1, title: 'Update prayer schedule', completed: true },
    { id: 2, title: 'Organize community iftar', completed: false },
    { id: 3, title: 'Prepare for Eid celebration', completed: false },
    { id: 4, title: 'Maintenance of sound system', completed: false },
]);

const completedTasks = computed(() => {
    return tasks.value.filter((task) => task.completed).length;
});

const totalTasks = computed(() => {
    return tasks.value.length;
});

const toggleTask = (id) => {
    const task = tasks.value.find((task) => task.id === id);
    if (task) {
        task.completed = !task.completed;
    }
};

const getPriorityClass = (priority) => {
    switch (priority) {
        case 'high':
            return 'bg-red-100 text-red-800';
        case 'medium':
            return 'bg-yellow-100 text-yellow-800';
        default:
            return 'bg-blue-100 text-blue-800';
    }
};

// Initialize prayer times
const {
    zones,
    prayerTimes,
    loading,
    error,
    selectedZone,
    fetchPrayerTimes,
    changeZone,
    setupAutoRefresh,
    cleanup,
    nextPrayer,
    countdownDisplay,
} = usePrayerTimes();

// Helper function to get unique states from zones
const getUniqueStates = () => {
    const states = new Set();
    zones.value.forEach((zone) => {
        const state = zone.name.split(' - ')[0];
        states.add(state);
    });
    return Array.from(states).sort();
};

// Helper function to get zones by state
const getZonesByState = (state) => {
    return zones.value.filter((zone) => zone.name.startsWith(state));
};

// Fetch prayer times on component mount
onMounted(() => {
    fetchPrayerTimes();
    setupAutoRefresh();
});

// Clean up intervals when component is unmounted
onBeforeUnmount(() => {
    cleanup();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Welcome Banner -->
                <div
                    class="mb-6 overflow-hidden rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-700 shadow-md"
                >
                    <div
                        class="px-6 py-8 md:flex md:items-center md:justify-between"
                    >
                        <div class="text-center md:text-left">
                            <h1
                                class="text-2xl font-bold text-white md:text-3xl"
                            >
                                Selamat Datang ke Urus Masjid
                            </h1>
                            <p class="mt-2 text-emerald-100">
                                Sistem pengurusan masjid dan surau yang
                                komprehensif
                            </p>
                        </div>
                        <div class="mt-4 flex justify-center md:mt-0">
                            <button
                                class="rounded-md bg-white px-4 py-2 text-sm font-medium text-emerald-700 shadow-sm hover:bg-emerald-50"
                            >
                                Lihat Panduan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="mb-6 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Members -->
                    <div
                        class="overflow-hidden rounded-lg bg-white p-6 shadow hover:bg-emerald-50"
                    >
                        <div class="flex items-center">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-md bg-emerald-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-emerald-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">
                                    Ahli Khariah
                                </h3>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ masjidStats.totalMembers }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance -->
                    <div
                        class="overflow-hidden rounded-lg bg-white p-6 shadow hover:bg-emerald-50"
                    >
                        <div class="flex items-center">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-md bg-emerald-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-emerald-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">
                                    Kehadiran Purata
                                </h3>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ masjidStats.averageAttendance }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Donations -->
                    <div
                        class="overflow-hidden rounded-lg bg-white p-6 shadow hover:bg-emerald-50"
                    >
                        <div class="flex items-center">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-md bg-emerald-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-emerald-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">
                                    Sumbangan Bulanan
                                </h3>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ masjidStats.monthlyDonations }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Events -->
                    <div
                        class="overflow-hidden rounded-lg bg-white p-6 shadow hover:bg-emerald-50"
                    >
                        <div class="flex items-center">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-md bg-emerald-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-emerald-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">
                                    Acara Akan Datang
                                </h3>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ masjidStats.upcomingEvents }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="grid gap-6 lg:grid-cols-3">
                    <!-- Left Column -->
                    <div class="space-y-6 lg:col-span-2">
                        <!-- Prayer Times -->
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Waktu Solat
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            {{
                                                prayerTimes?.date ||
                                                'Loading...'
                                            }}
                                        </p>
                                    </div>
                                    <div class="relative">
                                        <select
                                            v-model="selectedZone"
                                            @change="changeZone(selectedZone)"
                                            class="w-48 rounded-md border-gray-300 py-1 pl-3 pr-10 text-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500"
                                        >
                                            <optgroup
                                                v-for="state in getUniqueStates()"
                                                :key="state"
                                                :label="state"
                                            >
                                                <option
                                                    v-for="zone in getZonesByState(
                                                        state,
                                                    )"
                                                    :key="zone.code"
                                                    :value="zone.code"
                                                    class="truncate"
                                                >
                                                    {{
                                                        zone.name.split(
                                                            ' - ',
                                                        )[1]
                                                    }}
                                                </option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="loading"
                                class="flex items-center justify-center px-6 py-8"
                            >
                                <div class="text-center">
                                    <div
                                        class="mb-2 h-8 w-8 animate-spin rounded-full border-4 border-emerald-200 border-t-emerald-600"
                                    ></div>
                                    <p class="text-sm text-gray-500">
                                        Memuat waktu solat...
                                    </p>
                                </div>
                            </div>
                            <div
                                v-else-if="error && !prayerTimes"
                                class="px-6 py-4"
                            >
                                <div class="rounded-md bg-red-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
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
                                            <p class="text-sm text-red-700">
                                                {{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="px-6 py-4">
                                <div
                                    v-if="error"
                                    class="mb-4 rounded-md bg-yellow-50 p-3"
                                >
                                    <div class="flex">
                                        <div class="flex-shrink-0">
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
                                            <p class="text-sm text-yellow-700">
                                                Menggunakan data sementara.
                                                Waktu solat mungkin tidak tepat.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="mb-4 flex items-center justify-center rounded-lg bg-emerald-50 p-4"
                                >
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Solat Seterusnya
                                        </p>
                                        <p
                                            class="text-2xl font-bold text-emerald-600"
                                        >
                                            {{ nextPrayer?.name }}
                                        </p>
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            {{ countdownDisplay }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-6"
                                >
                                    <div class="text-center">
                                        <img
                                            src="/images/fajr.png"
                                            alt="Sunrise"
                                            class="mx-auto mb-2 h-10 w-10"
                                        />
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Subuh
                                        </p>
                                        <p
                                            class="text-base font-semibold text-gray-900"
                                        >
                                            {{ prayerTimes.times.subuh }}
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <img
                                            src="/images/syuruk.png"
                                            alt="Syuruk"
                                            class="mx-auto mb-2 h-10 w-10"
                                        />
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Syuruk
                                        </p>
                                        <p
                                            class="text-base font-semibold text-gray-900"
                                        >
                                            {{ prayerTimes.times.syuruk }}
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <img
                                            src="/images/dhuhr.png"
                                            alt="Zohor"
                                            class="mx-auto mb-2 h-10 w-10"
                                        />
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Zohor
                                        </p>
                                        <p
                                            class="text-base font-semibold text-gray-900"
                                        >
                                            {{ prayerTimes.times.zohor }}
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <img
                                            src="/images/asr.png"
                                            alt="Asar"
                                            class="mx-auto mb-2 h-10 w-10"
                                        />
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Asar
                                        </p>
                                        <p
                                            class="text-base font-semibold text-gray-900"
                                        >
                                            {{ prayerTimes.times.asar }}
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <img
                                            src="/images/maghrib.png"
                                            alt="Maghrib"
                                            class="mx-auto mb-2 h-10 w-10"
                                        />
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Maghrib
                                        </p>
                                        <p
                                            class="text-base font-semibold text-gray-900"
                                        >
                                            {{ prayerTimes.times.maghrib }}
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <img
                                            src="/images/isha.png"
                                            alt="Isha"
                                            class="mx-auto mb-2 h-10 w-10"
                                        />
                                        <p
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Isyak
                                        </p>
                                        <p
                                            class="text-base font-semibold text-gray-900"
                                        >
                                            {{ prayerTimes.times.isyak }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Events -->
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div
                                class="flex items-center justify-between border-b border-gray-200 px-6 py-4"
                            >
                                <h3 class="text-lg font-medium text-gray-900">
                                    Acara Akan Datang
                                </h3>
                                <a
                                    href="#"
                                    class="text-sm font-medium text-emerald-600 hover:text-emerald-500"
                                    >Lihat Semua</a
                                >
                            </div>
                            <div class="divide-y divide-gray-200">
                                <div
                                    v-for="event in upcomingEvents"
                                    :key="event.id"
                                    class="px-6 py-4"
                                >
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 rounded-md bg-emerald-100 p-2 text-center"
                                        >
                                            <span
                                                class="block text-sm font-semibold text-emerald-800"
                                                >{{
                                                    new Date(
                                                        event.date,
                                                    ).toLocaleDateString(
                                                        'en-US',
                                                        {
                                                            day: 'numeric',
                                                        },
                                                    )
                                                }}</span
                                            >
                                            <span
                                                class="block text-xs text-emerald-600"
                                                >{{
                                                    new Date(
                                                        event.date,
                                                    ).toLocaleDateString(
                                                        'en-US',
                                                        {
                                                            month: 'short',
                                                        },
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h4
                                                class="text-base font-medium text-gray-900"
                                            >
                                                {{ event.title }}
                                            </h4>
                                            <p
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                {{ event.time }} •
                                                {{ event.location }}
                                            </p>
                                            <p
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                {{ event.description }}
                                            </p>
                                            <div class="mt-2 flex items-center">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 text-gray-400"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                                    />
                                                </svg>
                                                <span
                                                    class="ml-1 text-xs text-gray-500"
                                                    >{{
                                                        event.attendees
                                                    }}
                                                    peserta</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Announcements -->
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Pengumuman
                                </h3>
                            </div>
                            <div class="divide-y divide-gray-200">
                                <div
                                    v-for="announcement in announcements"
                                    :key="announcement.id"
                                    class="px-6 py-4"
                                >
                                    <div class="flex items-start">
                                        <div class="flex-1">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <h4
                                                    class="text-base font-medium text-gray-900"
                                                >
                                                    {{ announcement.title }}
                                                </h4>
                                                <span
                                                    :class="[
                                                        'ml-2 rounded-full px-2 py-0.5 text-xs font-medium',
                                                        getPriorityClass(
                                                            announcement.priority,
                                                        ),
                                                    ]"
                                                >
                                                    {{
                                                        announcement.priority ===
                                                        'high'
                                                            ? 'Penting'
                                                            : announcement.priority ===
                                                                'medium'
                                                              ? 'Sederhana'
                                                              : 'Biasa'
                                                    }}
                                                </span>
                                            </div>
                                            <p
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                {{ announcement.content }}
                                            </p>
                                            <p
                                                class="mt-2 text-xs text-gray-400"
                                            >
                                                {{ announcement.date }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Donations -->
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Sumbangan Terkini
                                </h3>
                            </div>
                            <div class="divide-y divide-gray-200">
                                <div
                                    v-for="donation in recentDonations"
                                    :key="donation.id"
                                    class="px-6 py-4"
                                >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ donation.donor }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ donation.date }} •
                                                {{ donation.category }}
                                            </p>
                                        </div>
                                        <span
                                            class="font-medium text-emerald-600"
                                            >{{ donation.amount }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4">
                                <a
                                    href="#"
                                    class="block text-center text-sm font-medium text-emerald-600 hover:text-emerald-500"
                                    >Lihat Semua Sumbangan</a
                                >
                            </div>
                        </div>

                        <!-- Tasks -->
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Tugasan
                                    </h3>
                                    <span
                                        class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800"
                                    >
                                        {{ completedTasks }}/{{ totalTasks }}
                                    </span>
                                </div>
                            </div>
                            <div class="px-6 py-4">
                                <ul class="divide-y divide-gray-200">
                                    <li
                                        v-for="task in tasks"
                                        :key="task.id"
                                        class="flex items-center py-2"
                                    >
                                        <input
                                            :id="`task-${task.id}`"
                                            type="checkbox"
                                            :checked="task.completed"
                                            @change="toggleTask(task.id)"
                                            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                        />
                                        <label
                                            :for="`task-${task.id}`"
                                            class="ml-3 text-sm font-medium"
                                            :class="
                                                task.completed
                                                    ? 'text-gray-400 line-through'
                                                    : 'text-gray-700'
                                            "
                                        >
                                            {{ task.title }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4">
                                <button
                                    class="flex w-full items-center justify-center rounded-md border border-transparent bg-emerald-100 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-200"
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
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                        />
                                    </svg>
                                    Tambah Tugasan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

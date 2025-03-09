<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};
</script>

<template>
    <Head title="Urus Masjid - Sistem Pengurusan Masjid & Surau">
        <link
            rel="icon"
            href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🕌</text></svg>"
        />
    </Head>
    <div class="min-h-screen bg-gradient-to-b from-white to-emerald-50">
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <header class="flex items-center justify-between py-6">
                <div class="flex items-center gap-4">
                    <ApplicationLogo class="h-[50px] w-auto" />
                    <span class="text-xl font-semibold text-emerald-900"
                        >Urus Masjid</span
                    >
                </div>
                <nav v-if="canLogin" class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            Log Masuk
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-full border border-emerald-600 px-4 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            Daftar
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="py-16">
                <!-- Hero Section -->
                <div class="mb-24 text-center">
                    <h1
                        class="mb-6 text-5xl font-bold tracking-tight text-emerald-900"
                    >
                        Sistem Pengurusan Masjid & Surau Moden
                    </h1>
                    <p class="mx-auto mb-12 max-w-2xl text-lg text-gray-600">
                        Platform pengurusan masjid dan surau yang komprehensif
                        untuk memudahkan pengurusan, meningkatkan keberkesanan,
                        dan menghubungkan komuniti.
                    </p>
                    <div class="flex justify-center gap-4">
                        <Link
                            :href="route('register')"
                            v-if="canRegister"
                            class="rounded-full bg-emerald-600 px-8 py-3 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            Mulakan Sekarang
                        </Link>
                        <button
                            @click="scrollToSection('features')"
                            class="rounded-full border border-emerald-600 px-8 py-3 text-base font-medium text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            Ketahui Lebih Lanjut
                        </button>
                    </div>
                </div>

                <!-- Feature Cards -->
                <div id="features" class="mb-24">
                    <h2
                        class="mb-12 text-center text-3xl font-bold text-emerald-900"
                    >
                        Ciri-ciri Utama
                    </h2>
                    <div class="grid gap-8 md:grid-cols-2">
                        <button
                            @click="scrollToSection('mosque-registration')"
                            class="group rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-emerald-200"
                        >
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                            >
                                <svg
                                    class="h-6 w-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                            </div>
                            <h3
                                class="mb-2 text-xl font-semibold text-emerald-900"
                            >
                                Pendaftaran Masjid & Surau
                            </h3>
                            <p class="text-gray-600">
                                Daftarkan masjid dan surau dengan mudah. Urus
                                maklumat seperti nama, lokasi, dan maklumat
                                perhubungan.
                            </p>
                        </button>

                        <button
                            @click="scrollToSection('community-management')"
                            class="group rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-emerald-200"
                        >
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                            >
                                <svg
                                    class="h-6 w-6"
                                    xmlns="http://www.w3.org/2000/svg"
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
                            <h3
                                class="mb-2 text-xl font-semibold text-emerald-900"
                            >
                                Pengurusan Kariah
                            </h3>
                            <p class="text-gray-600">
                                Tambah dan urus ahli kariah dengan cekap. Rekod
                                status ahli, hubungan keluarga, dan maklumat
                                penting.
                            </p>
                        </button>

                        <button
                            @click="scrollToSection('khutbah-management')"
                            class="group rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-emerald-200"
                        >
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                            >
                                <svg
                                    class="h-6 w-6"
                                    xmlns="http://www.w3.org/2000/svg"
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
                            <h3
                                class="mb-2 text-xl font-semibold text-emerald-900"
                            >
                                Pengurusan Khutbah
                            </h3>
                            <p class="text-gray-600">
                                Jadualkan khutbah dan lantik khatib dengan
                                mudah. Sistem cadangan topik pintar dan arkib
                                khutbah.
                            </p>
                        </button>

                        <button
                            @click="scrollToSection('event-management')"
                            class="group rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-emerald-200"
                        >
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                            >
                                <svg
                                    class="h-6 w-6"
                                    xmlns="http://www.w3.org/2000/svg"
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
                            </div>
                            <h3
                                class="mb-2 text-xl font-semibold text-emerald-900"
                            >
                                Pengurusan Acara & Aktiviti
                            </h3>
                            <p class="text-gray-600">
                                Urus acara keagamaan, ceramah, dan program
                                komuniti dengan cekap dan teratur.
                            </p>
                        </button>
                    </div>
                </div>

                <!-- Detailed Feature Sections -->
                <div class="space-y-32">
                    <!-- Mosque Registration Section -->
                    <section id="mosque-registration" class="scroll-mt-16">
                        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                            <div>
                                <h2
                                    class="mb-6 text-3xl font-bold text-emerald-900"
                                >
                                    Pendaftaran Masjid & Surau
                                </h2>
                                <div class="space-y-4">
                                    <p class="text-gray-600">
                                        Sistem pendaftaran yang komprehensif
                                        untuk masjid dan surau dengan ciri-ciri:
                                    </p>
                                    <ul class="space-y-3">
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Pendaftaran mudah dengan borang
                                                yang lengkap</span
                                            >
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Pengurusan maklumat lokasi dan
                                                perhubungan</span
                                            >
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Pengurusan pelbagai masjid
                                                dalam satu platform</span
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="rounded-2xl bg-emerald-50 p-8">
                                <img
                                    src="/images/masjid.svg"
                                    alt="Pengurusan Masjid/Surau"
                                    class="w-full rounded-2xl"
                                />
                            </div>
                        </div>
                    </section>

                    <!-- Community Management Section -->
                    <section id="community-management" class="scroll-mt-16">
                        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                            <div
                                class="order-2 rounded-2xl bg-emerald-50 p-8 lg:order-1"
                            >
                                <img
                                    src="/images/khariah.svg"
                                    alt="Pengurusan Kariah"
                                    class="w-full rounded-2xl"
                                />
                            </div>
                            <div class="order-1 lg:order-2">
                                <h2
                                    class="mb-6 text-3xl font-bold text-emerald-900"
                                >
                                    Pengurusan Kariah
                                </h2>
                                <div class="space-y-4">
                                    <p class="text-gray-600">
                                        Sistem pengurusan ahli kariah yang
                                        efisien dengan ciri-ciri:
                                    </p>
                                    <ul class="space-y-3">
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Pendaftaran dan pengurusan ahli
                                                kariah</span
                                            >
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Rekod hubungan keluarga dan
                                                status ahli</span
                                            >
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Pengurusan maklumat
                                                kecemasan</span
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Khutbah Management Section -->
                    <section id="khutbah-management" class="scroll-mt-16">
                        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                            <div>
                                <h2
                                    class="mb-6 text-3xl font-bold text-emerald-900"
                                >
                                    Pengurusan Khutbah
                                </h2>
                                <div class="space-y-4">
                                    <p class="text-gray-600">
                                        Sistem pengurusan khutbah yang pintar
                                        dengan ciri-ciri:
                                    </p>
                                    <ul class="space-y-3">
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Penjadual khutbah dan
                                                pelantikan khatib</span
                                            >
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Cadangan topik khutbah
                                                berdasarkan AI</span
                                            >
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Arkib khutbah dan sistem
                                                peringatan</span
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="rounded-2xl bg-emerald-50 p-8">
                                <img
                                    src="/images/khutbah.svg"
                                    alt="Pengurusan Khutbah"
                                    class="w-full rounded-2xl"
                                />
                            </div>
                        </div>
                    </section>

                    <!-- Event Management Section -->
                    <section id="event-management" class="scroll-mt-16">
                        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                            <div
                                class="order-2 rounded-2xl bg-emerald-50 p-8 lg:order-1"
                            >
                                <img
                                    src="/images/acara.svg"
                                    alt="Pengurusan Acara & Aktiviti"
                                    class="w-full"
                                />
                            </div>
                            <div class="order-1 lg:order-2">
                                <h2
                                    class="mb-6 text-3xl font-bold text-emerald-900"
                                >
                                    Pengurusan Acara & Aktiviti
                                </h2>
                                <div class="space-y-4">
                                    <p class="text-gray-600">
                                        Sistem pengurusan acara yang
                                        komprehensif dengan ciri-ciri:
                                    </p>
                                    <ul class="space-y-3">
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Pengurusan acara dan aktiviti
                                                keagamaan</span
                                            >
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Pendaftaran dan pengurusan
                                                peserta</span
                                            >
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg
                                                class="h-5 w-5 text-emerald-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <span
                                                >Sistem notifikasi dan
                                                peringatan</span
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>

            <footer class="border-t border-gray-200 py-12 text-center">
                <p class="text-sm text-gray-600">
                    Urus Masjid - Sistem Pengurusan Masjid & Surau
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    Dibina oleh
                    <a href="https://github.com/nurikhwanidris" target="_blank"
                        >Nur Ikhwan Idris</a
                    >
                </p>
            </footer>
        </div>
    </div>
</template>

<style>
.scroll-mt-16 {
    scroll-margin-top: 4rem;
}
</style>

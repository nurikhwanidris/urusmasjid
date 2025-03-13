<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    event: Object,
    mosque: Object,
    registration: Object,
    joinedAsKariah: Boolean,
});
</script>

<template>
    <Head :title="`Pendaftaran Berjaya - ${event.title}`" />

    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-emerald-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Pendaftaran Berjaya!
                            </h1>
                            <p class="mt-2 text-gray-600">
                                Anda telah berjaya mendaftar untuk acara
                                {{ event.title }}.
                            </p>
                        </div>

                        <div class="mb-6 rounded-lg bg-emerald-50 p-4">
                            <h2
                                class="mb-2 text-lg font-semibold text-emerald-800"
                            >
                                Maklumat Pendaftaran
                            </h2>
                            <div class="space-y-2 text-emerald-700">
                                <p>
                                    <span class="font-semibold"
                                        >Nombor Pendaftaran:</span
                                    >
                                    {{ registration.registration_number }}
                                </p>
                                <p>
                                    <span class="font-semibold">Nama:</span>
                                    {{ registration.name }}
                                </p>
                                <p v-if="registration.email">
                                    <span class="font-semibold">Emel:</span>
                                    {{ registration.email }}
                                </p>
                                <p>
                                    <span class="font-semibold">Telefon:</span>
                                    {{ registration.phone }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-6 rounded-lg bg-blue-50 p-4">
                            <h2
                                class="mb-2 text-lg font-semibold text-blue-800"
                            >
                                Maklumat Acara
                            </h2>
                            <div class="space-y-2 text-blue-700">
                                <p>
                                    <span class="font-semibold">Acara:</span>
                                    {{ event.title }}
                                </p>
                                <p>
                                    <span class="font-semibold">Masjid:</span>
                                    {{ mosque.name }}
                                </p>
                                <p>
                                    <span class="font-semibold">Tarikh:</span>
                                    {{
                                        new Date(
                                            event.start_date,
                                        ).toLocaleDateString('ms-MY')
                                    }}
                                    <span
                                        v-if="
                                            event.end_date &&
                                            event.end_date !== event.start_date
                                        "
                                    >
                                        -
                                        {{
                                            new Date(
                                                event.end_date,
                                            ).toLocaleDateString('ms-MY')
                                        }}
                                    </span>
                                </p>
                                <p>
                                    <span class="font-semibold">Masa:</span>
                                    {{ event.start_time }}
                                    <span v-if="event.end_time">
                                        - {{ event.end_time }}
                                    </span>
                                </p>
                                <p>
                                    <span class="font-semibold">Lokasi:</span>
                                    {{ event.location }}
                                </p>
                                <p v-if="event.address">
                                    <span class="font-semibold">Alamat:</span>
                                    {{ event.address }}
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="joinedAsKariah"
                            class="mb-6 rounded-lg bg-yellow-50 p-4"
                        >
                            <h2
                                class="mb-2 text-lg font-semibold text-yellow-800"
                            >
                                Pendaftaran Ahli Kariah
                            </h2>
                            <p class="text-yellow-700">
                                Anda telah mendaftar sebagai ahli kariah
                                {{ mosque.name }}. Permohonan anda akan disemak
                                oleh pihak pengurusan masjid.
                            </p>
                        </div>

                        <div class="mt-8 text-center">
                            <p class="text-sm text-gray-600">
                                Sila simpan maklumat ini untuk rujukan anda.
                                Anda juga boleh menunjukkan nombor pendaftaran
                                ini semasa kehadiran.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

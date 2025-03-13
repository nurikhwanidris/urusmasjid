<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    event: Object,
    mosque: Object,
    message: String,
});
</script>

<template>
    <Head :title="`Pendaftaran Ditutup - ${event.title}`" />

    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-red-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Pendaftaran Ditutup
                            </h1>
                            <p class="mt-2 text-gray-600">
                                {{
                                    message ||
                                    'Pendaftaran untuk acara ini telah ditutup.'
                                }}
                            </p>
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

                        <div class="mt-8 text-center">
                            <p class="text-sm text-gray-600">
                                Untuk maklumat lanjut, sila hubungi pihak
                                masjid.
                            </p>
                            <p
                                v-if="event.contact_person"
                                class="mt-2 text-sm text-gray-600"
                            >
                                <span class="font-semibold"
                                    >Pegawai Perhubungan:</span
                                >
                                {{ event.contact_person }}
                                <span v-if="event.contact_phone">
                                    ({{ event.contact_phone }})
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

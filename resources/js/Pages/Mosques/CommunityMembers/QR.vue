<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    mosque: {
        type: Object,
        required: true,
    },
    qrCode: {
        type: String,
        required: true,
    },
});

const printQR = () => {
    window.location.href = route('masjid.kariah.qr', [
        props.mosque.id,
        { pdf: true },
    ]);
};
</script>

<template>
    <Head title="QR Code Kariah" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-2xl">
                <!-- QR Code Card - Printable Area -->
                <div
                    id="printable-area"
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <!-- Card Header -->
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="text-center">
                            <h1 class="text-2xl font-bold text-emerald-600">
                                {{ mosque.name }}
                            </h1>
                            <p class="mt-2 text-gray-600">
                                Pendaftaran Ahli Kariah
                            </p>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="bg-white p-6">
                        <div
                            class="flex flex-col items-center justify-center space-y-6"
                        >
                            <!-- QR Code -->
                            <div
                                class="rounded-lg border-4 border-emerald-500 p-4"
                            >
                                <img
                                    :src="`data:image/svg+xml;base64,${qrCode}`"
                                    class="h-64 w-64"
                                    alt="QR Code Pendaftaran"
                                />
                            </div>

                            <!-- Instructions -->
                            <div class="text-center">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Cara Pendaftaran:
                                </h3>
                                <ol class="mt-2 space-y-2 text-gray-600">
                                    <li>
                                        1. Imbas kod QR menggunakan telefon
                                        pintar anda
                                    </li>
                                    <li>2. Isi maklumat yang diperlukan</li>
                                    <li>3. Hantar borang pendaftaran</li>
                                </ol>
                            </div>

                            <!-- Mosque Details -->
                            <div
                                class="w-full rounded-lg bg-gray-50 p-4 text-center"
                            >
                                <p class="font-medium text-gray-700">
                                    {{ mosque.street_address }}
                                </p>
                                <p
                                    v-if="mosque.address_line_2"
                                    class="font-medium text-gray-700"
                                >
                                    {{ mosque.address_line_2 }}
                                </p>
                                <p class="font-medium text-gray-700">
                                    {{ mosque.postal_code }} {{ mosque.city }}
                                </p>
                                <p class="font-medium text-gray-700">
                                    {{ mosque.state }}
                                </p>
                                <p class="mt-1 text-gray-600">
                                    {{ mosque.contact_number }}
                                </p>
                                <p class="text-gray-600">{{ mosque.email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="border-t border-gray-200 bg-gray-50 p-4 text-center"
                    >
                        <p class="text-sm text-gray-600">
                            Sistem Pengurusan Masjid & Surau
                        </p>
                    </div>
                </div>

                <!-- Print Button - Hidden when printing -->
                <div class="mt-6 flex justify-center print:hidden">
                    <PrimaryButton @click="printQR" class="w-full sm:w-auto">
                        Cetak QR Code
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@media print {
    @page {
        size: A4;
        margin: 0;
    }

    body {
        margin: 1.6cm;
    }

    #printable-area {
        break-inside: avoid;
    }
}
</style>

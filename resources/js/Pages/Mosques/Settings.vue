<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { zones } from '@/Utils/zones';

const props = defineProps({
    mosque: Object,
});

// Helper function to get zone details from zone code
const getZoneDetails = (zoneCode) => {
    const zone = zones.find((z) => z.jakimCode === zoneCode);
    if (zone) {
        return `${zone.jakimCode} - ${zone.negeri} (${zone.daerah})`;
    }
    return zoneCode;
};

const address = computed(() => {
    return `${props.mosque.street_address}, ${props.mosque.address_line_2 ?? ''}, ${props.mosque.city}, ${props.mosque.district}, ${props.mosque.state}, ${props.mosque.postal_code}`;
});
</script>

<template>
    <Head :title="`Tetapan - ${mosque.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link :href="route('masjid.show', mosque.id)">
                        <SecondaryButton>Kembali</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Basic Information -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Maklumat Asas
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Maklumat asas
                                {{
                                    mosque.type === 'masjid'
                                        ? 'masjid'
                                        : 'surau'
                                }}
                                anda
                            </p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-900">Nama</h4>
                                <p class="text-gray-600">{{ mosque.name }}</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Jenis</h4>
                                <p class="text-gray-600">
                                    {{
                                        mosque.type === 'masjid'
                                            ? 'Masjid'
                                            : 'Surau'
                                    }}
                                </p>
                            </div>
                            <div v-if="mosque.registration_number">
                                <h4 class="font-medium text-gray-900">
                                    No. Pendaftaran
                                </h4>
                                <p class="text-gray-600">
                                    {{ mosque.registration_number }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <Link
                                :href="route('masjid.edit', mosque.id)"
                                class="block w-full sm:w-auto"
                            >
                                <PrimaryButton class="w-full sm:w-auto"
                                    >Kemaskini Maklumat Asas</PrimaryButton
                                >
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- AJK Information -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Maklumat AJK
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Senarai Ahli Jawatankuasa
                                {{
                                    mosque.type === 'masjid'
                                        ? 'Masjid'
                                        : 'Surau'
                                }}
                            </p>
                        </div>
                        <div class="space-y-4">
                            <div
                                v-if="
                                    mosque.committees &&
                                    mosque.committees.length > 0
                                "
                            >
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
                                                    Nama
                                                </th>
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
                                                    No. Telefon
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="divide-y divide-gray-200 bg-white"
                                        >
                                            <tr
                                                v-for="committee in mosque.committees"
                                                :key="committee.id"
                                            >
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                                >
                                                    {{ committee.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                                >
                                                    {{ committee.role }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                                >
                                                    {{ committee.phone_number }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div v-else class="py-4 text-center text-gray-500">
                                Tiada maklumat AJK
                            </div>
                        </div>
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <Link
                                :href="
                                    route(
                                        'masjid.jawatankuasa.index',
                                        mosque.id,
                                    )
                                "
                                class="block w-full sm:w-auto"
                            >
                                <PrimaryButton class="w-full sm:w-auto">
                                    Urus AJK
                                </PrimaryButton>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Lokasi
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Maklumat lokasi dan alamat
                            </p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-900">
                                    Alamat Penuh
                                </h4>
                                <p class="whitespace-pre-line text-gray-600">
                                    {{ address }}
                                </p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">
                                    Zon Solat
                                </h4>
                                <p class="text-gray-600">
                                    {{ getZoneDetails(mosque.zone_code) }}
                                </p>
                            </div>
                            <div v-if="mosque.latitude && mosque.longitude">
                                <h4 class="font-medium text-gray-900">
                                    Koordinat GPS
                                </h4>
                                <p class="text-gray-600">
                                    {{ mosque.latitude }},
                                    {{ mosque.longitude }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <Link
                                :href="route('masjid.edit', mosque.id)"
                                class="block w-full sm:w-auto"
                            >
                                <PrimaryButton class="w-full sm:w-auto"
                                    >Kemaskini Lokasi</PrimaryButton
                                >
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Maklumat Hubungan
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Maklumat hubungan untuk dihubungi
                            </p>
                        </div>
                        <div class="space-y-4">
                            <div v-if="mosque.phone">
                                <h4 class="font-medium text-gray-900">
                                    No. Telefon
                                </h4>
                                <p class="text-gray-600">{{ mosque.phone }}</p>
                            </div>
                            <div v-if="mosque.email">
                                <h4 class="font-medium text-gray-900">Emel</h4>
                                <p class="text-gray-600">{{ mosque.email }}</p>
                            </div>
                            <div v-if="mosque.website">
                                <h4 class="font-medium text-gray-900">
                                    Laman Web
                                </h4>
                                <p class="text-gray-600">
                                    <a
                                        :href="mosque.website"
                                        target="_blank"
                                        class="text-emerald-600 hover:text-emerald-500"
                                    >
                                        {{ mosque.website }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <Link
                                :href="route('masjid.edit', mosque.id)"
                                class="block w-full sm:w-auto"
                            >
                                <PrimaryButton class="w-full sm:w-auto"
                                    >Kemaskini Maklumat Hubungan</PrimaryButton
                                >
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Media Sosial
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Pautan media sosial rasmi
                            </p>
                        </div>
                        <div class="space-y-4">
                            <div v-if="mosque.facebook_url">
                                <h4 class="font-medium text-gray-900">
                                    Facebook
                                </h4>
                                <p class="text-gray-600">
                                    <a
                                        :href="mosque.facebook_url"
                                        target="_blank"
                                        class="text-emerald-600 hover:text-emerald-500"
                                    >
                                        {{ mosque.facebook_url }}
                                    </a>
                                </p>
                            </div>
                            <div v-if="mosque.twitter_url">
                                <h4 class="font-medium text-gray-900">
                                    Twitter
                                </h4>
                                <p class="text-gray-600">
                                    <a
                                        :href="mosque.twitter_url"
                                        target="_blank"
                                        class="text-emerald-600 hover:text-emerald-500"
                                    >
                                        {{ mosque.twitter_url }}
                                    </a>
                                </p>
                            </div>
                            <div v-if="mosque.instagram_url">
                                <h4 class="font-medium text-gray-900">
                                    Instagram
                                </h4>
                                <p class="text-gray-600">
                                    <a
                                        :href="mosque.instagram_url"
                                        target="_blank"
                                        class="text-emerald-600 hover:text-emerald-500"
                                    >
                                        {{ mosque.instagram_url }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <Link
                                :href="route('masjid.edit', mosque.id)"
                                class="block w-full sm:w-auto"
                            >
                                <PrimaryButton class="w-full sm:w-auto"
                                    >Kemaskini Media Sosial</PrimaryButton
                                >
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

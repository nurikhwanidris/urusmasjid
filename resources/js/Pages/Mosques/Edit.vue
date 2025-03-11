<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Textarea from '@/Components/Textarea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { zones } from '@/Utils/zones';
import { states } from '@/Utils/states';

const props = defineProps({
    mosque: Object,
});

// Form data
const form = useForm({
    // Basic Information
    name: props.mosque.name,
    type: props.mosque.type,
    registration_number: props.mosque.registration_number || '',

    // Location Details
    street_address: props.mosque.street_address,
    address_line_2: props.mosque.address_line_2 || '',
    city: props.mosque.city || '',
    district: props.mosque.district || '',
    state: props.mosque.state || '',
    postal_code: props.mosque.postal_code || '',
    location: props.mosque.location,
    latitude: props.mosque.latitude || '',
    longitude: props.mosque.longitude || '',
    jakim_zone: props.mosque.jakim_zone,

    // Contact Information
    contact_number: props.mosque.contact_number || '',
    email: props.mosque.email || '',
    website: props.mosque.website || '',
});

// Mosque types
const mosqueTypes = [
    { value: 'masjid', label: 'Masjid' },
    { value: 'surau', label: 'Surau' },
];

// Convert zones array to options format for SelectInput
const jakimZones = zones.map((zone) => ({
    value: zone.jakimCode,
    label: `${zone.jakimCode} - ${zone.negeri} (${zone.daerah})`,
}));

// States for dropdown
const stateOptions = states;

// Computed properties to check for errors in each section
const hasBasicInfoErrors = computed(() => {
    return (
        form.errors.name || form.errors.type || form.errors.registration_number
    );
});

const hasLocationErrors = computed(() => {
    return (
        form.errors.street_address ||
        form.errors.address_line_2 ||
        form.errors.city ||
        form.errors.district ||
        form.errors.state ||
        form.errors.postal_code ||
        form.errors.jakim_zone ||
        form.errors.location ||
        form.errors.latitude ||
        form.errors.longitude
    );
});

const hasContactErrors = computed(() => {
    return (
        form.errors.contact_number || form.errors.email || form.errors.website
    );
});

// Form submission
const submit = () => {
    form.put(route('masjid.update', props.mosque.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Kemaskini ${mosque.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Kemaskini Masjid
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Validation errors summary if any -->
                        <div
                            v-if="Object.keys(form.errors).length > 0"
                            class="mb-6 rounded-xl bg-red-50 p-4 shadow-sm"
                        >
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
                                    <h3
                                        class="text-sm font-medium text-red-800"
                                    >
                                        Terdapat
                                        {{ Object.keys(form.errors).length }}
                                        ralat yang perlu diperbaiki
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>
                                            Sila perbaiki ralat berikut sebelum
                                            menyimpan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Basic Information -->
                            <div
                                :class="{
                                    'rounded-xl bg-red-50 p-4':
                                        hasBasicInfoErrors,
                                }"
                            >
                                <h3
                                    class="text-lg font-medium"
                                    :class="
                                        hasBasicInfoErrors
                                            ? 'text-red-800'
                                            : 'text-gray-900'
                                    "
                                >
                                    Maklumat Asas
                                </h3>
                                <div class="mt-4 grid gap-6 sm:grid-cols-2">
                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.name,
                                        }"
                                    >
                                        <InputLabel
                                            for="name"
                                            value="Nama Masjid/Surau"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.name,
                                            }"
                                        />
                                        <TextInput
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.name,
                                            }"
                                            required
                                            autofocus
                                        />
                                        <InputError
                                            :message="form.errors.name"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.type,
                                        }"
                                    >
                                        <InputLabel
                                            for="type"
                                            value="Jenis"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.type,
                                            }"
                                        />
                                        <SelectInput
                                            id="type"
                                            v-model="form.type"
                                            :options="mosqueTypes"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.type,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.type"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.registration_number,
                                        }"
                                    >
                                        <InputLabel
                                            for="registration_number"
                                            value="Nombor Pendaftaran"
                                            :class="{
                                                'text-red-500':
                                                    form.errors
                                                        .registration_number,
                                            }"
                                        />
                                        <TextInput
                                            id="registration_number"
                                            v-model="form.registration_number"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors
                                                        .registration_number,
                                            }"
                                        />
                                        <InputError
                                            :message="
                                                form.errors.registration_number
                                            "
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div
                                class="pt-6"
                                :class="{
                                    'rounded-xl bg-red-50 p-4':
                                        hasLocationErrors,
                                }"
                            >
                                <h3
                                    class="text-lg font-medium"
                                    :class="
                                        hasLocationErrors
                                            ? 'text-red-800'
                                            : 'text-gray-900'
                                    "
                                >
                                    Maklumat Lokasi
                                </h3>
                                <div class="mt-4 grid gap-6 sm:grid-cols-2">
                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.street_address,
                                        }"
                                    >
                                        <InputLabel
                                            for="street_address"
                                            value="Alamat"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.street_address,
                                            }"
                                        />
                                        <Textarea
                                            id="street_address"
                                            v-model="form.street_address"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.street_address,
                                            }"
                                            rows="3"
                                            required
                                        />
                                        <InputError
                                            :message="
                                                form.errors.street_address
                                            "
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.address_line_2,
                                        }"
                                    >
                                        <InputLabel
                                            for="address_line_2"
                                            value="Alamat (Baris Kedua)"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.address_line_2,
                                            }"
                                        />
                                        <Textarea
                                            id="address_line_2"
                                            v-model="form.address_line_2"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.address_line_2,
                                            }"
                                            rows="3"
                                        />
                                        <InputError
                                            :message="
                                                form.errors.address_line_2
                                            "
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.city,
                                        }"
                                    >
                                        <InputLabel
                                            for="city"
                                            value="Bandar"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.city,
                                            }"
                                        />
                                        <TextInput
                                            id="city"
                                            v-model="form.city"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.city,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.city"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.district,
                                        }"
                                    >
                                        <InputLabel
                                            for="district"
                                            value="Daerah"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.district,
                                            }"
                                        />
                                        <TextInput
                                            id="district"
                                            v-model="form.district"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.district,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.district"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.state,
                                        }"
                                    >
                                        <InputLabel
                                            for="state"
                                            value="Negeri"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.state,
                                            }"
                                        />
                                        <SelectInput
                                            id="state"
                                            v-model="form.state"
                                            :options="stateOptions"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.state,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.state"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.postal_code,
                                        }"
                                    >
                                        <InputLabel
                                            for="postal_code"
                                            value="Poskod"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.postal_code,
                                            }"
                                        />
                                        <TextInput
                                            id="postal_code"
                                            v-model="form.postal_code"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.postal_code,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.postal_code"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.jakim_zone,
                                        }"
                                    >
                                        <InputLabel
                                            for="jakim_zone"
                                            value="Zon JAKIM"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.jakim_zone,
                                            }"
                                        />
                                        <SelectInput
                                            id="jakim_zone"
                                            v-model="form.jakim_zone"
                                            :options="jakimZones"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.jakim_zone,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.jakim_zone"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.location,
                                        }"
                                    >
                                        <InputLabel
                                            for="location"
                                            value="Lokasi"
                                            class="required"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.location,
                                            }"
                                        />
                                        <TextInput
                                            id="location"
                                            v-model="form.location"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.location,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.location"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.latitude,
                                        }"
                                    >
                                        <InputLabel
                                            for="latitude"
                                            value="Latitud"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.latitude,
                                            }"
                                        />
                                        <TextInput
                                            id="latitude"
                                            v-model="form.latitude"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.latitude,
                                            }"
                                        />
                                        <InputError
                                            :message="form.errors.latitude"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.longitude,
                                        }"
                                    >
                                        <InputLabel
                                            for="longitude"
                                            value="Longitud"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.longitude,
                                            }"
                                        />
                                        <TextInput
                                            id="longitude"
                                            v-model="form.longitude"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.longitude,
                                            }"
                                        />
                                        <InputError
                                            :message="form.errors.longitude"
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div
                                class="pt-6"
                                :class="{
                                    'rounded-xl bg-red-50 p-4':
                                        hasContactErrors,
                                }"
                            >
                                <h3
                                    class="text-lg font-medium"
                                    :class="
                                        hasContactErrors
                                            ? 'text-red-800'
                                            : 'text-gray-900'
                                    "
                                >
                                    Maklumat Hubungan
                                </h3>
                                <div class="mt-4 grid gap-6 sm:grid-cols-2">
                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.contact_number,
                                        }"
                                    >
                                        <InputLabel
                                            for="contact_number"
                                            value="Nombor Telefon"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.contact_number,
                                            }"
                                        />
                                        <TextInput
                                            id="contact_number"
                                            v-model="form.contact_number"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.contact_number,
                                            }"
                                        />
                                        <InputError
                                            :message="
                                                form.errors.contact_number
                                            "
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.email,
                                        }"
                                    >
                                        <InputLabel
                                            for="email"
                                            value="Emel"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.email,
                                            }"
                                        />
                                        <TextInput
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.email,
                                            }"
                                        />
                                        <InputError
                                            :message="form.errors.email"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.website,
                                        }"
                                    >
                                        <InputLabel
                                            for="website"
                                            value="Laman Web"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.website,
                                            }"
                                        />
                                        <TextInput
                                            id="website"
                                            v-model="form.website"
                                            type="url"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.website,
                                            }"
                                        />
                                        <InputError
                                            :message="form.errors.website"
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6">
                                <SecondaryButton
                                    :href="route('masjid.show', mosque.id)"
                                    class="mr-3"
                                >
                                    Batal
                                </SecondaryButton>
                                <PrimaryButton
                                    :disabled="form.processing"
                                    type="submit"
                                >
                                    <span v-if="form.processing"
                                        >Memproses...</span
                                    >
                                    <span v-else>Simpan</span>
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.required::after {
    content: ' *';
    color: #ef4444;
}
</style>

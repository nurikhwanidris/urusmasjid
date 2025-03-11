<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
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

// Form steps
const steps = [
    {
        id: 1,
        name: 'Maklumat Asas',
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    },
    {
        id: 2,
        name: 'Maklumat Lokasi',
        icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
    },
    {
        id: 3,
        name: 'Maklumat Admin',
        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    },
    {
        id: 4,
        name: 'Semak & Hantar',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    },
];

const currentStep = ref(1);

// Form data
const form = useForm({
    // Basic Information
    name: '',
    type: 'masjid',
    registration_number: '',

    // Location Details
    street_address: '',
    address_line_2: '',
    city: '',
    district: '',
    state: '',
    postal_code: '',
    location: '',
    latitude: '',
    longitude: '',
    jakim_zone: '',

    // Contact Information
    contact_number: '',
    email: '',
    website: '',

    // Admin Information
    ic_number: '',
    phone_number: '',
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

// Computed properties for step validation
const isStep1Valid = computed(() => {
    return form.name && form.type;
});

const isStep2Valid = computed(() => {
    return form.street_address && form.location && form.jakim_zone;
});

const isStep3Valid = computed(() => {
    return true; // Optional fields
});

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
        form.errors.contact_number ||
        form.errors.email ||
        form.errors.website
    );
});

const hasAdminErrors = computed(() => {
    return form.errors.ic_number || form.errors.phone_number;
});

// Method to navigate to the step with errors
const goToStepWithErrors = () => {
    if (hasBasicInfoErrors.value) {
        currentStep.value = 1;
    } else if (hasLocationErrors.value) {
        currentStep.value = 2;
    } else if (hasAdminErrors.value) {
        currentStep.value = 3;
    }
};

// Navigation methods
const nextStep = () => {
    if (currentStep.value < steps.length) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

// Form submission
const submit = () => {
    form.post(route('masjid.store'), {
        onSuccess: () => {
            // Reset form and go back to step 1
            form.reset();
            currentStep.value = 1;
        },
        onError: () => {
            // Navigate to the step with errors
            goToStepWithErrors();
        },
    });
};
</script>

<template>
    <Head title="Register Mosque" />

    <AuthenticatedLayout>
        <div class="bg-gray-50 py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-2xl">
                    <!-- Header with title -->
                    <div
                        class="bg-gradient-to-r from-emerald-600 to-teal-500 px-8 py-6 text-white"
                    >
                        <h2 class="text-2xl font-bold">
                            Pendaftaran Masjid Baru
                        </h2>
                        <p class="mt-1 text-emerald-100">
                            Lengkapkan borang untuk mendaftarkan masjid atau
                            surau baru
                        </p>
                    </div>

                    <div class="p-8">
                        <!-- Modern Steps UI -->
                        <div class="mb-12">
                            <!-- Steps with grid layout -->
                            <div class="grid grid-cols-4 gap-0">
                                <div
                                    v-for="(step, index) in steps"
                                    :key="step.id"
                                    class="relative"
                                >
                                    <!-- Step container with centered content -->
                                    <div class="flex flex-col items-center">
                                        <!-- Step circle -->
                                        <div
                                            class="z-10 flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full transition-all duration-300 ease-in-out"
                                            :class="[
                                                currentStep > step.id
                                                    ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200'
                                                    : currentStep === step.id
                                                      ? 'bg-white text-emerald-500 shadow-lg shadow-emerald-100 ring-2 ring-emerald-500'
                                                      : 'bg-gray-100 text-gray-400',
                                            ]"
                                        >
                                            <svg
                                                v-if="currentStep > step.id"
                                                class="h-7 w-7"
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
                                            <svg
                                                v-else
                                                class="h-7 w-7"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    :d="step.icon"
                                                />
                                            </svg>
                                        </div>

                                        <!-- Step label (desktop) -->
                                        <div
                                            class="mt-3 hidden text-center sm:block"
                                        >
                                            <p
                                                class="text-sm font-medium transition-colors duration-200"
                                                :class="[
                                                    currentStep >= step.id
                                                        ? 'text-emerald-600'
                                                        : 'text-gray-500',
                                                ]"
                                            >
                                                Langkah {{ step.id }}
                                            </p>
                                            <p
                                                class="text-base font-semibold transition-colors duration-200"
                                                :class="[
                                                    currentStep >= step.id
                                                        ? 'text-gray-900'
                                                        : 'text-gray-500',
                                                ]"
                                            >
                                                {{ step.name }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Connector line between steps -->
                                    <div
                                        v-if="index < steps.length - 1"
                                        class="absolute left-1/2 top-7 z-0 hidden h-0.5 w-full bg-gray-200 sm:block"
                                        :class="{
                                            'bg-emerald-500':
                                                currentStep > step.id,
                                        }"
                                    ></div>
                                </div>
                            </div>

                            <!-- Mobile step labels -->
                            <div class="mt-4 flex justify-center sm:hidden">
                                <p class="font-medium text-emerald-600">
                                    Langkah {{ currentStep }}:
                                    {{ steps[currentStep - 1].name }}
                                </p>
                            </div>

                            <!-- Progress bar -->
                            <div
                                class="mt-8 h-1.5 w-full overflow-hidden rounded-full bg-gray-200"
                            >
                                <div
                                    class="h-full rounded-full bg-emerald-500 transition-all duration-500 ease-in-out"
                                    :style="{
                                        width: `${((currentStep - 1) / (steps.length - 1)) * 100}%`,
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- Step 1: Basic Information -->
                        <div v-if="currentStep === 1" class="space-y-8">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-900">
                                    Maklumat Asas
                                </h3>
                                <p class="mt-2 text-gray-600">
                                    Masukkan maklumat asas tentang masjid.
                                </p>
                            </div>

                            <!-- Validation errors summary if any -->
                            <div
                                v-if="hasBasicInfoErrors"
                                class="rounded-xl bg-red-50 p-4 shadow-sm"
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
                                            Terdapat ralat yang perlu diperbaiki
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>
                                                Sila perbaiki ralat berikut
                                                sebelum meneruskan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="rounded-xl bg-gray-50 p-6 shadow-sm"
                                :class="{
                                    'ring-2 ring-red-500': hasBasicInfoErrors,
                                }"
                            >
                                <div class="space-y-6">
                                    <div
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.name,
                                        }"
                                    >
                                        <InputLabel
                                            for="name"
                                            value="Nama Masjid"
                                            class="required text-base"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.name,
                                            }"
                                        />
                                        <TextInput
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.name,
                                            }"
                                            required
                                            autofocus
                                            placeholder="Contoh: Masjid Al-Hidayah"
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
                                            value="Jenis Masjid"
                                            class="required text-base"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.type,
                                            }"
                                        />
                                        <SelectInput
                                            id="type"
                                            v-model="form.type"
                                            :options="mosqueTypes"
                                            class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
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
                                            value="Nombor Pendaftaran (jika berdaftar)"
                                            class="text-base"
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
                                            class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors
                                                        .registration_number,
                                            }"
                                            placeholder="Contoh: MSJ-12345"
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

                            <div class="flex justify-end">
                                <PrimaryButton
                                    :disabled="!isStep1Valid"
                                    @click="nextStep"
                                    class="rounded-lg bg-emerald-600 px-8 py-3 text-base font-medium hover:bg-emerald-700 focus:ring-emerald-500"
                                >
                                    Seterusnya
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Step 2: Location Details -->
                        <div v-if="currentStep === 2" class="space-y-8">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-900">
                                    Maklumat Lokasi
                                </h3>
                                <p class="mt-2 text-gray-600">
                                    Masukkan maklumat lokasi masjid.
                                </p>
                            </div>

                            <!-- Validation errors summary if any -->
                            <div
                                v-if="hasLocationErrors"
                                class="rounded-xl bg-red-50 p-4 shadow-sm"
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
                                            Terdapat ralat yang perlu diperbaiki
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>
                                                Sila perbaiki ralat berikut
                                                sebelum meneruskan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="rounded-xl bg-gray-50 p-6 shadow-sm"
                                :class="{
                                    'ring-2 ring-red-500': hasLocationErrors,
                                }"
                            >
                                <div class="space-y-6">
                                    <div class="grid gap-6 md:grid-cols-2">
                                        <div
                                            :class="{
                                                'rounded-lg bg-red-50 p-4':
                                                    form.errors.street_address,
                                            }"
                                        >
                                            <InputLabel
                                                for="street_address"
                                                value="Alamat"
                                                class="required text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors
                                                            .street_address,
                                                }"
                                            />
                                            <Textarea
                                                id="street_address"
                                                v-model="form.street_address"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors
                                                            .street_address,
                                                }"
                                                rows="3"
                                                required
                                                placeholder="Alamat lengkap masjid"
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
                                                class="text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors
                                                            .address_line_2,
                                                }"
                                            />
                                            <Textarea
                                                id="address_line_2"
                                                v-model="form.address_line_2"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors
                                                            .address_line_2,
                                                }"
                                                rows="3"
                                                placeholder="Alamat tambahan (jika ada)"
                                            />
                                            <InputError
                                                :message="
                                                    form.errors.address_line_2
                                                "
                                                class="mt-2"
                                            />
                                        </div>
                                    </div>

                                    <div class="grid gap-6 md:grid-cols-2">
                                        <div
                                            :class="{
                                                'rounded-lg bg-red-50 p-4':
                                                    form.errors.city,
                                            }"
                                        >
                                            <InputLabel
                                                for="city"
                                                value="Bandar"
                                                class="required text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors.city,
                                                }"
                                            />
                                            <TextInput
                                                id="city"
                                                v-model="form.city"
                                                type="text"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors.city,
                                                }"
                                                required
                                                placeholder="Contoh: Kuala Lumpur"
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
                                                class="required text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors.district,
                                                }"
                                            />
                                            <TextInput
                                                id="district"
                                                v-model="form.district"
                                                type="text"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors.district,
                                                }"
                                                required
                                                placeholder="Contoh: Petaling"
                                            />
                                            <InputError
                                                :message="form.errors.district"
                                                class="mt-2"
                                            />
                                        </div>
                                    </div>

                                    <div class="grid gap-6 md:grid-cols-2">
                                        <div
                                            :class="{
                                                'rounded-lg bg-red-50 p-4':
                                                    form.errors.state,
                                            }"
                                        >
                                            <InputLabel
                                                for="state"
                                                value="Negeri"
                                                class="required text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors.state,
                                                }"
                                            />
                                            <SelectInput
                                                id="state"
                                                v-model="form.state"
                                                :options="stateOptions"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
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
                                                class="required text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors.postal_code,
                                                }"
                                            />
                                            <TextInput
                                                id="postal_code"
                                                v-model="form.postal_code"
                                                type="text"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors.postal_code,
                                                }"
                                                required
                                                placeholder="Contoh: 50000"
                                            />
                                            <InputError
                                                :message="
                                                    form.errors.postal_code
                                                "
                                                class="mt-2"
                                            />
                                        </div>
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
                                            class="required text-base"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.jakim_zone,
                                            }"
                                        />
                                        <SelectInput
                                            id="jakim_zone"
                                            v-model="form.jakim_zone"
                                            :options="jakimZones"
                                            class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
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
                                            value="Lokasi Google Maps"
                                            class="required text-base"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.location,
                                            }"
                                        />
                                        <TextInput
                                            id="location"
                                            v-model="form.location"
                                            type="text"
                                            class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.location,
                                            }"
                                            placeholder="Paste pautan Google Maps atau koordinat"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.location"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div class="grid gap-6 md:grid-cols-3">
                                        <div
                                            :class="{
                                                'rounded-lg bg-red-50 p-4':
                                                    form.errors.contact_number,
                                            }"
                                        >
                                            <InputLabel
                                                for="contact_number"
                                                value="Nombor Telefon"
                                                class="text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors
                                                            .contact_number,
                                                }"
                                            />
                                            <TextInput
                                                id="contact_number"
                                                v-model="form.contact_number"
                                                type="text"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors
                                                            .contact_number,
                                                }"
                                                placeholder="Contoh: 03-12345678"
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
                                                class="text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors.email,
                                                }"
                                            />
                                            <TextInput
                                                id="email"
                                                v-model="form.email"
                                                type="email"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors.email,
                                                }"
                                                placeholder="Contoh: info@masjid.com"
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
                                                class="text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors.website,
                                                }"
                                            />
                                            <TextInput
                                                id="website"
                                                v-model="form.website"
                                                type="url"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors.website,
                                                }"
                                                placeholder="Contoh: https://masjid.com"
                                            />
                                            <InputError
                                                :message="form.errors.website"
                                                class="mt-2"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <SecondaryButton
                                    @click="prevStep"
                                    class="rounded-lg border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 focus:ring-gray-500"
                                >
                                    Sebelum
                                </SecondaryButton>
                                <PrimaryButton
                                    :disabled="!isStep2Valid"
                                    @click="nextStep"
                                    class="rounded-lg bg-emerald-600 px-8 py-3 text-base font-medium hover:bg-emerald-700 focus:ring-emerald-500"
                                >
                                    Seterusnya
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Step 3: Admin Information -->
                        <div v-if="currentStep === 3" class="space-y-8">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-900">
                                    Maklumat Admin
                                </h3>
                                <p class="mt-2 text-gray-600">
                                    Masukkan maklumat anda sebagai admin utama
                                    masjid ini.
                                </p>
                            </div>

                            <!-- Validation errors summary if any -->
                            <div
                                v-if="hasAdminErrors"
                                class="rounded-xl bg-red-50 p-4 shadow-sm"
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
                                            Terdapat ralat yang perlu diperbaiki
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>
                                                Sila perbaiki ralat berikut
                                                sebelum meneruskan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="rounded-xl bg-gray-50 p-6 shadow-sm"
                                :class="{
                                    'ring-2 ring-red-500': hasAdminErrors,
                                }"
                            >
                                <div class="space-y-6">
                                    <div class="grid gap-6 md:grid-cols-2">
                                        <div
                                            :class="{
                                                'rounded-lg bg-red-50 p-4':
                                                    form.errors.ic_number,
                                            }"
                                        >
                                            <InputLabel
                                                for="ic_number"
                                                value="Nombor IC (Tidak Wajib)"
                                                class="text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors.ic_number,
                                                }"
                                            />
                                            <TextInput
                                                id="ic_number"
                                                v-model="form.ic_number"
                                                type="text"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors.ic_number,
                                                }"
                                                placeholder="Contoh: 880101-01-1234"
                                            />
                                            <InputError
                                                :message="form.errors.ic_number"
                                                class="mt-2"
                                            />
                                        </div>

                                        <div
                                            :class="{
                                                'rounded-lg bg-red-50 p-4':
                                                    form.errors.phone_number,
                                            }"
                                        >
                                            <InputLabel
                                                for="phone_number"
                                                value="Nombor Telefon (Tidak Wajib)"
                                                class="text-base"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors
                                                            .phone_number,
                                                }"
                                            />
                                            <TextInput
                                                id="phone_number"
                                                v-model="form.phone_number"
                                                type="text"
                                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors
                                                            .phone_number,
                                                }"
                                                placeholder="Contoh: 012-3456789"
                                            />
                                            <InputError
                                                :message="
                                                    form.errors.phone_number
                                                "
                                                class="mt-2"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <SecondaryButton
                                    @click="prevStep"
                                    class="rounded-lg border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 focus:ring-gray-500"
                                >
                                    Sebelum
                                </SecondaryButton>
                                <PrimaryButton
                                    :disabled="!isStep3Valid"
                                    @click="nextStep"
                                    class="rounded-lg bg-emerald-600 px-8 py-3 text-base font-medium hover:bg-emerald-700 focus:ring-emerald-500"
                                >
                                    Seterusnya
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Step 4: Review & Submit -->
                        <div v-if="currentStep === 4" class="space-y-8">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-900">
                                    Semak & Hantar
                                </h3>
                                <p class="mt-2 text-gray-600">
                                    Sila semak maklumat sebelum hantar.
                                </p>
                            </div>

                            <!-- Validation errors summary if any -->
                            <div
                                v-if="Object.keys(form.errors).length > 0"
                                class="rounded-xl bg-red-50 p-4 shadow-sm"
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
                                            {{
                                                Object.keys(form.errors).length
                                            }}
                                            ralat yang perlu diperbaiki
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>
                                                Sila klik pada bahagian yang
                                                mempunyai ralat untuk
                                                memperbaikinya.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Basic Information -->
                                <div
                                    class="overflow-hidden rounded-xl bg-white shadow"
                                    :class="{
                                        'ring-2 ring-red-500':
                                            hasBasicInfoErrors,
                                    }"
                                >
                                    <div
                                        class="border-b border-gray-200 px-6 py-4"
                                        :class="
                                            hasBasicInfoErrors
                                                ? 'bg-red-50'
                                                : 'bg-emerald-50'
                                        "
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <h4
                                                class="text-lg font-semibold"
                                                :class="
                                                    hasBasicInfoErrors
                                                        ? 'text-red-800'
                                                        : 'text-emerald-800'
                                                "
                                            >
                                                Maklumat Asas
                                            </h4>
                                            <button
                                                v-if="hasBasicInfoErrors"
                                                type="button"
                                                @click="currentStep = 1"
                                                class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-1.5 text-sm font-medium text-red-800 hover:bg-red-200"
                                            >
                                                <svg
                                                    class="mr-1.5 h-4 w-4"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                                    />
                                                </svg>
                                                Perbaiki
                                            </button>
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-200 px-6">
                                        <div
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50': form.errors.name,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.name
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Nama Masjid
                                                <InputError
                                                    v-if="form.errors.name"
                                                    :message="form.errors.name"
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.name
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.name || '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50': form.errors.type,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.type
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Jenis Masjid
                                                <InputError
                                                    v-if="form.errors.type"
                                                    :message="form.errors.type"
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.type
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.type === 'masjid'
                                                        ? 'Masjid'
                                                        : 'Surau'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="
                                                form.registration_number ||
                                                form.errors.registration_number
                                            "
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors
                                                        .registration_number,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors
                                                        .registration_number
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Nombor Pendaftaran
                                                <InputError
                                                    v-if="
                                                        form.errors
                                                            .registration_number
                                                    "
                                                    :message="
                                                        form.errors
                                                            .registration_number
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors
                                                        .registration_number
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.registration_number ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Information -->
                                <div
                                    class="overflow-hidden rounded-xl bg-white shadow"
                                    :class="{
                                        'ring-2 ring-red-500':
                                            hasLocationErrors,
                                    }"
                                >
                                    <div
                                        class="border-b border-gray-200 px-6 py-4"
                                        :class="
                                            hasLocationErrors
                                                ? 'bg-red-50'
                                                : 'bg-emerald-50'
                                        "
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <h4
                                                class="text-lg font-semibold"
                                                :class="
                                                    hasLocationErrors
                                                        ? 'text-red-800'
                                                        : 'text-emerald-800'
                                                "
                                            >
                                                Maklumat Lokasi
                                            </h4>
                                            <button
                                                v-if="hasLocationErrors"
                                                type="button"
                                                @click="currentStep = 2"
                                                class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-1.5 text-sm font-medium text-red-800 hover:bg-red-200"
                                            >
                                                <svg
                                                    class="mr-1.5 h-4 w-4"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                                    />
                                                </svg>
                                                Perbaiki
                                            </button>
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-200 px-6">
                                        <div
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors.street_address,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.street_address
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Alamat
                                                <InputError
                                                    v-if="
                                                        form.errors
                                                            .street_address
                                                    "
                                                    :message="
                                                        form.errors
                                                            .street_address
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.street_address
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.street_address ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="
                                                form.address_line_2 ||
                                                form.errors.address_line_2
                                            "
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors.address_line_2,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.address_line_2
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Alamat (Baris Kedua)
                                                <InputError
                                                    v-if="
                                                        form.errors
                                                            .address_line_2
                                                    "
                                                    :message="
                                                        form.errors
                                                            .address_line_2
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.address_line_2
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.address_line_2 ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            class="grid grid-cols-2 gap-4 py-4"
                                        >
                                            <div
                                                :class="{
                                                    'rounded bg-red-50 p-2':
                                                        form.errors.city,
                                                }"
                                            >
                                                <dt
                                                    class="text-sm font-medium"
                                                    :class="
                                                        form.errors.city
                                                            ? 'text-red-500'
                                                            : 'text-gray-500'
                                                    "
                                                >
                                                    Bandar
                                                    <InputError
                                                        v-if="form.errors.city"
                                                        :message="
                                                            form.errors.city
                                                        "
                                                        class="mt-1"
                                                    />
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm font-semibold"
                                                    :class="
                                                        form.errors.city
                                                            ? 'text-red-700'
                                                            : 'text-gray-900'
                                                    "
                                                >
                                                    {{
                                                        form.city ||
                                                        '(Tidak diisi)'
                                                    }}
                                                </dd>
                                            </div>
                                            <div
                                                :class="{
                                                    'rounded bg-red-50 p-2':
                                                        form.errors.district,
                                                }"
                                            >
                                                <dt
                                                    class="text-sm font-medium"
                                                    :class="
                                                        form.errors.district
                                                            ? 'text-red-500'
                                                            : 'text-gray-500'
                                                    "
                                                >
                                                    Daerah
                                                    <InputError
                                                        v-if="
                                                            form.errors.district
                                                        "
                                                        :message="
                                                            form.errors.district
                                                        "
                                                        class="mt-1"
                                                    />
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm font-semibold"
                                                    :class="
                                                        form.errors.district
                                                            ? 'text-red-700'
                                                            : 'text-gray-900'
                                                    "
                                                >
                                                    {{
                                                        form.district ||
                                                        '(Tidak diisi)'
                                                    }}
                                                </dd>
                                            </div>
                                        </div>
                                        <div
                                            class="grid grid-cols-2 gap-4 py-4"
                                        >
                                            <div
                                                :class="{
                                                    'rounded bg-red-50 p-2':
                                                        form.errors.state,
                                                }"
                                            >
                                                <dt
                                                    class="text-sm font-medium"
                                                    :class="
                                                        form.errors.state
                                                            ? 'text-red-500'
                                                            : 'text-gray-500'
                                                    "
                                                >
                                                    Negeri
                                                    <InputError
                                                        v-if="form.errors.state"
                                                        :message="
                                                            form.errors.state
                                                        "
                                                        class="mt-1"
                                                    />
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm font-semibold"
                                                    :class="
                                                        form.errors.state
                                                            ? 'text-red-700'
                                                            : 'text-gray-900'
                                                    "
                                                >
                                                    {{
                                                        form.state ||
                                                        '(Tidak diisi)'
                                                    }}
                                                </dd>
                                            </div>
                                            <div
                                                :class="{
                                                    'rounded bg-red-50 p-2':
                                                        form.errors.postal_code,
                                                }"
                                            >
                                                <dt
                                                    class="text-sm font-medium"
                                                    :class="
                                                        form.errors.postal_code
                                                            ? 'text-red-500'
                                                            : 'text-gray-500'
                                                    "
                                                >
                                                    Poskod
                                                    <InputError
                                                        v-if="
                                                            form.errors
                                                                .postal_code
                                                        "
                                                        :message="
                                                            form.errors
                                                                .postal_code
                                                        "
                                                        class="mt-1"
                                                    />
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm font-semibold"
                                                    :class="
                                                        form.errors.postal_code
                                                            ? 'text-red-700'
                                                            : 'text-gray-900'
                                                    "
                                                >
                                                    {{
                                                        form.postal_code ||
                                                        '(Tidak diisi)'
                                                    }}
                                                </dd>
                                            </div>
                                        </div>
                                        <div
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors.jakim_zone,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.jakim_zone
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Zon JAKIM
                                                <InputError
                                                    v-if="
                                                        form.errors.jakim_zone
                                                    "
                                                    :message="
                                                        form.errors.jakim_zone
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.jakim_zone
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.jakim_zone ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors.location,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.location
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Lokasi Google Maps
                                                <InputError
                                                    v-if="form.errors.location"
                                                    :message="
                                                        form.errors.location
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.location
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.location ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="
                                                form.contact_number ||
                                                form.errors.contact_number
                                            "
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors.contact_number,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.contact_number
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Nombor Telefon
                                                <InputError
                                                    v-if="
                                                        form.errors
                                                            .contact_number
                                                    "
                                                    :message="
                                                        form.errors
                                                            .contact_number
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.contact_number
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.contact_number ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="
                                                form.email || form.errors.email
                                            "
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50': form.errors.email,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.email
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Emel
                                                <InputError
                                                    v-if="form.errors.email"
                                                    :message="form.errors.email"
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.email
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.email ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="
                                                form.website ||
                                                form.errors.website
                                            "
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors.website,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.website
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Laman Web
                                                <InputError
                                                    v-if="form.errors.website"
                                                    :message="
                                                        form.errors.website
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.website
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.website ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                    </div>
                                </div>

                                <!-- Admin Information -->
                                <div
                                    class="overflow-hidden rounded-xl bg-white shadow"
                                    :class="{
                                        'ring-2 ring-red-500': hasAdminErrors,
                                    }"
                                >
                                    <div
                                        class="border-b border-gray-200 px-6 py-4"
                                        :class="
                                            hasAdminErrors
                                                ? 'bg-red-50'
                                                : 'bg-emerald-50'
                                        "
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <h4
                                                class="text-lg font-semibold"
                                                :class="
                                                    hasAdminErrors
                                                        ? 'text-red-800'
                                                        : 'text-emerald-800'
                                                "
                                            >
                                                Maklumat Admin
                                            </h4>
                                            <button
                                                v-if="hasAdminErrors"
                                                type="button"
                                                @click="currentStep = 3"
                                                class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-1.5 text-sm font-medium text-red-800 hover:bg-red-200"
                                            >
                                                <svg
                                                    class="mr-1.5 h-4 w-4"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                                    />
                                                </svg>
                                                Perbaiki
                                            </button>
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-200 px-6">
                                        <div
                                            v-if="
                                                form.ic_number ||
                                                form.errors.ic_number
                                            "
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors.ic_number,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.ic_number
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Nombor IC
                                                <InputError
                                                    v-if="form.errors.ic_number"
                                                    :message="
                                                        form.errors.ic_number
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.ic_number
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.ic_number ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="
                                                form.phone_number ||
                                                form.errors.phone_number
                                            "
                                            class="flex items-center justify-between py-4"
                                            :class="{
                                                'bg-red-50':
                                                    form.errors.phone_number,
                                            }"
                                        >
                                            <dt
                                                class="text-sm font-medium"
                                                :class="
                                                    form.errors.phone_number
                                                        ? 'text-red-500'
                                                        : 'text-gray-500'
                                                "
                                            >
                                                Nombor Telefon
                                                <InputError
                                                    v-if="
                                                        form.errors.phone_number
                                                    "
                                                    :message="
                                                        form.errors.phone_number
                                                    "
                                                    class="mt-1"
                                                />
                                            </dt>
                                            <dd
                                                class="text-sm font-semibold"
                                                :class="
                                                    form.errors.phone_number
                                                        ? 'text-red-700'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    form.phone_number ||
                                                    '(Tidak diisi)'
                                                }}
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Card -->
                            <div
                                class="overflow-hidden rounded-xl bg-amber-50 shadow"
                            >
                                <div class="flex items-start gap-4 p-6">
                                    <div class="flex-shrink-0">
                                        <svg
                                            class="h-6 w-6 text-amber-500"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path
                                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"
                                            ></path>
                                            <line
                                                x1="12"
                                                y1="9"
                                                x2="12"
                                                y2="13"
                                            ></line>
                                            <line
                                                x1="12"
                                                y1="17"
                                                x2="12.01"
                                                y2="17"
                                            ></line>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="text-base font-medium text-amber-800"
                                        >
                                            Pemberitahuan Pengesahan
                                        </h3>
                                        <div
                                            class="mt-2 text-sm text-amber-700"
                                        >
                                            <p>
                                                Pendaftaran masjid anda akan
                                                disahkan oleh pegawai kami
                                                sebelum ia disahkan. Proses ini
                                                mungkin mengambil masa sehingga
                                                48 jam.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <SecondaryButton
                                    @click="prevStep"
                                    class="rounded-lg border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 focus:ring-gray-500"
                                >
                                    Sebelum
                                </SecondaryButton>
                                <PrimaryButton
                                    :disabled="form.processing"
                                    @click="submit"
                                    class="rounded-lg bg-emerald-600 px-8 py-3 text-base font-medium hover:bg-emerald-700 focus:ring-emerald-500"
                                >
                                    <span
                                        v-if="form.processing"
                                        class="flex items-center gap-2"
                                    >
                                        <svg
                                            class="h-5 w-5 animate-spin"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        Dalam Proses...
                                    </span>
                                    <span
                                        v-else
                                        class="flex items-center gap-2"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="M20 6L9 17l-5-5"></path>
                                        </svg>
                                        Hantar Pendaftaran
                                    </span>
                                </PrimaryButton>
                            </div>
                        </div>
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

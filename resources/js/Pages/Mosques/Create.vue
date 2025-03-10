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
    { id: 1, name: 'Maklumat Asas' },
    { id: 2, name: 'Maklumat Lokasi' },
    { id: 3, name: 'Maklumat Admin' },
    { id: 4, name: 'Semak & Hantar' },
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
    });
};
</script>

<template>
    <Head title="Register Mosque" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Register New Mosque
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Steps -->
                        <nav aria-label="Progress" class="mb-8">
                            <ol
                                role="list"
                                class="divide-y divide-gray-300 rounded-md border border-gray-300 md:flex md:divide-y-0"
                            >
                                <li
                                    v-for="(step, stepIdx) in steps"
                                    :key="step.id"
                                    class="relative md:flex md:flex-1"
                                >
                                    <div
                                        class="flex items-center px-6 py-4 text-sm font-medium"
                                        :class="[
                                            stepIdx < currentStep - 1
                                                ? 'bg-emerald-50'
                                                : '',
                                            stepIdx === currentStep - 1
                                                ? 'bg-white'
                                                : '',
                                            stepIdx > currentStep - 1
                                                ? 'bg-gray-50'
                                                : '',
                                        ]"
                                    >
                                        <span
                                            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full"
                                            :class="[
                                                stepIdx < currentStep
                                                    ? 'bg-emerald-600 text-white'
                                                    : 'border-2 border-gray-300 bg-white text-gray-500',
                                            ]"
                                        >
                                            <span>{{ step.id }}</span>
                                        </span>
                                        <span
                                            class="ml-4 text-sm font-medium"
                                            >{{ step.name }}</span
                                        >
                                    </div>
                                </li>
                            </ol>
                        </nav>

                        <!-- Step 1: Basic Information -->
                        <div v-if="currentStep === 1" class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Maklumat Asas
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Masukkan maklumat asas tentang masjid.
                                </p>
                            </div>

                            <div>
                                <InputLabel
                                    for="name"
                                    value="Nama Masjid"
                                    class="required"
                                />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError
                                    :message="form.errors.name"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="type"
                                    value="Jenis Masjid"
                                    class="required"
                                />
                                <SelectInput
                                    id="type"
                                    v-model="form.type"
                                    :options="mosqueTypes"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.type"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="registration_number"
                                    value="Nombor Pendaftaran (jika berdaftar)"
                                />
                                <TextInput
                                    id="registration_number"
                                    v-model="form.registration_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.registration_number"
                                    class="mt-2"
                                />
                            </div>

                            <div class="flex justify-end">
                                <PrimaryButton
                                    :disabled="!isStep1Valid"
                                    @click="nextStep"
                                >
                                    Next
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Step 2: Location Details -->
                        <div v-if="currentStep === 2" class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Maklumat Lokasi
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Masukkan maklumat lokasi masjid.
                                </p>
                            </div>

                            <div>
                                <InputLabel
                                    for="street_address"
                                    value="Alamat"
                                    class="required"
                                />
                                <Textarea
                                    id="street_address"
                                    v-model="form.street_address"
                                    class="mt-1 block w-full"
                                    rows="3"
                                    required
                                />
                                <InputError
                                    :message="form.errors.street_address"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="address_line_2"
                                    value="Alamat (Baris Kedua)"
                                />
                                <Textarea
                                    id="address_line_2"
                                    v-model="form.address_line_2"
                                    class="mt-1 block w-full"
                                    rows="3"
                                />
                                <InputError
                                    :message="form.errors.address_line_2"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="city"
                                    value="Bandar"
                                    class="required"
                                />
                                <TextInput
                                    id="city"
                                    v-model="form.city"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.city"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="district"
                                    value="Daerah"
                                    class="required"
                                />
                                <TextInput
                                    id="district"
                                    v-model="form.district"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.district"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="state"
                                    value="Negeri"
                                    class="required"
                                />
                                <SelectInput
                                    id="state"
                                    v-model="form.state"
                                    :options="stateOptions"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.state"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="postal_code"
                                    value="Poskod"
                                    class="required"
                                />
                                <TextInput
                                    id="postal_code"
                                    v-model="form.postal_code"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.postal_code"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="jakim_zone"
                                    value="Zon JAKIM"
                                    class="required"
                                />
                                <SelectInput
                                    id="jakim_zone"
                                    v-model="form.jakim_zone"
                                    :options="jakimZones"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.jakim_zone"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="location"
                                    value="Lokasi Google Maps"
                                    class="required"
                                />
                                <TextInput
                                    id="location"
                                    v-model="form.location"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Paste pautan Google Maps atau koordinat"
                                    required
                                />
                                <InputError
                                    :message="form.errors.location"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="contact_number"
                                    value="Nombor Telefon"
                                />
                                <TextInput
                                    id="contact_number"
                                    v-model="form.contact_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.contact_number"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel for="email" value="Emel" />
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.email"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel for="website" value="Laman Web" />
                                <TextInput
                                    id="website"
                                    v-model="form.website"
                                    type="url"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.website"
                                    class="mt-2"
                                />
                            </div>

                            <div class="flex justify-between">
                                <SecondaryButton @click="prevStep">
                                    Sebelum
                                </SecondaryButton>
                                <PrimaryButton
                                    :disabled="!isStep2Valid"
                                    @click="nextStep"
                                >
                                    Seterusnya
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Step 3: Admin Information -->
                        <div v-if="currentStep === 3" class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Maklumat Admin
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Masukkan maklumat anda sebagai admin utama
                                    administrator of this mosque.
                                </p>
                            </div>

                            <div>
                                <InputLabel
                                    for="ic_number"
                                    value="Nombor IC (Pilihan)"
                                />
                                <TextInput
                                    id="ic_number"
                                    v-model="form.ic_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.ic_number"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="phone_number"
                                    value="Nombor Telefon (Pilihan)"
                                />
                                <TextInput
                                    id="phone_number"
                                    v-model="form.phone_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.phone_number"
                                    class="mt-2"
                                />
                            </div>

                            <div class="flex justify-between">
                                <SecondaryButton @click="prevStep">
                                    Sebelum
                                </SecondaryButton>
                                <PrimaryButton
                                    :disabled="!isStep3Valid"
                                    @click="nextStep"
                                >
                                    Seterusnya
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Step 4: Review & Submit -->
                        <div v-if="currentStep === 4" class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Semak & Hantar
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Sila semak maklumat sebelum hantar.
                                </p>
                            </div>

                            <div class="rounded-md bg-gray-50 p-4">
                                <h4 class="text-md font-medium text-gray-900">
                                    Maklumat Asas
                                </h4>
                                <dl class="mt-2 divide-y divide-gray-200">
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Nama Masjid
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.name }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Jenis Masjid
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{
                                                form.type === 'masjid'
                                                    ? 'Masjid'
                                                    : 'Surau'
                                            }}
                                        </dd>
                                    </div>
                                    <div
                                        v-if="form.registration_number"
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Nombor Pendaftaran
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.registration_number }}
                                        </dd>
                                    </div>
                                </dl>

                                <h4
                                    class="text-md mt-4 font-medium text-gray-900"
                                >
                                    Maklumat Lokasi
                                </h4>
                                <dl class="mt-2 divide-y divide-gray-200">
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Alamat
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.street_address }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Alamat (Baris Kedua)
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.address_line_2 }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Bandar
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.city }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Daerah
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.district }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Negeri
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.state }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Poskod
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.postal_code }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Zon JAKIM
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.jakim_zone }}
                                        </dd>
                                    </div>
                                    <div
                                        v-if="form.contact_number"
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Nombor Telefon
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.contact_number }}
                                        </dd>
                                    </div>
                                    <div
                                        v-if="form.email"
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Emel
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.email }}
                                        </dd>
                                    </div>
                                    <div
                                        v-if="form.website"
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Laman Web
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.website }}
                                        </dd>
                                    </div>
                                </dl>

                                <h4
                                    class="text-md mt-4 font-medium text-gray-900"
                                >
                                    Maklumat Admin
                                </h4>
                                <dl class="mt-2 divide-y divide-gray-200">
                                    <div
                                        v-if="form.ic_number"
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Nombor IC
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.ic_number }}
                                        </dd>
                                    </div>
                                    <div
                                        v-if="form.phone_number"
                                        class="flex justify-between py-2 text-sm"
                                    >
                                        <dt class="font-medium text-gray-500">
                                            Nombor Telefon
                                        </dt>
                                        <dd class="text-gray-900">
                                            {{ form.phone_number }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="rounded-md bg-yellow-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg
                                            class="h-5 w-5 text-yellow-400"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-medium text-yellow-800"
                                        >
                                            Pemberitahuan Pengesahan
                                        </h3>
                                        <div
                                            class="mt-2 text-sm text-yellow-700"
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
                                <SecondaryButton @click="prevStep">
                                    Sebelum
                                </SecondaryButton>
                                <PrimaryButton
                                    :disabled="form.processing"
                                    @click="submit"
                                >
                                    <span v-if="form.processing">
                                        Dalam Proses...
                                    </span>
                                    <span v-else> Hantar Pendaftaran </span>
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

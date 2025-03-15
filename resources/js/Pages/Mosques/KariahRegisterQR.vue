<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    mosque: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: '',
    ic_number: '',
    phone_number: '',
    email: '',
    address: '',
    postcode: '',
    city: '',
    state: '',
    relationship_status: '',
    occupation: '',
    emergency_contact: '',
    emergency_phone: '',
    notes: '',
});

const states = [
    'Johor',
    'Kedah',
    'Kelantan',
    'Melaka',
    'Negeri Sembilan',
    'Pahang',
    'Perak',
    'Perlis',
    'Pulau Pinang',
    'Sabah',
    'Sarawak',
    'Selangor',
    'Terengganu',
    'W.P. Kuala Lumpur',
    'W.P. Labuan',
    'W.P. Putrajaya',
];

const relationshipStatuses = ['Bujang', 'Berkahwin', 'Duda', 'Janda'];

const submit = () => {
    form.post(route('masjid.kariah.register.store', props.mosque.id), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Pendaftaran Kariah" />

    <GuestLayout>
        <div class="py-8">
            <div class="mx-auto max-w-2xl">
                <!-- Registration Form Card -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <!-- Card Header -->
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="text-center">
                            <h1 class="text-2xl font-bold text-emerald-600">
                                {{ mosque.name }}
                            </h1>
                            <p class="mt-2 text-gray-600">
                                Borang Pendaftaran Ahli Kariah
                            </p>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <form
                        @submit.prevent="submit"
                        class="space-y-6 bg-white p-6"
                    >
                        <!-- Personal Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Maklumat Peribadi
                            </h3>

                            <!-- Name -->
                            <div>
                                <InputLabel for="name" value="Nama Penuh" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.name"
                                    class="mt-2"
                                />
                            </div>

                            <!-- IC Number -->
                            <div>
                                <InputLabel
                                    for="ic_number"
                                    value="No. Kad Pengenalan"
                                />
                                <TextInput
                                    id="ic_number"
                                    v-model="form.ic_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.ic_number"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Phone -->
                            <div>
                                <InputLabel
                                    for="phone_number"
                                    value="No. Telefon"
                                />
                                <TextInput
                                    id="phone_number"
                                    v-model="form.phone_number"
                                    type="tel"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.phone_number"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Email -->
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

                            <!-- Relationship Status -->
                            <div>
                                <InputLabel
                                    for="relationship_status"
                                    value="Status Perkahwinan"
                                />
                                <select
                                    id="relationship_status"
                                    v-model="form.relationship_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    required
                                >
                                    <option value="">Pilih Status</option>
                                    <option
                                        v-for="status in relationshipStatuses"
                                        :key="status"
                                        :value="status"
                                    >
                                        {{ status }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors.relationship_status"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Occupation -->
                            <div>
                                <InputLabel
                                    for="occupation"
                                    value="Pekerjaan"
                                />
                                <TextInput
                                    id="occupation"
                                    v-model="form.occupation"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.occupation"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Alamat
                            </h3>

                            <!-- Street Address -->
                            <div>
                                <InputLabel for="address" value="Alamat" />
                                <TextInput
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.address"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Postcode -->
                            <div>
                                <InputLabel for="postcode" value="Poskod" />
                                <TextInput
                                    id="postcode"
                                    v-model="form.postcode"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.postcode"
                                    class="mt-2"
                                />
                            </div>

                            <!-- City -->
                            <div>
                                <InputLabel for="city" value="Bandar" />
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

                            <!-- State -->
                            <div>
                                <InputLabel for="state" value="Negeri" />
                                <select
                                    id="state"
                                    v-model="form.state"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    required
                                >
                                    <option value="">Pilih Negeri</option>
                                    <option
                                        v-for="state in states"
                                        :key="state"
                                        :value="state"
                                    >
                                        {{ state }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors.state"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Maklumat Kecemasan
                            </h3>

                            <!-- Emergency Contact Name -->
                            <div>
                                <InputLabel
                                    for="emergency_contact"
                                    value="Nama Untuk Dihubungi"
                                />
                                <TextInput
                                    id="emergency_contact"
                                    v-model="form.emergency_contact"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.emergency_contact"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Emergency Contact Phone -->
                            <div>
                                <InputLabel
                                    for="emergency_phone"
                                    value="No. Telefon Kecemasan"
                                />
                                <TextInput
                                    id="emergency_phone"
                                    v-model="form.emergency_phone"
                                    type="tel"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    :message="form.errors.emergency_phone"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Catatan Tambahan
                            </h3>
                            <div>
                                <InputLabel
                                    for="notes"
                                    value="Catatan (Jika Ada)"
                                />
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    rows="3"
                                ></textarea>
                                <InputError
                                    :message="form.errors.notes"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Hantar Pendaftaran
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
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
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Maklumat Asas
                                </h3>
                                <div class="mt-4 grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <InputLabel
                                            for="name"
                                            value="Nama Masjid/Surau"
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
                                            value="Jenis"
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
                                            value="Nombor Pendaftaran"
                                        />
                                        <TextInput
                                            id="registration_number"
                                            v-model="form.registration_number"
                                            type="text"
                                            class="mt-1 block w-full"
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
                            <div class="pt-6">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Maklumat Lokasi
                                </h3>
                                <div class="mt-4 grid gap-6 sm:grid-cols-2">
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
                                            :message="
                                                form.errors.street_address
                                            "
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
                                            :message="
                                                form.errors.address_line_2
                                            "
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
                                            value="Lokasi"
                                            class="required"
                                        />
                                        <TextInput
                                            id="location"
                                            v-model="form.location"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.location"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="latitude"
                                            value="Latitud"
                                        />
                                        <TextInput
                                            id="latitude"
                                            v-model="form.latitude"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError
                                            :message="form.errors.latitude"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="longitude"
                                            value="Longitud"
                                        />
                                        <TextInput
                                            id="longitude"
                                            v-model="form.longitude"
                                            type="text"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError
                                            :message="form.errors.longitude"
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="pt-6">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Maklumat Hubungan
                                </h3>
                                <div class="mt-4 grid gap-6 sm:grid-cols-2">
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
                                            :message="
                                                form.errors.contact_number
                                            "
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
                                        <InputLabel
                                            for="website"
                                            value="Laman Web"
                                        />
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

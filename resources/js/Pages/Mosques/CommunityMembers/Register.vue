<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';

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
    notes: '',
});

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
                        </div>

                        <!-- Address Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Alamat
                            </h3>

                            <!-- Full Address -->
                            <div>
                                <InputLabel
                                    for="address"
                                    value="Alamat Penuh"
                                />
                                <Textarea
                                    id="address"
                                    v-model="form.address"
                                    class="mt-1 block w-full"
                                    rows="3"
                                    required
                                />
                                <InputError
                                    :message="form.errors.address"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Maklumat Tambahan
                            </h3>

                            <!-- Notes -->
                            <div>
                                <InputLabel for="notes" value="Catatan" />
                                <Textarea
                                    id="notes"
                                    v-model="form.notes"
                                    class="mt-1 block w-full"
                                    rows="3"
                                />
                                <InputError
                                    :message="form.errors.notes"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Hantar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

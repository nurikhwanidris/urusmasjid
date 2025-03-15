<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    mosque: {
        type: Object,
        required: true,
    },
});

const purposes = [
    { value: 'tabung_masjid', label: 'Tabung Masjid' },
    { value: 'wakaf', label: 'Wakaf' },
    { value: 'zakat', label: 'Zakat' },
    { value: 'infaq', label: 'Infaq' },
    { value: 'sadaqah', label: 'Sadaqah' },
];

const form = useForm({
    amount: '',
    purpose: '',
    donor_name: '',
    donor_phone: '',
    donor_email: '',
    notes: '',
    payment_method: 'duitnow_qr',
});

const submit = () => {
    form.post(route('masjid.donations.store', props.mosque.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Derma - ' + mosque.name" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Derma
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Sila isi maklumat di bawah untuk membuat
                                sumbangan kepada {{ mosque.name }}.
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Amount -->
                            <div>
                                <InputLabel for="amount" value="Jumlah (MYR)" />
                                <TextInput
                                    id="amount"
                                    type="number"
                                    step="0.01"
                                    min="1"
                                    class="mt-1 block w-full"
                                    v-model="form.amount"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.amount"
                                />
                            </div>

                            <!-- Purpose -->
                            <div>
                                <InputLabel for="purpose" value="Tujuan" />
                                <select
                                    id="purpose"
                                    v-model="form.purpose"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    required
                                >
                                    <option value="">Pilih Tujuan</option>
                                    <option
                                        v-for="purpose in purposes"
                                        :key="purpose.value"
                                        :value="purpose.value"
                                    >
                                        {{ purpose.label }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.purpose"
                                />
                            </div>

                            <!-- Donor Information -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Maklumat Penderma (Pilihan)
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    Sila isi maklumat anda jika ingin menerima
                                    resit sumbangan.
                                </p>

                                <div
                                    class="mt-6 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2"
                                >
                                    <div>
                                        <InputLabel
                                            for="donor_name"
                                            value="Nama"
                                        />
                                        <TextInput
                                            id="donor_name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.donor_name"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.donor_name"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="donor_phone"
                                            value="No. Telefon"
                                        />
                                        <TextInput
                                            id="donor_phone"
                                            type="tel"
                                            class="mt-1 block w-full"
                                            v-model="form.donor_phone"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.donor_phone"
                                        />
                                    </div>

                                    <div class="sm:col-span-2">
                                        <InputLabel
                                            for="donor_email"
                                            value="Emel"
                                        />
                                        <TextInput
                                            id="donor_email"
                                            type="email"
                                            class="mt-1 block w-full"
                                            v-model="form.donor_email"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.donor_email"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <InputLabel
                                    for="notes"
                                    value="Catatan (Pilihan)"
                                />
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.notes"
                                />
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Teruskan ke Pembayaran
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Textarea from '@/Components/Textarea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    mosque: Object,
    committee: Object,
    positions: Object,
});

// Form data
const form = useForm({
    role: props.committee.role,
    name: props.committee.name,
    ic_number: props.committee.ic_number || '',
    phone_number: props.committee.phone_number || '',
    email: props.committee.email || '',
    address: props.committee.address || '',
    start_date: props.committee.start_date || '',
    end_date: props.committee.end_date || '',
    status: props.committee.status,
    notes: props.committee.notes || '',
    user_id: props.committee.user_id || '',
});

// Status options
const statusOptions = [
    { value: 'active', label: 'Aktif' },
    { value: 'inactive', label: 'Tidak Aktif' },
    { value: 'pending', label: 'Belum Disahkan' },
];

// Form submission
const submit = () => {
    form.put(
        route('masjid.jawatankuasa.update', [
            props.mosque.id,
            props.committee.id,
        ]),
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <Head :title="`${mosque.name} - Kemaskini AJK`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link
                        :href="
                            route('masjid.jawatankuasa.show', [
                                mosque.id,
                                committee.id,
                            ])
                        "
                    >
                        <SecondaryButton
                            >Kembali ke Butiran AJK</SecondaryButton
                        >
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-6 md:grid-cols-2">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <div>
                                        <h3
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Maklumat Jawatan
                                        </h3>
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="role"
                                            value="Jawatan"
                                            class="required"
                                        />
                                        <SelectInput
                                            id="role"
                                            v-model="form.role"
                                            :options="
                                                Object.entries(positions).map(
                                                    ([value, label]) => ({
                                                        value,
                                                        label,
                                                    }),
                                                )
                                            "
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.role"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="status"
                                            value="Status"
                                            class="required"
                                        />
                                        <SelectInput
                                            id="status"
                                            v-model="form.status"
                                            :options="statusOptions"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.status"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="start_date"
                                            value="Tarikh Mula"
                                        />
                                        <TextInput
                                            id="start_date"
                                            v-model="form.start_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError
                                            :message="form.errors.start_date"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="end_date"
                                            value="Tarikh Tamat"
                                        />
                                        <TextInput
                                            id="end_date"
                                            v-model="form.end_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError
                                            :message="form.errors.end_date"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="notes"
                                            value="Catatan"
                                        />
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

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <div>
                                        <h3
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Maklumat Peribadi
                                        </h3>
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="name"
                                            value="Nama Penuh"
                                            class="required"
                                        />
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

                                    <div>
                                        <InputLabel
                                            for="ic_number"
                                            value="Nombor IC"
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
                                            value="Nombor Telefon"
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
                                            for="address"
                                            value="Alamat"
                                        />
                                        <Textarea
                                            id="address"
                                            v-model="form.address"
                                            class="mt-1 block w-full"
                                            rows="3"
                                        />
                                        <InputError
                                            :message="form.errors.address"
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <Link
                                    :href="
                                        route(
                                            'masjid.jawatankuasa.index',
                                            mosque.id,
                                        )
                                    "
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-25"
                                >
                                    Batal
                                </Link>
                                <PrimaryButton
                                    :disabled="form.processing"
                                    class="ml-4"
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

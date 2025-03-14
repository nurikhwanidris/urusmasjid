<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    mosque: Object,
    member: Object,
});

const form = useForm({
    name: props.member.name,
    ic_number: props.member.ic_number || '',
    phone_number: props.member.phone_number || '',
    email: props.member.email || '',
    address: props.member.address || '',
    status: props.member.status,
    notes: props.member.notes || '',
});

const statusOptions = [
    { value: 'active', label: 'Aktif' },
    { value: 'pending', label: 'Belum Disahkan' },
    { value: 'inactive', label: 'Tidak Aktif' },
];

const submit = () => {
    form.patch(
        route('masjid.kariah.update', [props.mosque.id, props.member.id]),
    );
};
</script>

<template>
    <Head title="Kemaskini Ahli Kariah" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link
                        :href="
                            route('masjid.kariah.show', [mosque.id, member.id])
                        "
                    >
                        <SecondaryButton>Kembali ke Maklumat</SecondaryButton>
                    </Link>
                    <Link :href="route('masjid.kariah.index', mosque.id)">
                        <SecondaryButton>Kembali ke Senarai</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-6 grid gap-6 md:grid-cols-2">
                                <!-- Full Name -->
                                <div>
                                    <InputLabel for="name" value="Nama Penuh" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        required
                                        autofocus
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.name"
                                    />
                                </div>

                                <!-- IC Number -->
                                <div>
                                    <InputLabel
                                        for="ic_number"
                                        value="Nombor IC"
                                    />
                                    <TextInput
                                        id="ic_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.ic_number"
                                        placeholder="e.g. 880101-01-1234"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.ic_number"
                                    />
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <InputLabel
                                        for="phone_number"
                                        value="Nombor Telefon"
                                    />
                                    <TextInput
                                        id="phone_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.phone_number"
                                        placeholder="e.g. 012-3456789"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.phone_number"
                                    />
                                </div>

                                <!-- Email -->
                                <div>
                                    <InputLabel for="email" value="Emel" />
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="form.email"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.email"
                                    />
                                </div>

                                <!-- Status -->
                                <div>
                                    <InputLabel
                                        for="status"
                                        value="Status Keahlian"
                                    />
                                    <SelectInput
                                        id="status"
                                        class="mt-1 block w-full"
                                        v-model="form.status"
                                        :options="statusOptions"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.status"
                                    />
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-6">
                                <InputLabel for="address" value="Alamat" />
                                <TextareaInput
                                    id="address"
                                    class="mt-1 block w-full"
                                    v-model="form.address"
                                    rows="3"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.address"
                                />
                            </div>

                            <!-- Notes -->
                            <div class="mb-6">
                                <InputLabel for="notes" value="Nota" />
                                <TextareaInput
                                    id="notes"
                                    class="mt-1 block w-full"
                                    v-model="form.notes"
                                    rows="3"
                                    placeholder="Sebarang maklumat tambahan..."
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.notes"
                                />
                            </div>

                            <div class="flex items-center justify-end">
                                <PrimaryButton
                                    class="ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Simpan
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone_number: '',
    ic_number: '',
    role: 'community_member', // Default role
    password: '',
    password_confirmation: '',
});

const roles = [
    { value: 'admin', label: 'Admin' },
    { value: 'mosque_admin', label: 'Mosque Admin' },
    { value: 'community_member', label: 'Community Member' },
    { value: 'volunteer', label: 'Volunteer' },
    { value: 'khatib', label: 'Khatib' },
];

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Akaun</h1>
            <p class="mt-2 text-sm text-gray-600">
                Sertai Sistem Pengurusan Masjid & Surau
            </p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nama" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Emel" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone_number" value="Nombor Telefon" />

                <TextInput
                    id="phone_number"
                    type="tel"
                    class="mt-1 block w-full"
                    v-model="form.phone_number"
                    required
                    placeholder="e.g. 0123456789"
                />

                <InputError class="mt-2" :message="form.errors.phone_number" />
            </div>

            <div class="mt-4">
                <InputLabel for="ic_number" value="No. Kad Pengenalan" />

                <TextInput
                    id="ic_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.ic_number"
                    placeholder="e.g. 900101-01-1234"
                />

                <InputError class="mt-2" :message="form.errors.ic_number" />
            </div>

            <div class="mt-4">
                <InputLabel for="role" value="Pilih Peranan" />

                <select
                    id="role"
                    v-model="form.role"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >
                    <option
                        v-for="role in roles"
                        :key="role.value"
                        :value="role.value"
                    >
                        {{ role.label }}
                    </option>
                </select>

                <InputError class="mt-2" :message="form.errors.role" />

                <div class="mt-1 text-sm text-gray-500">
                    <p v-if="form.role === 'admin'">
                        Sebagai Admin, anda akan dapat mendaftar dan mengurus
                        masjid.
                    </p>
                    <p v-else-if="form.role === 'mosque_admin'">
                        Sebagai Admin Masjid, anda akan dapat mengurus masjid
                        tertentu.
                    </p>
                    <p v-else-if="form.role === 'community_member'">
                        Sebagai Ahli Komuniti, anda boleh bergabung dengan
                        masjid or request access.
                    </p>
                    <p v-else-if="form.role === 'volunteer'">
                        Sebagai Sukarelawan, anda boleh membantu dengan aktiviti
                        dan acara masjid.
                    </p>
                    <p v-else-if="form.role === 'khatib'">
                        Sebagai Khatib, anda boleh mengurus dan menyampaikan
                        khutbah Jumaat.
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Kata Laluan" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Sahkan kata laluan"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-6">
                <PrimaryButton
                    class="w-full justify-center"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Daftar
                </PrimaryButton>
            </div>

            <div class="mt-4 text-center">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 hover:text-gray-900"
                >
                    Sudah mempunyai akaun? Log masuk
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>

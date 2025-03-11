<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: String,
    verification_code: String,
});

const form = useForm({
    code: '',
});

const submit = () => {
    form.post(route('verification.verify-phone'));
};

const sendCode = () => {
    form.post(route('verification.send-code'));
};
</script>

<template>
    <Head title="Verify Phone" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6">
                            <p class="text-gray-700">
                                Untuk selesaikan pendaftaran, kami perlu
                                mengesahkan nombor telefon anda. Sila klik
                                butang di bawah untuk menerima kod pengesahan
                                melalui SMS.
                            </p>
                        </div>

                        <!-- Display verification code for demo purposes -->
                        <div
                            v-if="verification_code"
                            class="mb-6 rounded-md bg-yellow-50 p-4"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-5 w-5 text-yellow-400"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3
                                        class="text-sm font-medium text-yellow-800"
                                    >
                                        Mode Demo
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>
                                            Kod pengesahan anda adalah:
                                            <strong>{{
                                                verification_code
                                            }}</strong>
                                        </p>
                                        <p class="mt-1">
                                            Dalam aplikasi sebenar, ini akan
                                            dihantar ke telefon anda melalui
                                            SMS.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Message -->
                        <div
                            v-if="status"
                            class="mb-6 rounded-md bg-green-50 p-4"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-5 w-5 text-green-400"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p
                                        class="text-sm font-medium text-green-800"
                                    >
                                        {{ status }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Send Code Button -->
                        <div class="mb-6">
                            <PrimaryButton
                                @click="sendCode"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Hantar Kod Pengesahan
                            </PrimaryButton>
                        </div>

                        <!-- Verification Form -->
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="code" value="Kod Pengesahan" />
                                <TextInput
                                    id="code"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.code"
                                    required
                                    autofocus
                                    maxlength="6"
                                    placeholder="Masukkan 6 digit kod"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.code"
                                />
                            </div>

                            <div class="mt-6">
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
        </div>
    </AuthenticatedLayout>
</template>

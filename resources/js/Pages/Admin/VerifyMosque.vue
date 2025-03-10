<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Badge from '@/Components/Badge.vue';

const props = defineProps({
    mosque: Object,
});

const form = useForm({
    verification_status: props.mosque.verification_status || 'pending',
    verification_notes: props.mosque.verification_notes || '',
});

const getStatusColor = (status) => {
    switch (status) {
        case 'verified':
            return 'success';
        case 'pending':
            return 'warning';
        case 'rejected':
            return 'danger';
        default:
            return 'secondary';
    }
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const approve = () => {
    form.verification_status = 'verified';
    form.patch(route('masjid.verify', props.mosque.id));
};

const reject = () => {
    form.verification_status = 'rejected';
    form.patch(route('masjid.verify', props.mosque.id));
};

const submit = () => {
    form.patch(route('masjid.verify', props.mosque.id));
};
</script>

<template>
    <Head title="Verify Mosque" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Verify Mosque
                </h2>
                <div class="flex space-x-2">
                    <Link :href="route('admin.mosques.pending')">
                        <SecondaryButton>Back to Pending Mosques</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ mosque.name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ mosque.type === 'masjid' ? 'Masjid' : 'Surau' }}
                                    <span v-if="mosque.registration_number">
                                        • Registration: {{ mosque.registration_number }}
                                    </span>
                                </p>
                            </div>
                            <Badge :color="getStatusColor(mosque.verification_status)">
                                {{ mosque.verification_status.charAt(0).toUpperCase() + mosque.verification_status.slice(1) }}
                            </Badge>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Left Column: Mosque Information -->
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">
                                        Mosque Information
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div class="flex justify-between py-2">
                                            <dt class="font-medium text-gray-500">
                                                Address
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.full_address }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <dt class="font-medium text-gray-500">
                                                Location
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.location }}
                                            </dd>
                                        </div>
                                        <div v-if="mosque.contact_number" class="flex justify-between py-2">
                                            <dt class="font-medium text-gray-500">
                                                Contact Number
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.contact_number }}
                                            </dd>
                                        </div>
                                        <div v-if="mosque.email" class="flex justify-between py-2">
                                            <dt class="font-medium text-gray-500">
                                                Email
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.email }}
                                            </dd>
                                        </div>
                                        <div v-if="mosque.website" class="flex justify-between py-2">
                                            <dt class="font-medium text-gray-500">
                                                Website
                                            </dt>
                                            <dd class="text-gray-900">
                                                <a :href="mosque.website" target="_blank" class="text-emerald-600 hover:text-emerald-500">
                                                    {{ mosque.website }}
                                                </a>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">
                                        Registration Information
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div class="flex justify-between py-2">
                                            <dt class="font-medium text-gray-500">
                                                Submitted By
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.user?.name || 'Unknown' }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <dt class="font-medium text-gray-500">
                                                Submitted On
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ formatDate(mosque.created_at) }}
                                            </dd>
                                        </div>
                                        <div v-if="mosque.verified_at" class="flex justify-between py-2">
                                            <dt class="font-medium text-gray-500">
                                                Verified On
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ formatDate(mosque.verified_at) }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <!-- Right Column: Verification Form -->
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">
                                        Verification
                                    </h4>
                                    <form @submit.prevent="submit" class="mt-4 space-y-6">
                                        <div>
                                            <InputLabel for="verification_notes" value="Verification Notes" />
                                            <TextareaInput
                                                id="verification_notes"
                                                v-model="form.verification_notes"
                                                class="mt-1 block w-full"
                                                rows="4"
                                                placeholder="Add notes about this verification decision..."
                                            />
                                            <InputError :message="form.errors.verification_notes" class="mt-2" />
                                        </div>

                                        <div class="flex flex-col space-y-3 sm:flex-row sm:justify-end sm:space-x-3 sm:space-y-0">
                                            <SecondaryButton
                                                type="button"
                                                @click="form.reset()"
                                                :disabled="form.processing"
                                            >
                                                Reset
                                            </SecondaryButton>
                                            <DangerButton
                                                type="button"
                                                @click="reject"
                                                :disabled="form.processing"
                                            >
                                                Reject
                                            </DangerButton>
                                            <PrimaryButton
                                                type="button"
                                                @click="approve"
                                                :disabled="form.processing"
                                            >
                                                Approve
                                            </PrimaryButton>
                                        </div>
                                    </form>
                                </div>

                                <div v-if="mosque.admins && mosque.admins.length > 0">
                                    <h4 class="text-lg font-medium text-gray-900">
                                        Mosque Administrators
                                    </h4>
                                    <ul class="mt-2 divide-y divide-gray-200">
                                        <li v-for="admin in mosque.admins" :key="admin.id" class="py-2">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-900">
                                                        {{ admin.user?.name || 'Unknown' }}
                                                        <span v-if="admin.is_primary" class="ml-2 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">
                                                            Primary
                                                        </span>
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ admin.user?.email || 'No email' }}
                                                    </p>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ admin.role }}
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

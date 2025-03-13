<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Badge from '@/Components/Badge.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    mosque: Object,
    member: Object,
});

const confirmingDeletion = ref(false);

const getStatusColor = (status) => {
    switch (status) {
        case 'active':
            return 'success';
        case 'pending':
            return 'warning';
        case 'inactive':
            return 'secondary';
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

const confirmDelete = () => {
    confirmingDeletion.value = true;
};

const deleteMember = () => {
    router.delete(
        route('masjid.kariah.destroy', [props.mosque.id, props.member.id]),
        {
            onSuccess: () => {
                confirmingDeletion.value = false;
            },
        },
    );
};

const cancelDelete = () => {
    confirmingDeletion.value = false;
};
</script>

<template>
    <Head :title="member.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link
                        :href="
                            route('masjid.kariah.edit', [mosque.id, member.id])
                        "
                    >
                        <PrimaryButton>Kemaskini</PrimaryButton>
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
                        <div
                            class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4"
                        >
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ member.name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    <span v-if="member.ic_number">
                                        IC: {{ member.ic_number }}
                                    </span>
                                </p>
                            </div>
                            <Badge :color="getStatusColor(member.status)">
                                {{
                                    member.status === 'active'
                                        ? 'Aktif'
                                        : member.status === 'pending'
                                          ? 'Menunggu'
                                          : 'Tidak Aktif'
                                }}
                            </Badge>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Left Column: Personal Information -->
                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Maklumat Peribadi
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div
                                            v-if="member.address"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Alamat
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ member.address }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div>
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Maklumat Hubungan
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div
                                            v-if="member.phone_number"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Nombor Telefon
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ member.phone_number }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="member.email"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Emel
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ member.email }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <!-- Right Column: Membership Information -->
                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Maklumat Keahlian
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div class="flex justify-between py-2">
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Masjid
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.name }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="member.joined_at"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Tarikh Daftar
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{
                                                    formatDate(member.joined_at)
                                                }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Dicipta Pada
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{
                                                    formatDate(
                                                        member.created_at,
                                                    )
                                                }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div v-if="member.notes">
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Nota
                                    </h4>
                                    <div
                                        class="mt-2 rounded-md border border-gray-200 p-4 text-gray-700"
                                    >
                                        {{ member.notes }}
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <DangerButton @click="confirmDelete">
                                        Padam Ahli Kariah
                                    </DangerButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="confirmingDeletion" @close="cancelDelete">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Adakah anda pasti untuk memadam ahli kariah ini?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Tindakan ini tidak boleh dibatalkan. Semua maklumat ahli
                    kariah ini akan dipadam secara kekal.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="cancelDelete">
                        Batal
                    </SecondaryButton>
                    <DangerButton class="ml-3" @click="deleteMember">
                        Padam Ahli Kariah
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

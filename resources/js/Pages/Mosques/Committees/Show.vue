<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Badge from '@/Components/Badge.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    mosque: Object,
    committee: Object,
});

// Helper function to get status badge color
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

// Helper function to format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

// Delete confirmation modal
const showDeleteModal = ref(false);
const form = useForm({});

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const closeModal = () => {
    showDeleteModal.value = false;
};

const deleteCommittee = () => {
    form.delete(
        route('masjid.jawatankuasa.destroy', [
            props.mosque.id,
            props.committee.id,
        ]),
        {
            onSuccess: () => {
                // Redirect will happen automatically
            },
        },
    );
};
</script>

<template>
    <Head :title="`${mosque.name} - ${committee.role}: ${committee.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link
                        :href="
                            route('masjid.jawatankuasa.edit', [
                                mosque.id,
                                committee.id,
                            ])
                        "
                    >
                        <PrimaryButton>Kemaskini</PrimaryButton>
                    </Link>
                    <Link :href="route('masjid.jawatankuasa.index', mosque.id)">
                        <SecondaryButton
                            >Kembali ke Senarai AJK</SecondaryButton
                        >
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
                                    {{ committee.name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ committee.role }}
                                    <Badge
                                        :color="
                                            getStatusColor(committee.status)
                                        "
                                        class="ml-2"
                                    >
                                        {{
                                            committee.status === 'active'
                                                ? 'Aktif'
                                                : committee.status === 'pending'
                                                  ? 'Menunggu'
                                                  : 'Tidak Aktif'
                                        }}
                                    </Badge>
                                </p>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Left Column: Position Information -->
                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Maklumat Jawatan
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div class="flex justify-between py-2">
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Jawatan
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ committee.role }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Status
                                            </dt>
                                            <dd class="text-gray-900">
                                                <Badge
                                                    :color="
                                                        getStatusColor(
                                                            committee.status,
                                                        )
                                                    "
                                                >
                                                    {{
                                                        committee.status ===
                                                        'active'
                                                            ? 'Aktif'
                                                            : committee.status ===
                                                                'pending'
                                                              ? 'Menunggu'
                                                              : 'Tidak Aktif'
                                                    }}
                                                </Badge>
                                            </dd>
                                        </div>
                                        <div
                                            v-if="committee.start_date"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Tarikh Mula
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{
                                                    formatDate(
                                                        committee.start_date,
                                                    )
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="committee.end_date"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Tarikh Tamat
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{
                                                    formatDate(
                                                        committee.end_date,
                                                    )
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="committee.notes"
                                            class="py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Catatan
                                            </dt>
                                            <dd
                                                class="mt-1 whitespace-pre-line text-gray-900"
                                            >
                                                {{ committee.notes }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <!-- Right Column: Personal Information -->
                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Maklumat Peribadi
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div class="flex justify-between py-2">
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Nama Penuh
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ committee.name }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="committee.ic_number"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Nombor IC
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ committee.ic_number }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="committee.phone_number"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Nombor Telefon
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ committee.phone_number }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="committee.email"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Emel
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ committee.email }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="committee.address"
                                            class="py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Alamat
                                            </dt>
                                            <dd
                                                class="mt-1 whitespace-pre-line text-gray-900"
                                            >
                                                {{ committee.address }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="committee.user"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Akaun Pengguna
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ committee.user.name }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <div class="flex justify-end">
                                <DangerButton
                                    @click="confirmDelete"
                                    class="ml-3"
                                >
                                    Padam AJK
                                </DangerButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Adakah anda pasti mahu memadamkan AJK ini?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Tindakan ini tidak boleh dibatalkan. Semua maklumat
                    berkaitan AJK ini akan dipadam secara kekal.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                    <DangerButton class="ml-3" @click="deleteCommittee">
                        Padam AJK
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

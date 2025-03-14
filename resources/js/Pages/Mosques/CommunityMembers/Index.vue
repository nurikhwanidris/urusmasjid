<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Badge from '@/Components/Badge.vue';
import Pagination from '@/Components/Pagination.vue';
import SearchInput from '@/Components/SearchInput.vue';

const props = defineProps({
    mosque: Object,
    members: Object,
});

const search = ref('');
const filteredMembers = computed(() => {
    if (!search.value) return props.members.data;

    const searchTerm = search.value.toLowerCase();
    return props.members.data.filter(
        (member) =>
            member.full_name.toLowerCase().includes(searchTerm) ||
            (member.email && member.email.toLowerCase().includes(searchTerm)) ||
            (member.phone_number && member.phone_number.includes(searchTerm)),
    );
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
</script>

<template>
    <Head title="Ahli Kariah" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link :href="route('masjid.kariah.create', mosque.id)">
                        <PrimaryButton>Tambah Ahli Kariah</PrimaryButton>
                    </Link>
                    <Link :href="route('masjid.show', mosque.id)">
                        <SecondaryButton>Kembali ke Masjid</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">
                                Senarai Ahli Kariah - {{ mosque.name }}
                            </h3>
                            <SearchInput
                                v-model="search"
                                placeholder="Cari ahli kariah..."
                                class="w-64"
                            />
                        </div>

                        <div
                            v-if="members.data.length === 0"
                            class="py-8 text-center text-gray-500"
                        >
                            Tiada ahli kariah ditemui.
                        </div>

                        <div v-else>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Nama
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Hubungi
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Status
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Tindakan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-gray-200 bg-white"
                                    >
                                        <tr
                                            v-for="member in filteredMembers"
                                            :key="member.id"
                                        >
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    class="font-medium text-gray-900"
                                                >
                                                    {{ member.name }}
                                                </div>
                                                <div
                                                    v-if="member.ic_number"
                                                    class="text-xs text-gray-500"
                                                >
                                                    IC: {{ member.ic_number }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    v-if="member.phone_number"
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ member.phone_number }}
                                                </div>
                                                <div
                                                    v-if="member.email"
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ member.email }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <Badge
                                                    :color="
                                                        getStatusColor(
                                                            member.status,
                                                        )
                                                    "
                                                >
                                                    {{
                                                        member.status ===
                                                        'active'
                                                            ? 'Aktif'
                                                            : member.status ===
                                                                'pending'
                                                              ? 'Belum Disahkan'
                                                              : 'Tidak Aktif'
                                                    }}
                                                </Badge>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex justify-end space-x-2"
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'masjid.kariah.show',
                                                                [
                                                                    mosque.id,
                                                                    member.id,
                                                                ],
                                                            )
                                                        "
                                                        class="text-emerald-600 hover:text-emerald-900"
                                                    >
                                                        Lihat
                                                    </Link>
                                                    <span class="mx-1">|</span>
                                                    <Link
                                                        :href="
                                                            route(
                                                                'masjid.kariah.edit',
                                                                [
                                                                    mosque.id,
                                                                    member.id,
                                                                ],
                                                            )
                                                        "
                                                        class="text-emerald-600 hover:text-emerald-900"
                                                    >
                                                        Kemaskini
                                                    </Link>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                <Pagination :links="members.links" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

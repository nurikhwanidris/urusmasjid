<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Badge from '@/Components/Badge.vue';

defineProps({
    mosque: Object,
});

// Helper function to get status badge color
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
</script>

<template>
    <Head :title="mosque.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link :href="route('masjid.edit', mosque.id)">
                        <PrimaryButton>Kemaskini Masjid</PrimaryButton>
                    </Link>
                    <Link :href="route('masjid.index')">
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
                                    {{ mosque.name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{
                                        mosque.type === 'masjid'
                                            ? 'Masjid'
                                            : 'Surau'
                                    }}
                                    <span v-if="mosque.registration_number">
                                        • Registration:
                                        {{ mosque.registration_number }}
                                    </span>
                                </p>
                            </div>
                            <Badge
                                :color="
                                    getStatusColor(mosque.verification_status)
                                "
                            >
                                {{
                                    mosque.verification_status
                                        .charAt(0)
                                        .toUpperCase() +
                                    mosque.verification_status.slice(1)
                                }}
                            </Badge>
                        </div>

                        <!-- Quick Actions -->
                        <div
                            class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4"
                        >
                            <Link
                                :href="
                                    route(
                                        'masjid.jawatankuasa.index',
                                        mosque.id,
                                    )
                                "
                                class="flex items-center justify-center rounded-lg border border-gray-200 bg-white p-4 text-center shadow-sm hover:bg-gray-50"
                            >
                                <div>
                                    <div class="mb-2 flex justify-center">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-emerald-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Ahli Jawatankuasa
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Urus AJK masjid
                                    </p>
                                </div>
                            </Link>

                            <div
                                class="flex items-center justify-center rounded-lg border border-gray-200 bg-white p-4 text-center shadow-sm hover:bg-gray-50"
                            >
                                <div>
                                    <div class="mb-2 flex justify-center">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-emerald-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Acara
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Urus acara masjid
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-center rounded-lg border border-gray-200 bg-white p-4 text-center shadow-sm hover:bg-gray-50"
                            >
                                <div>
                                    <div class="mb-2 flex justify-center">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-emerald-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Derma
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Urus derma masjid
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-center rounded-lg border border-gray-200 bg-white p-4 text-center shadow-sm hover:bg-gray-50"
                            >
                                <div>
                                    <div class="mb-2 flex justify-center">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-emerald-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </div>
                                    <h3
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Tetapan
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Urus tetapan masjid
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Left Column: Basic Information -->
                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Maklumat Asas
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div class="flex justify-between py-2">
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Alamat
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.address }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Zon JAKIM
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.jakim_zone }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="mosque.contact_number"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Nombor Telefon
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.contact_number }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="mosque.email"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Emel
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{ mosque.email }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="mosque.website"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Laman Web
                                            </dt>
                                            <dd class="text-gray-900">
                                                <a
                                                    :href="mosque.website"
                                                    target="_blank"
                                                    class="text-emerald-600 hover:text-emerald-500"
                                                >
                                                    {{ mosque.website }}
                                                </a>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div>
                                    <h4
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Maklumat Pendaftaran
                                    </h4>
                                    <dl class="mt-2 divide-y divide-gray-200">
                                        <div class="flex justify-between py-2">
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Dicipta Oleh
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{
                                                    mosque.user?.name ||
                                                    'Unknown'
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
                                                        mosque.created_at,
                                                    )
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="mosque.verified_at"
                                            class="flex justify-between py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Disahkan Pada
                                            </dt>
                                            <dd class="text-gray-900">
                                                {{
                                                    formatDate(
                                                        mosque.verified_at,
                                                    )
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            v-if="mosque.verification_notes"
                                            class="py-2"
                                        >
                                            <dt
                                                class="font-medium text-gray-500"
                                            >
                                                Nota Pengesahan
                                            </dt>
                                            <dd class="mt-1 text-gray-900">
                                                {{ mosque.verification_notes }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <!-- Right Column: Admins and Community Members -->
                            <div class="space-y-6">
                                <div>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Pengurus
                                        </h4>
                                        <button
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-500"
                                        >
                                            Tambah Pengurus
                                        </button>
                                    </div>
                                    <ul class="mt-2 divide-y divide-gray-200">
                                        <li
                                            v-for="admin in mosque.admins"
                                            :key="admin.id"
                                            class="py-2"
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div>
                                                    <p
                                                        class="font-medium text-gray-900"
                                                    >
                                                        {{
                                                            admin.user?.name ||
                                                            'Si Polan'
                                                        }}
                                                        <span
                                                            v-if="
                                                                admin.is_primary
                                                            "
                                                            class="ml-2 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800"
                                                        >
                                                            Utama
                                                        </span>
                                                    </p>
                                                    <p
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{
                                                            admin.user?.email ||
                                                            'No email'
                                                        }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ admin.role }}
                                                </div>
                                            </div>
                                        </li>
                                        <li
                                            v-if="
                                                !mosque.admins ||
                                                mosque.admins.length === 0
                                            "
                                            class="py-2 text-center text-gray-500"
                                        >
                                            Tiada pengurus ditemui.
                                        </li>
                                    </ul>
                                </div>

                                <div>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Ahli Jawatankuasa (AJK)
                                        </h4>
                                        <Link
                                            :href="
                                                route(
                                                    'masjid.jawatankuasa.index',
                                                    mosque.id,
                                                )
                                            "
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-500"
                                        >
                                            Urus AJK
                                        </Link>
                                    </div>
                                    <div class="mt-2">
                                        <p
                                            class="py-2 text-center text-gray-500"
                                        >
                                            Klik "Urus AJK" untuk melihat dan
                                            mengurus ahli jawatankuasa masjid.
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <h4
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            Ahli Kariah
                                        </h4>
                                        <button
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-500"
                                        >
                                            Tambah Ahli Kariah
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <p
                                            v-if="
                                                !mosque.community_members ||
                                                mosque.community_members
                                                    .length === 0
                                            "
                                            class="py-2 text-center text-gray-500"
                                        >
                                            Tiada ahli kariah ditemui.
                                        </p>
                                        <div
                                            v-else
                                            class="rounded-md border border-gray-200"
                                        >
                                            <div
                                                class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-500"
                                            >
                                                <div>Nama</div>
                                                <div>Status</div>
                                            </div>
                                            <ul
                                                class="divide-y divide-gray-200"
                                            >
                                                <li
                                                    v-for="member in mosque.community_members"
                                                    :key="member.id"
                                                    class="flex items-center justify-between px-4 py-2"
                                                >
                                                    <div>
                                                        <p
                                                            class="font-medium text-gray-900"
                                                        >
                                                            {{
                                                                member.full_name
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-xs text-gray-500"
                                                        >
                                                            {{
                                                                member.email ||
                                                                'Tiada emel'
                                                            }}
                                                        </p>
                                                    </div>
                                                    <Badge
                                                        :color="
                                                            member.membership_status ===
                                                            'active'
                                                                ? 'success'
                                                                : member.membership_status ===
                                                                    'pending'
                                                                  ? 'warning'
                                                                  : 'secondary'
                                                        "
                                                    >
                                                        {{
                                                            member.membership_status
                                                                .charAt(0)
                                                                .toUpperCase() +
                                                            member.membership_status.slice(
                                                                1,
                                                            )
                                                        }}
                                                    </Badge>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

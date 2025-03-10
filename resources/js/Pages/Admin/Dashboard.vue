<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-6 text-lg font-medium text-gray-900">
                            Mosque Management
                        </h3>

                        <!-- Stats Cards -->
                        <div
                            class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                        >
                            <div
                                class="overflow-hidden rounded-lg bg-white shadow"
                            >
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 rounded-md bg-blue-100 p-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6 text-blue-600"
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
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt
                                                    class="truncate text-sm font-medium text-gray-500"
                                                >
                                                    Total Mosques
                                                </dt>
                                                <dd>
                                                    <div
                                                        class="text-lg font-medium text-gray-900"
                                                    >
                                                        {{ stats.totalMosques }}
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <Link
                                            :href="route('admin.mosques.all')"
                                            class="font-medium text-emerald-600 hover:text-emerald-500"
                                        >
                                            View all mosques
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="overflow-hidden rounded-lg bg-white shadow"
                            >
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 rounded-md bg-yellow-100 p-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6 text-yellow-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt
                                                    class="truncate text-sm font-medium text-gray-500"
                                                >
                                                    Pending Verification
                                                </dt>
                                                <dd>
                                                    <div
                                                        class="text-lg font-medium text-gray-900"
                                                    >
                                                        {{
                                                            stats.pendingMosques
                                                        }}
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <Link
                                            :href="
                                                route('admin.mosques.pending')
                                            "
                                            class="font-medium text-emerald-600 hover:text-emerald-500"
                                        >
                                            View pending mosques
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="overflow-hidden rounded-lg bg-white shadow"
                            >
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 rounded-md bg-green-100 p-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6 text-green-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt
                                                    class="truncate text-sm font-medium text-gray-500"
                                                >
                                                    Verified Mosques
                                                </dt>
                                                <dd>
                                                    <div
                                                        class="text-lg font-medium text-gray-900"
                                                    >
                                                        {{
                                                            stats.verifiedMosques
                                                        }}
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <Link
                                            :href="route('admin.mosques.all')"
                                            class="font-medium text-emerald-600 hover:text-emerald-500"
                                        >
                                            View all mosques
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="overflow-hidden rounded-lg bg-white shadow"
                            >
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 rounded-md bg-red-100 p-3"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6 text-red-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt
                                                    class="truncate text-sm font-medium text-gray-500"
                                                >
                                                    Rejected Mosques
                                                </dt>
                                                <dd>
                                                    <div
                                                        class="text-lg font-medium text-gray-900"
                                                    >
                                                        {{
                                                            stats.rejectedMosques
                                                        }}
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                                    <div class="text-sm">
                                        <Link
                                            :href="route('admin.mosques.all')"
                                            class="font-medium text-emerald-600 hover:text-emerald-500"
                                        >
                                            View all mosques
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div>
                            <h4
                                class="mb-4 text-base font-medium text-gray-900"
                            >
                                Quick Actions
                            </h4>
                            <div
                                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                            >
                                <Link
                                    :href="route('admin.mosques.pending')"
                                    class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:bg-emerald-50"
                                >
                                    <div
                                        class="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-emerald-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            Verify Pending Mosques
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Review and approve mosque
                                            registrations
                                        </p>
                                    </div>
                                </Link>

                                <Link
                                    :href="route('admin.mosques.all')"
                                    class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:bg-emerald-50"
                                >
                                    <div
                                        class="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-emerald-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            Manage All Mosques
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            View and manage all registered
                                            mosques
                                        </p>
                                    </div>
                                </Link>

                                <Link
                                    :href="route('dashboard')"
                                    class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:bg-emerald-50"
                                >
                                    <div
                                        class="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-emerald-600"
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
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            System Settings
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Configure system-wide settings
                                        </p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

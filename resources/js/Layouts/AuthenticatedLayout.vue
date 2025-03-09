<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Sidebar from '@/Components/Sidebar.vue';

const showingSidebar = ref(true);
const showingMobileMenu = ref(false);

const toggleSidebar = () => {
    showingSidebar.value = !showingSidebar.value;
};

const toggleMobileMenu = () => {
    showingMobileMenu.value = !showingMobileMenu.value;
};

const closeMobileMenu = () => {
    showingMobileMenu.value = false;
};
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar Component -->
        <Sidebar
            :collapsed="!showingSidebar"
            :show-mobile-menu="showingMobileMenu"
            @close-mobile-menu="closeMobileMenu"
        />

        <!-- Main Content -->
        <div class="flex flex-1 flex-col">
            <!-- Top Bar -->
            <div
                class="flex h-16 items-center justify-between border-b border-gray-100 bg-white px-4 shadow-sm"
            >
                <div class="flex items-center">
                    <!-- Sidebar Toggle Button (Desktop) -->
                    <button
                        @click="toggleSidebar"
                        class="hidden items-center justify-center rounded-md p-2 text-gray-500 transition-all duration-200 ease-in-out hover:bg-gray-100 hover:text-emerald-600 focus:bg-gray-100 focus:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 md:inline-flex"
                        :title="
                            showingSidebar
                                ? 'Collapse sidebar'
                                : 'Expand sidebar'
                        "
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: !showingSidebar,
                                    'inline-flex': showingSidebar,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: showingSidebar,
                                    'inline-flex': !showingSidebar,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>

                    <!-- Mobile Menu Toggle Button -->
                    <button
                        @click="toggleMobileMenu"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 transition-all duration-200 ease-in-out hover:bg-gray-100 hover:text-emerald-600 focus:bg-gray-100 focus:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 md:hidden"
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingMobileMenu,
                                    'inline-flex': !showingMobileMenu,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingMobileMenu,
                                    'inline-flex': showingMobileMenu,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Page Title (optional) -->
                <div v-if="$slots.header" class="hidden md:block">
                    <h1 class="text-lg font-semibold text-gray-800">
                        <slot name="header" />
                    </h1>
                </div>

                <!-- Mobile User Menu (visible only on small screens) -->
                <div class="ml-auto sm:hidden">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition-all duration-200 ease-in-out hover:bg-gray-50 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1"
                            >
                                {{ $page.props.auth.user.name }}
                                <svg
                                    class="ml-2 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="border-b border-gray-100 px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $page.props.auth.user.name }}
                                </p>
                                <p class="truncate text-xs text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </p>
                            </div>
                            <DropdownLink
                                :href="route('profile.edit')"
                                class="flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mr-2 h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                Profile
                            </DropdownLink>
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="flex w-full items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mr-2 h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <!-- Page Heading (Mobile) -->
            <header class="bg-white shadow-sm md:hidden" v-if="$slots.header">
                <div class="px-4 py-4">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

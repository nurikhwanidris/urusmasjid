<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import SidebarLink from '@/Components/SidebarLink.vue';
import SidebarMenu from '@/Components/SidebarMenu.vue';
import SidebarSubmenuItem from '@/Components/SidebarSubmenuItem.vue';
import MobileSidebarLink from '@/Components/MobileSidebarLink.vue';
import MobileSidebarMenu from '@/Components/MobileSidebarMenu.vue';

const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false,
    },
    showMobileMenu: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close-mobile-menu']);

const page = usePage();
const user = computed(() => page.props.auth.user);
const showUserMenu = ref(false);
const userMenuRef = ref(null);
const userButtonRef = ref(null);

// Get current page props
const currentPage = usePage();
const currentMosque = computed(() => currentPage.props.mosque);

// Dashboard navigation item
const dashboardItem = {
    name: 'Utama',
    route: 'dashboard',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>`,
};

// Mosque management menu
const mosqueManagementIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                              </svg>`;

const mosqueManagementItems = [
    {
        name: 'Senarai Masjid',
        route: 'masjid.index',
    },
    {
        name: 'Daftar Masjid',
        route: 'masjid.create',
    },
];

// Events menu
const eventsIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>`;

const eventsItems = computed(() => {
    // If we have a current mosque, use its ID for the routes
    if (currentMosque.value) {
        return [
            {
                name: 'Senarai Acara',
                route: 'masjid.acara.index',
                params: [currentMosque.value.id],
            },
            {
                name: 'Cipta Acara',
                route: 'masjid.acara.create',
                params: [currentMosque.value.id],
            },
        ];
    }

    // Otherwise, just link to the mosque list
    return [
        {
            name: 'Senarai Acara',
            route: 'masjid.index',
            params: [],
        },
        {
            name: 'Cipta Acara',
            route: 'masjid.index',
            params: [],
        },
    ];
});

// Donations menu
const donationsIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                       </svg>`;

const donationsItems = [
    {
        name: 'Senarai Derma',
        route: 'masjid.index', // Update with actual route
    },
    {
        name: 'Cipta Derma',
        route: 'masjid.index', // Update with actual route
    },
];

// Settings item
const settingsItem = {
    name: 'Tetapan',
    route: 'masjid.index', // Update with actual route
    icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>`,
};

// Admin menu
const adminDashboardIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>`;

const adminItems = [
    {
        name: 'Admin Dashboard',
        route: 'admin.dashboard',
    },
    {
        name: 'Pending Mosques',
        route: 'admin.mosques.pending',
    },
    {
        name: 'All Mosques',
        route: 'admin.mosques.all',
    },
];

// Check if user is admin
const isAdmin = computed(() => {
    return user.value && user.value.role === 'admin';
});

const closeMobileMenu = () => {
    emit('close-mobile-menu');
};

const toggleUserMenu = () => {
    showUserMenu.value = !showUserMenu.value;
};

const closeUserMenu = () => {
    showUserMenu.value = false;
};

const handleClickOutside = (event) => {
    if (
        userMenuRef.value &&
        !userMenuRef.value.contains(event.target) &&
        userButtonRef.value &&
        !userButtonRef.value.contains(event.target)
    ) {
        closeUserMenu();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const logout = () => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route('logout');

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    }

    document.body.appendChild(form);
    form.submit();
};
</script>

<template>
    <!-- Desktop Sidebar -->
    <aside
        :class="[
            'hidden overflow-x-hidden border-r border-gray-100 bg-white shadow-sm transition-all duration-300 ease-in-out md:flex',
            props.collapsed ? 'w-20' : 'w-64',
        ]"
    >
        <div class="flex h-full w-full flex-col">
            <!-- Logo -->
            <div
                class="flex h-16 items-center justify-center border-b border-gray-50 px-4"
            >
                <Link :href="route('dashboard')" class="flex items-center">
                    <ApplicationLogo
                        class="block h-[40px] w-auto fill-current text-emerald-600 transition-all duration-200"
                        :class="{ 'mx-auto': props.collapsed }"
                    />
                    <span
                        v-if="!props.collapsed"
                        class="ml-3 text-base font-semibold text-emerald-600 transition-opacity duration-200"
                        >Urus Masjid</span
                    >
                </Link>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-6">
                <div class="space-y-2 px-2">
                    <!-- Dashboard -->
                    <SidebarLink
                        :href="route(dashboardItem.route)"
                        :active="route().current(dashboardItem.route)"
                        :collapsed="props.collapsed"
                    >
                        <template #icon>
                            <div v-html="dashboardItem.icon"></div>
                        </template>
                        {{ dashboardItem.name }}
                    </SidebarLink>

                    <!-- Mosque Management Menu -->
                    <SidebarMenu
                        title="Pengurusan Masjid"
                        :icon="mosqueManagementIcon"
                        :collapsed="props.collapsed"
                        :routes="
                            mosqueManagementItems.map((item) => item.route)
                        "
                    >
                        <SidebarSubmenuItem
                            v-for="item in mosqueManagementItems"
                            :key="item.name"
                            :href="route(item.route)"
                            :active="route().current(item.route)"
                        >
                            {{ item.name }}
                        </SidebarSubmenuItem>
                    </SidebarMenu>

                    <!-- Events Menu -->
                    <SidebarMenu
                        title="Acara"
                        :icon="eventsIcon"
                        :collapsed="props.collapsed"
                        :routes="eventsItems.map((item) => item.route)"
                    >
                        <SidebarSubmenuItem
                            v-for="item in eventsItems"
                            :key="item.name"
                            :href="route(item.route, item.params)"
                            :active="
                                route().current(
                                    item.route,
                                    item.params && item.params.length > 0
                                        ? { mosque: item.params[0] }
                                        : {},
                                )
                            "
                        >
                            {{ item.name }}
                        </SidebarSubmenuItem>
                    </SidebarMenu>

                    <!-- Donations Menu -->
                    <SidebarMenu
                        title="Derma"
                        :icon="donationsIcon"
                        :collapsed="props.collapsed"
                        :routes="donationsItems.map((item) => item.route)"
                    >
                        <SidebarSubmenuItem
                            v-for="item in donationsItems"
                            :key="item.name"
                            :href="route(item.route)"
                            :active="route().current(item.route)"
                        >
                            {{ item.name }}
                        </SidebarSubmenuItem>
                    </SidebarMenu>

                    <!-- Settings -->
                    <SidebarLink
                        :href="route(settingsItem.route)"
                        :active="route().current(settingsItem.route)"
                        :collapsed="props.collapsed"
                    >
                        <template #icon>
                            <div v-html="settingsItem.icon"></div>
                        </template>
                        {{ settingsItem.name }}
                    </SidebarLink>

                    <!-- Admin Section -->
                    <template v-if="isAdmin">
                        <div
                            class="my-4 border-t border-gray-100 pt-4"
                            :class="{ 'px-2': !props.collapsed }"
                        >
                            <p
                                v-if="!props.collapsed"
                                class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400"
                            >
                                Admin
                            </p>
                        </div>

                        <!-- Admin Menu -->
                        <SidebarMenu
                            title="Admin"
                            :icon="adminDashboardIcon"
                            :collapsed="props.collapsed"
                            :routes="adminItems.map((item) => item.route)"
                        >
                            <SidebarSubmenuItem
                                v-for="item in adminItems"
                                :key="item.name"
                                :href="route(item.route)"
                                :active="route().current(item.route)"
                            >
                                {{ item.name }}
                            </SidebarSubmenuItem>
                        </SidebarMenu>
                    </template>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Menu Overlay -->
    <div
        v-if="props.showMobileMenu"
        class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 transition-opacity md:hidden"
        @click="closeMobileMenu"
    ></div>

    <!-- Mobile Menu -->
    <div
        v-if="props.showMobileMenu"
        class="fixed inset-y-0 left-0 z-50 w-full max-w-xs transform overflow-y-auto bg-white transition duration-300 ease-in-out md:hidden"
    >
        <div class="flex h-full flex-col">
            <!-- Mobile Header -->
            <div
                class="flex h-16 items-center justify-between border-b border-gray-100 px-4"
            >
                <Link :href="route('dashboard')" class="flex items-center">
                    <ApplicationLogo
                        class="block h-[40px] w-auto fill-current text-emerald-600"
                    />
                    <span class="ml-3 text-base font-semibold text-emerald-600"
                        >Urus Masjid</span
                    >
                </Link>
                <button
                    @click="closeMobileMenu"
                    class="rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div class="flex-1 overflow-y-auto py-6">
                <div class="w-full space-y-2 px-3">
                    <!-- Dashboard -->
                    <MobileSidebarLink
                        :href="route(dashboardItem.route)"
                        :active="route().current(dashboardItem.route)"
                    >
                        <template #icon>
                            <div v-html="dashboardItem.icon"></div>
                        </template>
                        {{ dashboardItem.name }}
                    </MobileSidebarLink>

                    <!-- Mosque Management Menu -->
                    <MobileSidebarMenu
                        title="Pengurusan Masjid"
                        :icon="mosqueManagementIcon"
                        :routes="
                            mosqueManagementItems.map((item) => item.route)
                        "
                    >
                        <MobileSidebarLink
                            v-for="item in mosqueManagementItems"
                            :key="item.name"
                            :href="route(item.route)"
                            :active="route().current(item.route)"
                        >
                            {{ item.name }}
                        </MobileSidebarLink>
                    </MobileSidebarMenu>

                    <!-- Events Menu -->
                    <MobileSidebarMenu
                        title="Acara"
                        :icon="eventsIcon"
                        :routes="eventsItems.map((item) => item.route)"
                    >
                        <MobileSidebarLink
                            v-for="item in eventsItems"
                            :key="item.name"
                            :href="route(item.route, item.params)"
                            :active="
                                route().current(
                                    item.route,
                                    item.params && item.params.length > 0
                                        ? { mosque: item.params[0] }
                                        : {},
                                )
                            "
                        >
                            {{ item.name }}
                        </MobileSidebarLink>
                    </MobileSidebarMenu>

                    <!-- Donations Menu -->
                    <MobileSidebarMenu
                        title="Derma"
                        :icon="donationsIcon"
                        :routes="donationsItems.map((item) => item.route)"
                    >
                        <MobileSidebarLink
                            v-for="item in donationsItems"
                            :key="item.name"
                            :href="route(item.route)"
                            :active="route().current(item.route)"
                        >
                            {{ item.name }}
                        </MobileSidebarLink>
                    </MobileSidebarMenu>

                    <!-- Settings -->
                    <MobileSidebarLink
                        :href="route(settingsItem.route)"
                        :active="route().current(settingsItem.route)"
                    >
                        <template #icon>
                            <div v-html="settingsItem.icon"></div>
                        </template>
                        {{ settingsItem.name }}
                    </MobileSidebarLink>

                    <!-- Admin Section -->
                    <template v-if="isAdmin">
                        <div class="my-4 border-t border-gray-100 pt-4">
                            <p
                                class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400"
                            >
                                Admin
                            </p>
                        </div>

                        <!-- Admin Menu -->
                        <MobileSidebarMenu
                            title="Admin"
                            :icon="adminDashboardIcon"
                            :routes="adminItems.map((item) => item.route)"
                        >
                            <MobileSidebarLink
                                v-for="item in adminItems"
                                :key="item.name"
                                :href="route(item.route)"
                                :active="route().current(item.route)"
                            >
                                {{ item.name }}
                            </MobileSidebarLink>
                        </MobileSidebarMenu>
                    </template>
                </div>
            </div>

            <!-- Mobile User Profile -->
            <div class="border-t border-gray-100 bg-gray-50">
                <!-- Mobile User Profile Header -->
                <div
                    @click="toggleUserMenu"
                    class="flex w-full cursor-pointer items-center p-4 transition-colors duration-200 hover:bg-gray-100"
                >
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                    >
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="text-base font-medium text-gray-800">
                            {{ user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            {{ user.email }}
                        </div>
                    </div>
                    <div class="ml-2">
                        <svg
                            class="h-5 w-5 text-gray-400 transition-transform duration-200"
                            :class="{ 'rotate-180': showUserMenu }"
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
                    </div>
                </div>

                <!-- Mobile Accordion Menu -->
                <div
                    v-if="showUserMenu"
                    class="overflow-hidden transition-all duration-200 ease-in-out"
                >
                    <div class="border-t border-gray-100 bg-white">
                        <Link
                            :href="route('profile.edit')"
                            class="flex w-full items-center px-4 py-3 text-base text-gray-700 hover:bg-gray-50 hover:text-emerald-600"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-3 h-5 w-5 text-gray-400"
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
                        </Link>
                        <button
                            @click="logout"
                            class="flex w-full items-center px-4 py-3 text-base text-gray-700 hover:bg-gray-50 hover:text-emerald-600"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mr-3 h-5 w-5 text-gray-400"
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
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    mosque: Object,
});

const form = useForm({
    title: '',
    description: '',
    category: '',
    start_date: '',
    end_date: '',
    start_time: '',
    end_time: '',
    location: '',
    address: '',
    is_online: false,
    online_url: '',
    registration_required: false,
    registration_deadline: '',
    max_participants: '',
    contact_person: '',
    contact_phone: '',
    contact_email: '',
    featured_image: null,
});

// Computed properties for validation errors
const hasBasicInfoErrors = computed(() => {
    return form.errors.title || form.errors.description || form.errors.category;
});

const hasDateTimeErrors = computed(() => {
    return (
        form.errors.start_date ||
        form.errors.end_date ||
        form.errors.start_time ||
        form.errors.end_time
    );
});

const hasLocationErrors = computed(() => {
    return (
        form.errors.location ||
        form.errors.address ||
        form.errors.is_online ||
        form.errors.online_url
    );
});

const hasRegistrationErrors = computed(() => {
    return (
        form.errors.registration_required ||
        form.errors.registration_deadline ||
        form.errors.max_participants
    );
});

const hasContactErrors = computed(() => {
    return (
        form.errors.contact_person ||
        form.errors.contact_phone ||
        form.errors.contact_email
    );
});

// Handle image preview
const imagePreview = ref(null);
const handleImageUpload = (e) => {
    const file = e.target.files[0];
    form.featured_image = file;

    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.value = null;
    }
};

// Form submission
const submit = () => {
    form.post(route('masjid.acara.store', props.mosque.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            imagePreview.value = null;
        },
    });
};
</script>

<template>
    <Head :title="`Tambah Acara Baru - ${mosque.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tambah Acara Baru - {{ mosque.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Validation errors summary if any -->
                        <div
                            v-if="Object.keys(form.errors).length > 0"
                            class="mb-6 rounded-xl bg-red-50 p-4 shadow-sm"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-5 w-5 text-red-400"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3
                                        class="text-sm font-medium text-red-800"
                                    >
                                        Terdapat
                                        {{ Object.keys(form.errors).length }}
                                        ralat yang perlu diperbaiki
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>
                                            Sila perbaiki ralat berikut sebelum
                                            menyimpan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-8">
                            <!-- Basic Information Section -->
                            <div
                                :class="{
                                    'rounded-xl bg-red-50 p-4':
                                        hasBasicInfoErrors,
                                }"
                            >
                                <h3
                                    class="text-lg font-medium"
                                    :class="
                                        hasBasicInfoErrors
                                            ? 'text-red-800'
                                            : 'text-gray-900'
                                    "
                                >
                                    Maklumat Asas
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Maklumat asas mengenai acara ini.
                                </p>

                                <div
                                    class="mt-6 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-6"
                                >
                                    <!-- Title -->
                                    <div
                                        class="sm:col-span-6"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.title,
                                        }"
                                    >
                                        <InputLabel
                                            for="title"
                                            value="Tajuk Acara"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.title,
                                            }"
                                        />
                                        <TextInput
                                            id="title"
                                            v-model="form.title"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.title,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.title"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- Description -->
                                    <div
                                        class="sm:col-span-6"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.description,
                                        }"
                                    >
                                        <InputLabel
                                            for="description"
                                            value="Penerangan"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.description,
                                            }"
                                        />
                                        <Textarea
                                            id="description"
                                            v-model="form.description"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.description,
                                            }"
                                            rows="4"
                                        />
                                        <InputError
                                            :message="form.errors.description"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- Category -->
                                    <div
                                        class="sm:col-span-3"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.category,
                                        }"
                                    >
                                        <InputLabel
                                            for="category"
                                            value="Kategori"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.category,
                                            }"
                                        />
                                        <select
                                            id="category"
                                            v-model="form.category"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.category,
                                            }"
                                        >
                                            <option value="">
                                                Pilih Kategori
                                            </option>
                                            <option value="ceramah">
                                                Ceramah
                                            </option>
                                            <option value="kelas">Kelas</option>
                                            <option value="seminar">
                                                Seminar
                                            </option>
                                            <option value="gotong-royong">
                                                Gotong-royong
                                            </option>
                                            <option value="kenduri">
                                                Kenduri
                                            </option>
                                            <option value="lain-lain">
                                                Lain-lain
                                            </option>
                                        </select>
                                        <InputError
                                            :message="form.errors.category"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- Featured Image -->
                                    <div
                                        class="sm:col-span-3"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.featured_image,
                                        }"
                                    >
                                        <InputLabel
                                            for="featured_image"
                                            value="Gambar Acara"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.featured_image,
                                            }"
                                        />
                                        <div class="mt-1 flex items-center">
                                            <div
                                                v-if="imagePreview"
                                                class="mr-4 h-32 w-32 overflow-hidden rounded-md"
                                            >
                                                <img
                                                    :src="imagePreview"
                                                    class="h-full w-full object-cover"
                                                />
                                            </div>
                                            <input
                                                type="file"
                                                id="featured_image"
                                                @change="handleImageUpload"
                                                accept="image/*"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100"
                                            />
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">
                                            Saiz maksimum: 2MB. Format: JPG,
                                            PNG, GIF.
                                        </p>
                                        <InputError
                                            :message="
                                                form.errors.featured_image
                                            "
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Date and Time Section -->
                            <div
                                :class="{
                                    'rounded-xl bg-red-50 p-4':
                                        hasDateTimeErrors,
                                }"
                            >
                                <h3
                                    class="text-lg font-medium"
                                    :class="
                                        hasDateTimeErrors
                                            ? 'text-red-800'
                                            : 'text-gray-900'
                                    "
                                >
                                    Tarikh dan Masa
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tetapkan tarikh dan masa acara ini.
                                </p>

                                <div
                                    class="mt-6 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-6"
                                >
                                    <!-- Start Date -->
                                    <div
                                        class="sm:col-span-3"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.start_date,
                                        }"
                                    >
                                        <InputLabel
                                            for="start_date"
                                            value="Tarikh Mula"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.start_date,
                                            }"
                                        />
                                        <TextInput
                                            id="start_date"
                                            v-model="form.start_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.start_date,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.start_date"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- End Date -->
                                    <div
                                        class="sm:col-span-3"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.end_date,
                                        }"
                                    >
                                        <InputLabel
                                            for="end_date"
                                            value="Tarikh Tamat"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.end_date,
                                            }"
                                        />
                                        <TextInput
                                            id="end_date"
                                            v-model="form.end_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.end_date,
                                            }"
                                            required
                                        />
                                        <InputError
                                            :message="form.errors.end_date"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- Start Time -->
                                    <div
                                        class="sm:col-span-3"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.start_time,
                                        }"
                                    >
                                        <InputLabel
                                            for="start_time"
                                            value="Masa Mula"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.start_time,
                                            }"
                                        />
                                        <TextInput
                                            id="start_time"
                                            v-model="form.start_time"
                                            type="time"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.start_time,
                                            }"
                                        />
                                        <InputError
                                            :message="form.errors.start_time"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- End Time -->
                                    <div
                                        class="sm:col-span-3"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.end_time,
                                        }"
                                    >
                                        <InputLabel
                                            for="end_time"
                                            value="Masa Tamat"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.end_time,
                                            }"
                                        />
                                        <TextInput
                                            id="end_time"
                                            v-model="form.end_time"
                                            type="time"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.end_time,
                                            }"
                                        />
                                        <InputError
                                            :message="form.errors.end_time"
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Location Section -->
                            <div
                                :class="{
                                    'rounded-xl bg-red-50 p-4':
                                        hasLocationErrors,
                                }"
                            >
                                <h3
                                    class="text-lg font-medium"
                                    :class="
                                        hasLocationErrors
                                            ? 'text-red-800'
                                            : 'text-gray-900'
                                    "
                                >
                                    Lokasi
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tetapkan lokasi acara ini.
                                </p>

                                <div
                                    class="mt-6 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-6"
                                >
                                    <!-- Location Name -->
                                    <div
                                        class="sm:col-span-6"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.location,
                                        }"
                                    >
                                        <InputLabel
                                            for="location"
                                            value="Nama Lokasi"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.location,
                                            }"
                                        />
                                        <TextInput
                                            id="location"
                                            v-model="form.location"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.location,
                                            }"
                                            placeholder="Contoh: Dewan Utama Masjid"
                                        />
                                        <InputError
                                            :message="form.errors.location"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- Address -->
                                    <div
                                        class="sm:col-span-6"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.address,
                                        }"
                                    >
                                        <InputLabel
                                            for="address"
                                            value="Alamat"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.address,
                                            }"
                                        />
                                        <Textarea
                                            id="address"
                                            v-model="form.address"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.address,
                                            }"
                                            rows="3"
                                            placeholder="Masukkan alamat lengkap lokasi acara"
                                        />
                                        <InputError
                                            :message="form.errors.address"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- Is Online -->
                                    <div
                                        class="sm:col-span-6"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.is_online,
                                        }"
                                    >
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <Checkbox
                                                    id="is_online"
                                                    v-model:checked="
                                                        form.is_online
                                                    "
                                                />
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <InputLabel
                                                    for="is_online"
                                                    value="Acara Dalam Talian"
                                                    :class="{
                                                        'text-red-500':
                                                            form.errors
                                                                .is_online,
                                                    }"
                                                />
                                                <p class="text-gray-500">
                                                    Tandakan jika acara ini akan
                                                    diadakan secara dalam
                                                    talian.
                                                </p>
                                                <InputError
                                                    :message="
                                                        form.errors.is_online
                                                    "
                                                    class="mt-2"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Online URL -->
                                    <div
                                        v-if="form.is_online"
                                        class="sm:col-span-6"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.online_url,
                                        }"
                                    >
                                        <InputLabel
                                            for="online_url"
                                            value="URL Acara Dalam Talian"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.online_url,
                                            }"
                                        />
                                        <TextInput
                                            id="online_url"
                                            v-model="form.online_url"
                                            type="url"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.online_url,
                                            }"
                                            placeholder="Contoh: https://zoom.us/j/123456789"
                                        />
                                        <InputError
                                            :message="form.errors.online_url"
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Registration Section -->
                            <div
                                :class="{
                                    'rounded-xl bg-red-50 p-4':
                                        hasRegistrationErrors,
                                }"
                            >
                                <h3
                                    class="text-lg font-medium"
                                    :class="
                                        hasRegistrationErrors
                                            ? 'text-red-800'
                                            : 'text-gray-900'
                                    "
                                >
                                    Pendaftaran
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tetapkan maklumat pendaftaran untuk acara
                                    ini.
                                </p>

                                <div
                                    class="mt-6 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-6"
                                >
                                    <!-- Registration Required -->
                                    <div
                                        class="sm:col-span-6"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors
                                                    .registration_required,
                                        }"
                                    >
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <Checkbox
                                                    id="registration_required"
                                                    v-model:checked="
                                                        form.registration_required
                                                    "
                                                />
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <InputLabel
                                                    for="registration_required"
                                                    value="Pendaftaran Diperlukan"
                                                    :class="{
                                                        'text-red-500':
                                                            form.errors
                                                                .registration_required,
                                                    }"
                                                />
                                                <p class="text-gray-500">
                                                    Tandakan jika peserta perlu
                                                    mendaftar untuk acara ini.
                                                </p>
                                                <InputError
                                                    :message="
                                                        form.errors
                                                            .registration_required
                                                    "
                                                    class="mt-2"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <template v-if="form.registration_required">
                                        <!-- Registration Deadline -->
                                        <div
                                            class="sm:col-span-3"
                                            :class="{
                                                'rounded-lg bg-red-50 p-4':
                                                    form.errors
                                                        .registration_deadline,
                                            }"
                                        >
                                            <InputLabel
                                                for="registration_deadline"
                                                value="Tarikh Tutup Pendaftaran"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors
                                                            .registration_deadline,
                                                }"
                                            />
                                            <TextInput
                                                id="registration_deadline"
                                                v-model="
                                                    form.registration_deadline
                                                "
                                                type="date"
                                                class="mt-1 block w-full"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors
                                                            .registration_deadline,
                                                }"
                                            />
                                            <InputError
                                                :message="
                                                    form.errors
                                                        .registration_deadline
                                                "
                                                class="mt-2"
                                            />
                                        </div>

                                        <!-- Max Participants -->
                                        <div
                                            class="sm:col-span-3"
                                            :class="{
                                                'rounded-lg bg-red-50 p-4':
                                                    form.errors
                                                        .max_participants,
                                            }"
                                        >
                                            <InputLabel
                                                for="max_participants"
                                                value="Bilangan Peserta Maksimum"
                                                :class="{
                                                    'text-red-500':
                                                        form.errors
                                                            .max_participants,
                                                }"
                                            />
                                            <TextInput
                                                id="max_participants"
                                                v-model="form.max_participants"
                                                type="number"
                                                min="1"
                                                class="mt-1 block w-full"
                                                :class="{
                                                    'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                        form.errors
                                                            .max_participants,
                                                }"
                                                placeholder="Biarkan kosong jika tiada had"
                                            />
                                            <InputError
                                                :message="
                                                    form.errors.max_participants
                                                "
                                                class="mt-2"
                                            />
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div
                                :class="{
                                    'rounded-xl bg-red-50 p-4':
                                        hasContactErrors,
                                }"
                            >
                                <h3
                                    class="text-lg font-medium"
                                    :class="
                                        hasContactErrors
                                            ? 'text-red-800'
                                            : 'text-gray-900'
                                    "
                                >
                                    Hubungi
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tetapkan Hubungi untuk acara ini.
                                </p>

                                <div
                                    class="mt-6 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-6"
                                >
                                    <!-- Contact Person -->
                                    <div
                                        class="sm:col-span-6"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.contact_person,
                                        }"
                                    >
                                        <InputLabel
                                            for="contact_person"
                                            value="Nama Pegawai Hubungan"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.contact_person,
                                            }"
                                        />
                                        <TextInput
                                            id="contact_person"
                                            v-model="form.contact_person"
                                            type="text"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.contact_person,
                                            }"
                                        />
                                        <InputError
                                            :message="
                                                form.errors.contact_person
                                            "
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- Contact Phone -->
                                    <div
                                        class="sm:col-span-3"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.contact_phone,
                                        }"
                                    >
                                        <InputLabel
                                            for="contact_phone"
                                            value="Nombor Telefon"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.contact_phone,
                                            }"
                                        />
                                        <TextInput
                                            id="contact_phone"
                                            v-model="form.contact_phone"
                                            type="tel"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.contact_phone,
                                            }"
                                        />
                                        <InputError
                                            :message="form.errors.contact_phone"
                                            class="mt-2"
                                        />
                                    </div>

                                    <!-- Contact Email -->
                                    <div
                                        class="sm:col-span-3"
                                        :class="{
                                            'rounded-lg bg-red-50 p-4':
                                                form.errors.contact_email,
                                        }"
                                    >
                                        <InputLabel
                                            for="contact_email"
                                            value="Emel"
                                            :class="{
                                                'text-red-500':
                                                    form.errors.contact_email,
                                            }"
                                        />
                                        <TextInput
                                            id="contact_email"
                                            v-model="form.contact_email"
                                            type="email"
                                            class="mt-1 block w-full"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500':
                                                    form.errors.contact_email,
                                            }"
                                        />
                                        <InputError
                                            :message="form.errors.contact_email"
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex justify-end space-x-3 pt-5">
                                <SecondaryButton
                                    :href="
                                        route('masjid.acara.index', mosque.id)
                                    "
                                    as="a"
                                >
                                    Batal
                                </SecondaryButton>
                                <PrimaryButton
                                    :disabled="form.processing"
                                    type="submit"
                                >
                                    {{
                                        form.processing
                                            ? 'Menyimpan...'
                                            : 'Simpan'
                                    }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

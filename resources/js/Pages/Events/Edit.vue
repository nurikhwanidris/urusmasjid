<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import Checkbox from '@/Components/Checkbox.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import QrcodeVue from 'qrcode-vue3';

const props = defineProps({
    mosque: Object,
    event: Object,
});

// Format date from ISO to YYYY-MM-DD
const formatDateForInput = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
};

const form = useForm({
    title: props.event.title,
    description: props.event.description,
    category: props.event.category,
    start_date: formatDateForInput(props.event.start_date),
    end_date: formatDateForInput(props.event.end_date),
    start_time: props.event.start_time,
    end_time: props.event.end_time,
    location: props.event.location,
    address: props.event.address,
    is_online: props.event.is_online,
    online_url: props.event.online_url || '',
    registration_required: props.event.registration_required,
    registration_deadline: formatDateForInput(
        props.event.registration_deadline,
    ),
    max_participants: props.event.max_participants || '',
    contact_person: props.event.contact_person || '',
    contact_phone: props.event.contact_phone || '',
    contact_email: props.event.contact_email || '',
    status: props.event.status || 'active',
    featured_image: null,
    _method: 'PUT',
});

// Computed properties for validation errors
const hasBasicInfoErrors = computed(() => {
    return (
        form.errors.title ||
        form.errors.description ||
        form.errors.category ||
        form.errors.status
    );
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
        form.errors.location || form.errors.address || form.errors.online_url
    );
});

const hasRegistrationErrors = computed(() => {
    return (
        form.errors.registration_deadline ||
        form.errors.max_participants ||
        form.errors.contact_person ||
        form.errors.contact_phone ||
        form.errors.contact_email
    );
});

const confirmingDeletion = ref(false);
const showQrCode = ref(false);

// Generate the registration URL for QR code
const registrationUrl = computed(() => {
    return (
        window.location.origin + route('events.public.register', props.event.id)
    );
});

const submit = () => {
    form.post(route('masjid.acara.update', [props.mosque.id, props.event.id]), {
        preserveScroll: true,
        onSuccess: () => {
            // Reset the form or redirect
        },
    });
};

const confirmDelete = () => {
    confirmingDeletion.value = true;
};

const deleteEvent = () => {
    router.delete(
        route('masjid.acara.destroy', [props.mosque.id, props.event.id]),
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

const toggleQrCode = () => {
    showQrCode.value = !showQrCode.value;
};
</script>

<template>
    <Head :title="`Edit Acara - ${event.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <Link
                        :href="
                            route('masjid.acara.show', [mosque.id, event.id])
                        "
                        class="inline-flex items-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-300 focus:border-gray-400 focus:outline-none focus:ring focus:ring-gray-200 active:bg-gray-400 disabled:opacity-25"
                    >
                        Kembali ke Acara
                    </Link>
                    <DangerButton @click="confirmDelete">
                        Padam Acara
                    </DangerButton>
                </div>
                <div v-if="event.registration_required">
                    <PrimaryButton @click="toggleQrCode">
                        {{
                            showQrCode
                                ? 'Tutup QR Code'
                                : 'Tunjuk QR Code Pendaftaran'
                        }}
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- QR Code Modal -->
                <div
                    v-if="showQrCode"
                    class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <h2 class="mb-4 text-xl font-semibold text-gray-900">
                            QR Code Pendaftaran
                        </h2>
                        <p class="mb-4 text-gray-600">
                            Sila imbas QR code ini untuk mendaftar ke acara ini.
                            Anda juga boleh kongsi URL di bawah.
                        </p>
                        <div class="mb-4 flex justify-center">
                            <div class="rounded-lg bg-white p-4 shadow-md">
                                <QrcodeVue
                                    :value="registrationUrl"
                                    :size="200"
                                    level="H"
                                />
                            </div>
                        </div>
                        <div class="mb-4">
                            <InputLabel value="URL Pendaftaran" />
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <TextInput
                                    type="text"
                                    class="block w-full"
                                    :value="registrationUrl"
                                    readonly
                                />
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500"
                                    @click="
                                        navigator.clipboard.writeText(
                                            registrationUrl,
                                        )
                                    "
                                >
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <!-- Basic Information Section -->
                            <div class="mb-6">
                                <h3
                                    class="mb-4 flex items-center text-lg font-medium text-gray-900"
                                    :class="{
                                        'text-red-600': hasBasicInfoErrors,
                                    }"
                                >
                                    Maklumat Asas
                                    <span
                                        v-if="hasBasicInfoErrors"
                                        class="ml-2 text-sm font-normal text-red-600"
                                    >
                                        (Sila perbaiki ralat di bawah)
                                    </span>
                                </h3>
                                <div class="space-y-4">
                                    <!-- Title -->
                                    <div>
                                        <InputLabel
                                            for="title"
                                            value="Tajuk Acara"
                                        />
                                        <TextInput
                                            id="title"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.title"
                                            required
                                            autofocus
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.title"
                                        />
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <InputLabel
                                            for="description"
                                            value="Penerangan"
                                        />
                                        <Textarea
                                            id="description"
                                            class="mt-1 block w-full"
                                            v-model="form.description"
                                            rows="4"
                                            required
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.description"
                                        />
                                    </div>

                                    <!-- Category -->
                                    <div>
                                        <InputLabel
                                            for="category"
                                            value="Kategori"
                                        />
                                        <select
                                            id="category"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                            v-model="form.category"
                                            required
                                        >
                                            <option value="">
                                                Pilih Kategori
                                            </option>
                                            <option value="religious">
                                                Keagamaan
                                            </option>
                                            <option value="educational">
                                                Pendidikan
                                            </option>
                                            <option value="community">
                                                Komuniti
                                            </option>
                                            <option value="charity">
                                                Kebajikan
                                            </option>
                                            <option value="other">
                                                Lain-lain
                                            </option>
                                        </select>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.category"
                                        />
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <InputLabel
                                            for="status"
                                            value="Status Acara"
                                        />
                                        <select
                                            id="status"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                            v-model="form.status"
                                            required
                                        >
                                            <option value="active">
                                                Aktif
                                            </option>
                                            <option value="cancelled">
                                                Dibatalkan
                                            </option>
                                            <option value="postponed">
                                                Ditangguhkan
                                            </option>
                                            <option value="completed">
                                                Selesai
                                            </option>
                                        </select>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.status"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Date and Time Section -->
                            <div class="mb-6">
                                <h3
                                    class="mb-4 flex items-center text-lg font-medium text-gray-900"
                                    :class="{
                                        'text-red-600': hasDateTimeErrors,
                                    }"
                                >
                                    Tarikh dan Masa
                                    <span
                                        v-if="hasDateTimeErrors"
                                        class="ml-2 text-sm font-normal text-red-600"
                                    >
                                        (Sila perbaiki ralat di bawah)
                                    </span>
                                </h3>
                                <div
                                    class="grid grid-cols-1 gap-4 md:grid-cols-2"
                                >
                                    <!-- Start Date -->
                                    <div>
                                        <InputLabel
                                            for="start_date"
                                            value="Tarikh Mula"
                                        />
                                        <TextInput
                                            id="start_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                            v-model="form.start_date"
                                            required
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.start_date"
                                        />
                                    </div>

                                    <!-- End Date -->
                                    <div>
                                        <InputLabel
                                            for="end_date"
                                            value="Tarikh Tamat"
                                        />
                                        <TextInput
                                            id="end_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                            v-model="form.end_date"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.end_date"
                                        />
                                    </div>

                                    <!-- Start Time -->
                                    <div>
                                        <InputLabel
                                            for="start_time"
                                            value="Masa Mula"
                                        />
                                        <TextInput
                                            id="start_time"
                                            type="time"
                                            class="mt-1 block w-full"
                                            v-model="form.start_time"
                                            required
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.start_time"
                                        />
                                    </div>

                                    <!-- End Time -->
                                    <div>
                                        <InputLabel
                                            for="end_time"
                                            value="Masa Tamat"
                                        />
                                        <TextInput
                                            id="end_time"
                                            type="time"
                                            class="mt-1 block w-full"
                                            v-model="form.end_time"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.end_time"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Location Section -->
                            <div class="mb-6">
                                <h3
                                    class="mb-4 flex items-center text-lg font-medium text-gray-900"
                                    :class="{
                                        'text-red-600': hasLocationErrors,
                                    }"
                                >
                                    Lokasi
                                    <span
                                        v-if="hasLocationErrors"
                                        class="ml-2 text-sm font-normal text-red-600"
                                    >
                                        (Sila perbaiki ralat di bawah)
                                    </span>
                                </h3>
                                <div class="space-y-4">
                                    <!-- Location Name -->
                                    <div>
                                        <InputLabel
                                            for="location"
                                            value="Nama Lokasi"
                                        />
                                        <TextInput
                                            id="location"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.location"
                                            required
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.location"
                                        />
                                    </div>

                                    <!-- Address -->
                                    <div>
                                        <InputLabel
                                            for="address"
                                            value="Alamat"
                                        />
                                        <Textarea
                                            id="address"
                                            class="mt-1 block w-full"
                                            v-model="form.address"
                                            rows="3"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.address"
                                        />
                                    </div>

                                    <!-- Online Event -->
                                    <div class="flex items-start">
                                        <div class="flex h-5 items-center">
                                            <Checkbox
                                                id="is_online"
                                                v-model:checked="form.is_online"
                                            />
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <InputLabel
                                                for="is_online"
                                                value="Acara Dalam Talian"
                                            />
                                        </div>
                                    </div>

                                    <!-- Online URL (conditional) -->
                                    <div v-if="form.is_online">
                                        <InputLabel
                                            for="online_url"
                                            value="URL Acara Dalam Talian"
                                        />
                                        <TextInput
                                            id="online_url"
                                            type="url"
                                            class="mt-1 block w-full"
                                            v-model="form.online_url"
                                            placeholder="https://meet.google.com/..."
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.online_url"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Registration Section -->
                            <div class="mb-6">
                                <h3
                                    class="mb-4 flex items-center text-lg font-medium text-gray-900"
                                    :class="{
                                        'text-red-600': hasRegistrationErrors,
                                    }"
                                >
                                    Pendaftaran
                                    <span
                                        v-if="hasRegistrationErrors"
                                        class="ml-2 text-sm font-normal text-red-600"
                                    >
                                        (Sila perbaiki ralat di bawah)
                                    </span>
                                </h3>
                                <div class="space-y-4">
                                    <!-- Registration Required -->
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
                                            />
                                        </div>
                                    </div>

                                    <div
                                        v-if="form.registration_required"
                                        class="space-y-4"
                                    >
                                        <!-- Registration Deadline -->
                                        <div>
                                            <InputLabel
                                                for="registration_deadline"
                                                value="Tarikh Tutup Pendaftaran"
                                            />
                                            <TextInput
                                                id="registration_deadline"
                                                type="date"
                                                class="mt-1 block w-full"
                                                v-model="
                                                    form.registration_deadline
                                                "
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors
                                                        .registration_deadline
                                                "
                                            />
                                        </div>

                                        <!-- Max Participants -->
                                        <div>
                                            <InputLabel
                                                for="max_participants"
                                                value="Bilangan Peserta Maksimum"
                                            />
                                            <TextInput
                                                id="max_participants"
                                                type="number"
                                                class="mt-1 block w-full"
                                                v-model="form.max_participants"
                                                min="1"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors.max_participants
                                                "
                                            />
                                        </div>

                                        <!-- Contact Person -->
                                        <div>
                                            <InputLabel
                                                for="contact_person"
                                                value="Nama Pegawai Perhubungan"
                                            />
                                            <TextInput
                                                id="contact_person"
                                                type="text"
                                                class="mt-1 block w-full"
                                                v-model="form.contact_person"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors.contact_person
                                                "
                                            />
                                        </div>

                                        <!-- Contact Phone -->
                                        <div>
                                            <InputLabel
                                                for="contact_phone"
                                                value="Nombor Telefon Perhubungan"
                                            />
                                            <TextInput
                                                id="contact_phone"
                                                type="text"
                                                class="mt-1 block w-full"
                                                v-model="form.contact_phone"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors.contact_phone
                                                "
                                            />
                                        </div>

                                        <!-- Contact Email -->
                                        <div>
                                            <InputLabel
                                                for="contact_email"
                                                value="Emel Perhubungan"
                                            />
                                            <TextInput
                                                id="contact_email"
                                                type="email"
                                                class="mt-1 block w-full"
                                                v-model="form.contact_email"
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="
                                                    form.errors.contact_email
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Image Section -->
                            <div class="mb-6">
                                <h3
                                    class="mb-4 text-lg font-medium text-gray-900"
                                >
                                    Gambar Acara
                                </h3>
                                <div class="space-y-4">
                                    <!-- Current Image (if exists) -->
                                    <div
                                        v-if="event.featured_image"
                                        class="mb-4"
                                    >
                                        <p class="mb-2 text-sm text-gray-600">
                                            Gambar Semasa:
                                        </p>
                                        <img
                                            :src="`/storage/${event.featured_image}`"
                                            :alt="event.title"
                                            class="h-40 w-auto rounded-md object-cover"
                                        />
                                    </div>

                                    <!-- Featured Image Upload -->
                                    <div>
                                        <InputLabel
                                            for="featured_image"
                                            value="Muat Naik Gambar Baru (Pilihan)"
                                        />
                                        <input
                                            id="featured_image"
                                            type="file"
                                            class="mt-1 block w-full"
                                            @input="
                                                form.featured_image =
                                                    $event.target.files[0]
                                            "
                                            accept="image/*"
                                        />
                                        <p class="mt-1 text-sm text-gray-500">
                                            Gambar yang dibenarkan: JPG, PNG,
                                            GIF. Saiz maksimum: 2MB.
                                        </p>
                                        <InputError
                                            class="mt-2"
                                            :message="
                                                form.errors.featured_image
                                            "
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex items-center justify-end">
                                <Link
                                    :href="
                                        route('masjid.acara.show', [
                                            mosque.id,
                                            event.id,
                                        ])
                                    "
                                    class="inline-flex items-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-300 focus:border-gray-400 focus:outline-none focus:ring focus:ring-gray-200 active:bg-gray-400 disabled:opacity-25"
                                >
                                    Batal
                                </Link>
                                <PrimaryButton
                                    class="ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Simpan Perubahan
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="confirmingDeletion" @close="cancelDelete">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Adakah anda pasti mahu memadamkan acara ini?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Tindakan ini tidak boleh dibatalkan. Semua maklumat
                    berkaitan acara ini akan dipadam secara kekal.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="cancelDelete">
                        Batal
                    </SecondaryButton>
                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteEvent"
                    >
                        Padam Acara
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

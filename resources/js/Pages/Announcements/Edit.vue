<script setup>
import { ref, onMounted } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Textarea from '@/Components/Textarea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    mosque: Object,
    announcement: Object,
});

// Parse dates for form initialization
const parseDateTime = (dateTimeString) => {
    if (!dateTimeString) return { date: '', time: '' };
    const date = new Date(dateTimeString);
    return {
        date: date.toISOString().split('T')[0],
        time: date.toTimeString().slice(0, 5),
    };
};

const publishedDateTime = parseDateTime(props.announcement.published_at);
const expiresDateTime = parseDateTime(props.announcement.expires_at);

const form = useForm({
    title: props.announcement.title,
    content: props.announcement.content,
    type: props.announcement.type,
    status: props.announcement.status,
    published_at_date: publishedDateTime.date,
    published_at_time: publishedDateTime.time,
    expires_at_date: expiresDateTime.date,
    expires_at_time: expiresDateTime.time,
    featured_image: null,
    _method: 'PUT',
});

const typeOptions = [
    { value: 'general', label: 'Umum' },
    { value: 'important', label: 'Penting' },
    { value: 'emergency', label: 'Kecemasan' },
];

const statusOptions = [
    { value: 'draft', label: 'Draf' },
    { value: 'published', label: 'Terbit' },
    { value: 'archived', label: 'Arkib' },
];

const showPublishedAt = ref(form.status === 'published');
const showExpiresAt = ref(!!props.announcement.expires_at);
const confirmingDeletion = ref(false);

const submit = () => {
    // Combine date and time fields before submitting
    const formData = { ...form };

    if (form.published_at_date && form.published_at_time) {
        formData.published_at = `${form.published_at_date}T${form.published_at_time}`;
    }

    if (showExpiresAt.value && form.expires_at_date && form.expires_at_time) {
        formData.expires_at = `${form.expires_at_date}T${form.expires_at_time}`;
    } else if (!showExpiresAt.value) {
        formData.expires_at = null;
    }

    // Remove the separate date and time fields
    delete formData.published_at_date;
    delete formData.published_at_time;
    delete formData.expires_at_date;
    delete formData.expires_at_time;

    formData.post(
        route('masjid.pengumuman.update', [
            props.mosque.id,
            props.announcement.id,
        ]),
        {
            preserveScroll: true,
        },
    );
};

const updateStatus = () => {
    showPublishedAt.value = form.status === 'published';
    if (form.status === 'published' && !form.published_at_date) {
        const now = new Date();
        form.published_at_date = now.toISOString().split('T')[0];
        form.published_at_time = now.toTimeString().slice(0, 5);
    }
};

const confirmDelete = () => {
    confirmingDeletion.value = true;
};

const deleteAnnouncement = () => {
    form._method = 'DELETE';
    form.post(
        route('masjid.pengumuman.destroy', [
            props.mosque.id,
            props.announcement.id,
        ]),
        {
            onSuccess: () => {
                confirmingDeletion.value = false;
            },
        },
    );
};

const closeModal = () => {
    confirmingDeletion.value = false;
};

onMounted(() => {
    updateStatus();
});
</script>

<template>
    <AuthenticatedLayout :title="`Edit Pengumuman - ${mosque.name}`">
        <Head :title="`Edit Pengumuman - ${mosque.name}`" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div
                            class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div>
                                <h2
                                    class="text-2xl font-semibold text-gray-900"
                                >
                                    Edit Pengumuman
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Kemaskini maklumat pengumuman.
                                </p>
                            </div>
                            <div class="mt-4 sm:mt-0">
                                <DangerButton @click="confirmDelete">
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
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                    Padam Pengumuman
                                </DangerButton>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Title -->
                            <div>
                                <InputLabel for="title" value="Tajuk" />
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

                            <!-- Content -->
                            <div>
                                <InputLabel for="content" value="Kandungan" />
                                <Textarea
                                    id="content"
                                    class="mt-1 block w-full"
                                    v-model="form.content"
                                    rows="6"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.content"
                                />
                            </div>

                            <!-- Type -->
                            <div>
                                <InputLabel
                                    for="type"
                                    value="Jenis Pengumuman"
                                />
                                <SelectInput
                                    id="type"
                                    class="mt-1 block w-full"
                                    v-model="form.type"
                                    :options="typeOptions"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.type"
                                />
                            </div>

                            <!-- Status -->
                            <div>
                                <InputLabel for="status" value="Status" />
                                <SelectInput
                                    id="status"
                                    class="mt-1 block w-full"
                                    v-model="form.status"
                                    :options="statusOptions"
                                    required
                                    @update:modelValue="updateStatus"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.status"
                                />
                            </div>

                            <!-- Published At -->
                            <div
                                v-if="showPublishedAt"
                                class="grid grid-cols-1 gap-4 md:grid-cols-2"
                            >
                                <div>
                                    <InputLabel
                                        for="published_at_date"
                                        value="Tarikh Terbit"
                                    />
                                    <TextInput
                                        id="published_at_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.published_at_date"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.published_at_date"
                                    />
                                </div>
                                <div>
                                    <InputLabel
                                        for="published_at_time"
                                        value="Masa Terbit"
                                    />
                                    <TextInput
                                        id="published_at_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="form.published_at_time"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.published_at_time"
                                    />
                                </div>
                            </div>

                            <!-- Expires At -->
                            <div>
                                <div class="mb-2 flex items-center">
                                    <input
                                        id="has_expiry"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                        v-model="showExpiresAt"
                                    />
                                    <label
                                        for="has_expiry"
                                        class="ml-2 block text-sm text-gray-900"
                                    >
                                        Tetapkan tarikh tamat
                                    </label>
                                </div>
                                <div
                                    v-if="showExpiresAt"
                                    class="grid grid-cols-1 gap-4 md:grid-cols-2"
                                >
                                    <div>
                                        <InputLabel
                                            for="expires_at_date"
                                            value="Tarikh Tamat"
                                        />
                                        <TextInput
                                            id="expires_at_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                            v-model="form.expires_at_date"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="
                                                form.errors.expires_at_date
                                            "
                                        />
                                    </div>
                                    <div>
                                        <InputLabel
                                            for="expires_at_time"
                                            value="Masa Tamat"
                                        />
                                        <TextInput
                                            id="expires_at_time"
                                            type="time"
                                            class="mt-1 block w-full"
                                            v-model="form.expires_at_time"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="
                                                form.errors.expires_at_time
                                            "
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Image -->
                            <div>
                                <InputLabel
                                    for="featured_image"
                                    value="Gambar Pengumuman (Pilihan)"
                                />
                                <div
                                    v-if="announcement.featured_image"
                                    class="mb-2"
                                >
                                    <p class="mb-2 text-sm text-gray-500">
                                        Gambar sedia ada:
                                    </p>
                                    <img
                                        :src="`/storage/${announcement.featured_image}`"
                                        :alt="announcement.title"
                                        class="h-32 w-32 rounded object-cover"
                                    />
                                </div>
                                <input
                                    id="featured_image"
                                    type="file"
                                    class="mt-3 block w-full"
                                    @input="
                                        form.featured_image =
                                            $event.target.files[0]
                                    "
                                    accept="image/*"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    Gambar yang dibenarkan: JPG, PNG, GIF. Saiz
                                    maksimum: 2MB. Biarkan kosong jika tidak
                                    mahu menukar gambar.
                                </p>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.featured_image"
                                />
                            </div>

                            <!-- Submit Buttons -->
                            <div
                                class="flex items-center justify-end space-x-3"
                            >
                                <Link
                                    :href="
                                        route('masjid.pengumuman.show', [
                                            mosque.id,
                                            announcement.id,
                                        ])
                                    "
                                    class="inline-flex items-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-300 focus:border-gray-400 focus:outline-none focus:ring focus:ring-gray-200 active:bg-gray-400 disabled:opacity-25"
                                >
                                    Batal
                                </Link>
                                <PrimaryButton
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
        <Modal :show="confirmingDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Adakah anda pasti mahu memadamkan pengumuman ini?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Tindakan ini tidak boleh dibatalkan. Semua maklumat
                    berkaitan pengumuman ini akan dipadam secara kekal.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="closeModal">
                        Batal
                    </SecondaryButton>
                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteAnnouncement"
                    >
                        Padam Pengumuman
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

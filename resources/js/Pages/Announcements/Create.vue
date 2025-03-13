<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Textarea from '@/Components/Textarea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';

const props = defineProps({
    mosque: Object,
});

const form = useForm({
    title: '',
    content: '',
    type: 'general',
    status: 'draft',
    published_at_date: '',
    published_at_time: '',
    expires_at_date: '',
    expires_at_time: '',
    featured_image: null,
});

const typeOptions = [
    { value: 'general', label: 'Umum' },
    { value: 'important', label: 'Penting' },
    { value: 'emergency', label: 'Kecemasan' },
];

const statusOptions = [
    { value: 'draft', label: 'Draf' },
    { value: 'published', label: 'Terbit' },
];

const showPublishedAt = ref(form.status === 'published');
const showExpiresAt = ref(false);

const submit = () => {
    // Combine date and time fields before submitting
    const formData = { ...form };

    if (form.published_at_date && form.published_at_time) {
        formData.published_at = `${form.published_at_date}T${form.published_at_time}`;
    }

    if (showExpiresAt.value && form.expires_at_date && form.expires_at_time) {
        formData.expires_at = `${form.expires_at_date}T${form.expires_at_time}`;
    }

    // Remove the separate date and time fields
    delete formData.published_at_date;
    delete formData.published_at_time;
    delete formData.expires_at_date;
    delete formData.expires_at_time;

    formData.post(route('masjid.pengumuman.store', props.mosque.id), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const updateStatus = () => {
    showPublishedAt.value = form.status === 'published';
    if (form.status === 'published' && !form.published_at_date) {
        const now = new Date();
        form.published_at_date = now.toISOString().split('T')[0];
        form.published_at_time = now.toTimeString().slice(0, 5);
    }
};
</script>

<template>
    <AuthenticatedLayout :title="`Cipta Pengumuman - ${mosque.name}`">
        <Head :title="`Cipta Pengumuman - ${mosque.name}`" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">
                                Cipta Pengumuman Baru
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Sila isi maklumat pengumuman yang ingin dicipta.
                            </p>
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
                                    Gambar yang dibenarkan: JPG, PNG, GIF. Saiz
                                    maksimum: 2MB.
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
                                <SecondaryButton
                                    type="button"
                                    :href="
                                        route(
                                            'masjid.pengumuman.index',
                                            mosque.id,
                                        )
                                    "
                                >
                                    Batal
                                </SecondaryButton>
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Simpan Pengumuman
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

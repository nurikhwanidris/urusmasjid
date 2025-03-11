<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';

const props = defineProps({
    mosque: Object,
    event: Object,
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    notes: '',
});

// Format date for display
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return date.toLocaleDateString('ms-MY', options);
};

// Form submission
const submit = () => {
    form.post(route('masjid.acara.pendaftaran.store', [props.mosque.id, props.event.id]), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Pendaftaran Acara - ${event.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Pendaftaran Acara: {{ event.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Event Summary -->
                        <div class="mb-8 rounded-lg bg-gray-50 p-4">
                            <h3 class="text-lg font-medium text-gray-900">Maklumat Acara</h3>
                            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tajuk</p>
                                    <p class="text-sm text-gray-900">{{ event.title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tarikh</p>
                                    <p class="text-sm text-gray-900">
                                        {{ formatDate(event.start_date) }}
                                        <span v-if="event.start_date !== event.end_date">
                                            - {{ formatDate(event.end_date) }}
                                        </span>
                                    </p>
                                </div>
                                <div v-if="event.location">
                                    <p class="text-sm font-medium text-gray-500">Lokasi</p>
                                    <p class="text-sm text-gray-900">{{ event.location }}</p>
                                </div>
                                <div v-if="event.max_participants">
                                    <p class="text-sm font-medium text-gray-500">Bilangan Peserta Maksimum</p>
                                    <p class="text-sm text-gray-900">{{ event.max_participants }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Form -->
                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="name" value="Nama" />
                                    <TextInput
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        autofocus
                                    />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="email" value="Emel" />
                                    <TextInput
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        class="mt-1 block w-full"
                                    />
                                    <InputError :message="form.errors.email" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="phone" value="Nombor Telefon" />
                                    <TextInput
                                        id="phone"
                                        v-model="form.phone"
                                        type="tel"
                                        class="mt-1 block w-full"
                                    />
                                    <InputError :message="form.errors.phone" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="notes" value="Catatan" />
                                    <Textarea
                                        id="notes"
                                        v-model="form.notes"
                                        class="mt-1 block w-full"
                                        rows="3"
                                        placeholder="Sebarang maklumat tambahan yang perlu diketahui oleh penganjur"
                                    />
                                    <InputError :message="form.errors.notes" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end space-x-3">
                                    <SecondaryButton :href="route('masjid.acara.show', [mosque.id, event.id])" as="a">
                                        Batal
                                    </SecondaryButton>
                                    <PrimaryButton :disabled="form.processing" type="submit">
                                        {{ form.processing ? 'Mendaftar...' : 'Daftar' }}
                                    </PrimaryButton>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

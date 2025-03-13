<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    event: Object,
    mosque: Object,
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    ic_number: '',
    address: '',
    join_kariah: true,
    notes: '',
});

const submit = () => {
    form.post(route('events.public.register.store', props.event.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Pendaftaran Acara - ${event.title}`" />

    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 text-center">
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ event.title }}
                            </h1>
                            <p class="mt-2 text-gray-600">
                                {{ mosque.name }}
                            </p>
                            <div
                                class="mt-4 flex flex-wrap justify-center gap-4 text-sm text-gray-600"
                            >
                                <div>
                                    <span class="font-semibold">Tarikh:</span>
                                    {{
                                        new Date(
                                            event.start_date,
                                        ).toLocaleDateString('ms-MY')
                                    }}
                                    <span
                                        v-if="
                                            event.end_date &&
                                            event.end_date !== event.start_date
                                        "
                                    >
                                        -
                                        {{
                                            new Date(
                                                event.end_date,
                                            ).toLocaleDateString('ms-MY')
                                        }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-semibold">Masa:</span>
                                    {{ event.start_time }}
                                    <span v-if="event.end_time">
                                        - {{ event.end_time }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-semibold">Lokasi:</span>
                                    {{ event.location }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="mb-6 rounded-lg bg-emerald-50 p-4 text-emerald-800"
                        >
                            <p class="text-center">
                                Sila isi borang pendaftaran di bawah untuk
                                mendaftar ke acara ini.
                            </p>
                        </div>

                        <form @submit.prevent="submit">
                            <div class="mb-6 space-y-4">
                                <!-- Name -->
                                <div>
                                    <InputLabel for="name" value="Nama Penuh" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        required
                                        autofocus
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.name"
                                    />
                                </div>

                                <!-- IC Number -->
                                <div>
                                    <InputLabel
                                        for="ic_number"
                                        value="Nombor IC"
                                    />
                                    <TextInput
                                        id="ic_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.ic_number"
                                        placeholder="e.g. 880101-01-1234"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.ic_number"
                                    />
                                </div>

                                <!-- Phone -->
                                <div>
                                    <InputLabel
                                        for="phone"
                                        value="Nombor Telefon"
                                    />
                                    <TextInput
                                        id="phone"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.phone"
                                        required
                                        placeholder="e.g. 012-3456789"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.phone"
                                    />
                                </div>

                                <!-- Email -->
                                <div>
                                    <InputLabel for="email" value="Emel" />
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="form.email"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.email"
                                    />
                                </div>

                                <!-- Address -->
                                <div>
                                    <InputLabel for="address" value="Alamat" />
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

                                <!-- Join as Kariah Member -->
                                <div class="flex items-start">
                                    <div class="flex h-5 items-center">
                                        <Checkbox
                                            id="join_kariah"
                                            v-model:checked="form.join_kariah"
                                        />
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <InputLabel
                                            for="join_kariah"
                                            value="Saya ingin mendaftar sebagai ahli kariah masjid ini"
                                        />
                                        <p class="mt-1 text-xs text-gray-500">
                                            Dengan mendaftar sebagai ahli
                                            kariah, anda akan menerima maklumat
                                            terkini tentang aktiviti masjid.
                                        </p>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <InputLabel
                                        for="notes"
                                        value="Catatan (jika ada)"
                                    />
                                    <Textarea
                                        id="notes"
                                        class="mt-1 block w-full"
                                        v-model="form.notes"
                                        rows="3"
                                        placeholder="Sebarang maklumat tambahan..."
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.notes"
                                    />
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <PrimaryButton
                                    class="ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Daftar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

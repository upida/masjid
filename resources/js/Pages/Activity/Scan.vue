<script setup>
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import moment from 'moment';
import { onMounted, ref } from 'vue';
import { QrcodeStream } from 'vue-qrcode-reader';

const props = defineProps({
    activity: {
        type: Object,
        required: true,
    },
});

const snackbar = ref({
    active: false,
    message: '',
});

function remove() {
    if (confirm("Are you sure you want to delete this activity?")) {
        router.delete(
            route('activity.destroy', {activity: props.activity.id}),
            (response) => {
                console.log(response);
            },
            (error) => {
                console.log(error);
            }
        );
    }
}

function back() {
    router.get(route('activity.show', {activity: props.activity.id}));
}

function onDetect(code) {
    const join = useForm({
        code: code[0].rawValue
    });

    join.post(
        route('activity.member.join', {activity: props.activity.id}),
        {
            preserveScroll: true,
            onSuccess: (response) => {
                snackbar.value = {
                    active: true,
                    message: usePage().props.flash.message,
                };
                join.reset();
            },
            onError: (error) => {
                snackbar.value = {
                    active: true,
                    type: 'error',
                    message: error.message,
                };
            },
        }
    )

}

</script>

<template>
    <Head :title="activity.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-5">
                <div class="flex flex-row justify-between">
                    <div
                        class="flex flex-col justify-center"
                    >
                        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ activity.title }}</h2>
                        <small v-if="activity.description" class="text-sm text-gray-600">{{ activity.description }}</small>
                    </div>
                    <div class="flex flex-row gap-2">
                        <v-btn :href="route('activity.edit', { activity: activity.id })" size="x-small" icon="mdi-pencil" color="teal" variant="outlined" class="btn-create inline-flex items-center justify-center"></v-btn>
                        <v-btn @click="remove" size="x-small" icon="mdi-delete" color="error" variant="outlined" class="btn-create inline-flex items-center justify-center"></v-btn>
                    </div>
                </div>
                <div class="grid gap-5 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <v-chip variant="outlined" color="teal-lighten-3">
                        <v-icon icon="mdi-clock" class="text-teal-lighten-3 mr-2"></v-icon>
                        {{ moment(activity.start_time).format('ddd') }}, {{ moment(activity.start_time).format('YYYY-MM-DD') }} {{ moment(activity.start_time).format('HH:mm') }} - {{ activity.end_time ? moment(activity.end_time).format('HH:mm') : 'TBA' }}
                    </v-chip>
                    <v-chip variant="outlined" color="teal-lighten-3">
                        <v-icon icon="mdi-account" class="text-teal-lighten-3 mr-2"></v-icon>
                        Members: {{ activity.members.length ?? '0' }}
                    </v-chip>
                </div>
            </div>
        </template>

        <v-snackbar
            v-model="snackbar.active"
            :color="snackbar.type ?? 'teal'"
            rounded="pill"
        >
            {{ snackbar.message }}

            <template v-slot:actions>
                <v-btn
                color="white"
                variant="text"
                icon="mdi-close"
                @click="snackbar.active = false"
                >
                </v-btn>
            </template>
        </v-snackbar>

        <v-card
            color="teal"
            elevation="2"
            :title="`QR scanner to join ${activity.title}`"
            class="py-12"
        >
            <template #prepend>
                <v-icon
                    @click="back()"
                    class="cursor-pointer"
                >
                    mdi-arrow-left
                </v-icon>
            </template>
        </v-card>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <QrcodeStream @detect="onDetect" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

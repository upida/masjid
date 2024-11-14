<script setup>
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import moment from 'moment';
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
    activity: {
        type: Object,
        required: true,
    },
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

const users = ref([]);
const snackbar = ref({
    active: false,
    message: '',
});

function searchUsers(search) {
    axios.get(route('profile.search'), {
        params: {
            q: search,
        },
    }).then((response) => {
        users.value = response.data.users;
    }).catch((error) => {
        console.log(error);
    });
}

function status(member) {
    // status present and member.created_at <= activity.start_time , status is "in time"
    // status present and member.created_at <= activity.end_time + 10 min , status is "on time"
    // status present and member.created_at > activity.end_time + 10 min , status is "late about {minutes} min"
    // status permit , status is "permit"
    var start_time = moment(props.activity.start_time).format('YYYY-MM-DD HH:mm');
    var after_10_min = moment(start_time).add(10, 'minutes');
    var member_time = moment(member.created_at).format('YYYY-MM-DD HH:mm');
    console.log('props.activity.start_time', start_time);
    console.log('after_10_min',moment(member_time).format('YYYY-MM-DD HH:mm:ss'), after_10_min.format('YYYY-MM-DD HH:mm:ss'));
    if (member.status === 'present') {
        if (moment(member_time).isSameOrBefore(start_time)) {
            return 'In time';
        } else if (moment(member_time).isSameOrBefore(after_10_min)) {
            return 'On time';
        } else {
            return 'Late about ' + (moment(member_time).diff(after_10_min, 'minutes')) + ' min';
        }
    } else if (member.status === 'permit') {
        return 'Permit';
    }
}

const join = useForm({
    user_id: null,
});

watch(() => join.user_id, (newValue, oldValue) => {
    if (newValue) {
        join.post(
            route('activity.member.join', {activity: props.activity.id}),
            {
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
});

function leave(id) {
    if (confirm("Are you sure you want to leave this activity?")) {
        var leave = useForm({
            user_id: id,
        });
        leave.post(
            route('activity.member.leave', {activity: props.activity.id}),
            {
                onSuccess: (response) => {
                    snackbar.value = {
                        active: true,
                        type: 'warning',
                        message: usePage().props.flash.message,
                    };
                    leave.reset();
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
}

onMounted(() => {
    if (usePage().props.flash.message) {
        snackbar.value = {
            active: true,
            type: 'success',
            message: usePage().props.flash.message,
        }
    }
});

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

        <div class="pt-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="flex flex-row p-6 text-gray-900">
                        <v-autocomplete
                            v-model="join.user_id"
                            @update:search="searchUsers($event)"
                            :items="users"
                            item-title="name"
                            item-value="id"
                            placeholder="Select a user"
                            density="comfortable"
                            theme="light"
                            variant="outlined"
                            rounded
                            menu-icon=""
                            base-color="teal"
                            color="teal"
                        >
                            <template v-slot:append>
                                <v-btn :href="route('activity.scan', { activity: activity.id })" size="x-small" icon="mdi-qrcode-scan" color="teal" variant="outlined" class="btn-create inline-flex items-center justify-center"></v-btn>
                            </template>
                        </v-autocomplete>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <v-list v-for="(member, index) in activity.members" :key="member.id">
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title>{{ member.user.name }}</v-list-item-title>
                                    <v-list-item-subtitle>{{ moment(member.created_at).format('HH:mm') }} - {{ status(member) }}</v-list-item-subtitle>
                                </v-list-item-content>
                                <template v-slot:append>
                                    <v-btn @click="leave(member.user_id)" size="small" icon="mdi-logout" color="error" variant="text" class="btn-create inline-flex items-center justify-center"></v-btn>
                                </template>
                            </v-list-item>
                            <v-divider v-if="index < activity.members.length - 1" class="border-opacity-100"></v-divider>
                        </v-list>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

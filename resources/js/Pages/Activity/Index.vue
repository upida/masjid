<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    activities: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Head title="Activity" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2
                    class="inline-flex items-center justify-center text-xl font-semibold leading-tight text-gray-800"
                >
                    Activity
                </h2>
                <v-btn :href="route('activity.create')" size="x-small" icon="mdi-plus" class="btn-create inline-flex items-center justify-center"></v-btn>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <v-list lines="three">
                            <template v-for="(activity, index) in activities" :key="activity.id">
                                <v-list-item :href="route('activity.show', {activity: activity.id})">
                                    <v-list-item-content>
                                        <v-list-item-title>{{ activity.title }}</v-list-item-title>
                                        <v-list-item-subtitle>{{ activity.start_time }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                    <template v-slot:append>
                                        <v-chip variant="text">
                                            <v-icon icon="mdi-account" class="text-teal-600 mr-2"></v-icon>
                                            {{ activity.members_count }} Members
                                        </v-chip>
                                    </template>
                                </v-list-item>
                                <v-divider v-if="index < activities.length - 1" class="border-opacity-100"></v-divider>
                            </template>
                        </v-list>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.v-btn.btn-create {
    background-color: rgb(13 148 136 / var(--tw-bg-opacity)) !important;
    color: rgb(255 255 255) !important;
}
</style>
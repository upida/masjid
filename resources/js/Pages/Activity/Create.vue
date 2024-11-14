<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FormCustom from '@/Components/FormCustom.vue';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';

defineProps({
});

const fields = [
    {
        key: 'title',
        title: 'Title',
        type: 'text',
        required: true,
        value: null,
    },
    {
        key: 'description',
        title: 'Description',
        type: 'text',
        required: false,
        value: null,
    },
    {
        key: 'start_time',
        title: 'Start time',
        type: 'datetime',
        required: true,
        value: null,
    },
    {
        key: 'end_time',
        title: 'End time',
        type: 'datetime',
        required: false,
        value: null,
    },
];

function formatDateTime(date) {
    date = moment(date);
    return date.format('YYYY-MM-DD HH:mm:ss');
}

function submit(form) {
    for(const field of fields) {
        const key = field.key;
        if (!form[key]) delete form[key];
        if (form[key] && ['start_time', 'end_time'].includes(key)) {
            form[key] = formatDateTime(form[key]);
        }
    }
    console.log(form);
    form.post(
        route('activity.store'),
        (response) => {
            console.log(response);
        },
        (error) => {
            console.log(error);
        }
    );
}
</script>

<template>
    <Head title="Create a new activity" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Create a new activity
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <FormCustom
                        type="create"
                        title="Create a new activity"
                        :fields="fields"
                        @submit="submit"
                        class="max-w-xl"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

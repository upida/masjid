<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FormCustom from '@/Components/FormCustom.vue';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';

const props = defineProps({
    activity: {
        type: Object,
        required: true,
    },
});

const fields = [
    {
        key: 'title',
        title: 'Title',
        type: 'text',
        required: true,
        value: props.activity.title ?? null,
    },
    {
        key: 'description',
        title: 'Description',
        type: 'text',
        required: false,
        value: props.activity.description ?? null,
    },
    {
        key: 'start_time',
        title: 'Start time',
        type: 'datetime',
        required: true,
        value: props.activity.start_time ?? null,
    },
    {
        key: 'end_time',
        title: 'End time',
        type: 'datetime',
        required: false,
        value: props.activity.end_time ?? null,
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
    form.patch(
        route('activity.update', {activity: props.activity.id}),
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
    <Head :title="'Edit ' + activity.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Edit {{ activity.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <FormCustom
                        type="update"
                        title="Update an activity"
                        :fields="fields"
                        @submit="submit"
                        class="max-w-xl"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

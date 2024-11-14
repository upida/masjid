<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
    },
    fields: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['submit']);

var data = {}

for (const field of props.fields) {
    if (field.value) data[field.key] = field.value;
    else data[field.key] = "";
}

const form = useForm(data);


function submit() {
    emit('submit', form);
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ title }}
            </h2>

            <p v-if="description" class="mt-1 text-sm text-gray-600">
                {{ description }}
            </p>
        </header>

        <form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
        >
            <template v-for="field in fields">
                <div v-if="field.type === 'text'">
                    <InputLabel :for="field.key" :value="field.title" />
    
                    <TextInput
                        :id="field.key"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form[field.key]"
                        :required="field.required"
                    />
    
                    <InputError class="mt-2" :message="form.errors[field.key]" />
                </div>

                <div v-if="field.type === 'datetime'">
                    <InputLabel :for="field.key" :value="field.title" />
    
                    <input
                        :id="field.key"
                        type="datetime-local"
                        class="mt-1 block w-full"
                        v-model="form[field.key]"
                        :required="field.required"
                    />
    
                    <InputError class="mt-2" :message="form.errors[field.key]" />
                </div>
            </template>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{ type === 'create' ? 'Save' : 'Update' }}</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        {{ type === 'create' ? 'Saved.' : 'Updated.' }}
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

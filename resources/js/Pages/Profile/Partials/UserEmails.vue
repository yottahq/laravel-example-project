<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import DangerButton from "@/Components/DangerButton.vue";
import UserEmailItem from "@/Components/UserEmailItem.vue";

const user = usePage().props.value.auth.user;
const userEmails = usePage().props.value.emails;
const allowUnverifiedAsPrimary = usePage().props.value.allowUnverifiedAsPrimary;

const form = useForm({
    email: null,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Email Addresses</h2>
        </header>

        <div class="mt-6 gap-2 flex flex-col">
            <UserEmailItem
                :email="user.email"
                is-primary
                :is-verified="user.email_verified_at"
            />

            <UserEmailItem
                v-for="email in userEmails"
                :email="email.email"
                :is-verified="email.email_verified_at"
                :allow-unverified-as-primary="allowUnverifiedAsPrimary"
            />
        </div>

        <hr class="h-1 bg-slate-50 mt-6">

        <p class="font-bold">Add secondary email</p>
        <form @submit.prevent="form.post(route('user-emails.store'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    placeholder="new-email@test.com"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

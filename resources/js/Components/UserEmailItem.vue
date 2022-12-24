<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from "@/Components/DangerButton.vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    email: String,
    isPrimary: Boolean,
    isVerified: Boolean,
    allowUnverifiedAsPrimary: Boolean,
});

function destroy(email) {
    if (confirm("Are you sure?")) {
        Inertia.delete(route('user-emails.destroy', email));
    }
}

function setAsPrimary(email) {
    Inertia.post(route('user-emails.set-as-primary', {
        email: email,
    }));
}

function resendVerification(email) {
    Inertia.post(route('user-emails.resend-verification', {
        email: email,
    }));
}
</script>

<template>
    <div class="flex justify-between bg-slate-100 p-4 items-center">
        <div class="email">
            {{ props.email }}

            <span v-if="props.isPrimary" class="rounded-full bg-green-200 text-sm px-3 py-1 border border-green-300 ml-2" >Primary</span>
            <span v-if="!props.isVerified" class="rounded-full bg-amber-200 text-sm px-3 py-1 border border-amber-300 ml-2" >Unverified</span>
        </div>
        <div class="actions flex items-center gap-4">
            <PrimaryButton v-if="!props.isVerified" @click="resendVerification(props.email)">Resend verification</PrimaryButton>

            <PrimaryButton v-if="!props.isPrimary && props.allowUnverifiedAsPrimary" @click="setAsPrimary(props.email)">Set as Primary</PrimaryButton>

            <DangerButton v-if="!props.isPrimary" @click="destroy(props.email)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </DangerButton>
        </div>
    </div>
</template>

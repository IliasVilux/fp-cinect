<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Content } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

interface Props {
    token: string;
    email: string;
    content: Content;
}

const props = defineProps<Props>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <AuthLayout :title="t('auth.resetPassword.base.title')" :description="t('auth.resetPassword.base.description')" :content="content">
        <Head :title="t('auth.resetPassword.base.titleShort')" />

        <form @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">{{ t('auth.resetPassword.form.email.label') }}</Label>
                    <Input id="email" type="email" name="email" autocomplete="email" v-model="form.email" class="mt-1 block w-full" readonly />
                    <InputError :message="form.errors.email" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">{{ t('auth.resetPassword.form.password.label') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        v-model="form.password"
                        class="mt-1 block w-full"
                        autofocus
                        :placeholder="t('auth.resetPassword.form.password.placeholder')"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">{{ t('auth.resetPassword.form.confirmPassword.label') }}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        class="mt-1 block w-full"
                        :placeholder="t('auth.resetPassword.form.confirmPassword.placeholder')"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-4 w-full" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ t('auth.resetPassword.form.submit') }}
                </Button>
            </div>
        </form>
    </AuthLayout>
</template>

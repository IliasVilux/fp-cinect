<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSplitLayout.vue';
import { Content } from '@/types/models';

defineProps<{
    title?: string;
    description?: string;
    content: Content;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description" :content="content">
        <slot />
    </AuthLayout>
</template>

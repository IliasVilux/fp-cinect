<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import ContentCarousel from '@/components/ContentCarousel.vue';
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Content } from '@/types/models';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    content: Content;
}>();
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout layout="header">
        {{ content }}
        <AppFooter />
    </AppLayout>
</template>

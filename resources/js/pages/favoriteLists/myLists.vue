<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { FavoriteList } from '@/types/models';
import { useI18n } from 'vue-i18n';
import { BreadcrumbItem } from '@/types';
import AppFooter from '@/components/AppFooter.vue';

const { t, locale, messages } = useI18n();

defineProps<{
  lists: FavoriteList[];
}>();

const breadcrumbs: BreadcrumbItem[] = messages.value[locale.value]['favoriteLists'].breadcrumbs.myLists as BreadcrumbItem[];
</script>

<template>
    <Head :title="t('dashboard.head.title')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <AppFooter />
    </AppLayout>
</template>

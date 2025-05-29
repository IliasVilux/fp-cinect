<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    layout?: 'sidebar' | 'header';
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    layout: 'sidebar',
});
</script>

<template>
    <AppSidebarLayout v-if="layout === 'sidebar'" :breadcrumbs="breadcrumbs">
        <slot />
    </AppSidebarLayout>
    <AppHeaderLayout v-else :breadcrumbs="breadcrumbs">
        <slot />
    </AppHeaderLayout>
</template>

<script setup lang="ts">
import AppContent from '@/components/app/AppContent.vue';
import AppFooter from '@/components/app/AppFooter.vue';
import AppShell from '@/components/app/AppShell.vue';
import AppSidebar from '@/components/app/AppSidebar.vue';
import AppSidebarHeader from '@/components/app/AppSidebarHeader.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />

            <div class="flex-1 flex flex-col w-full">
                <slot />
            </div>

            <AppFooter />
        </AppContent>
    </AppShell>
</template>

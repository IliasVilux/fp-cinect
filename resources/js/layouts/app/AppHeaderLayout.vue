<script setup lang="ts">
import AppContent from '@/components/app/AppContent.vue';
import AppFooter from '@/components/app/AppFooter.vue';
import AppHeader from '@/components/app/AppHeader.vue';
import AppShell from '@/components/app/AppShell.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell class="flex-col">
        <AppHeader :breadcrumbs="breadcrumbs" />

        <AppContent class="flex-1 flex flex-col">
            <div class="w-full">
                <slot />
            </div>
        </AppContent>

        <AppFooter />
    </AppShell>
</template>

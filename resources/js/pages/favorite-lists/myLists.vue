<script setup lang="ts">
import AppFooter from '@/components/app/AppFooter.vue';
import FavoriteListCard from '@/components/favoriteLists/favoriteListCard.vue';
import FavoriteListDialog from '@/components/favoriteLists/favoriteListDialog.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import type { FavoriteList } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();

defineProps<{
    lists: FavoriteList[];
}>();

const breadcrumbs: BreadcrumbItem[] = messages.value[locale.value]['favoriteLists'].breadcrumbs.myLists as BreadcrumbItem[];
</script>

<template>
    <Head :title="t('favoriteLists.head.myListsTitle')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <FavoriteListDialog />

        <section>
            <div v-for="list in lists" :key="list.id">
                <FavoriteListCard :list="list" />
            </div>
        </section>
        <AppFooter />
    </AppLayout>
</template>

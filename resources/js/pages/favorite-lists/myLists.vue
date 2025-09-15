<script setup lang="ts">
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
        <div class="m-5 mb-20 flex flex-col gap-5 lg:mx-11">
            <FavoriteListDialog />

            <section class="grid grid-cols-1 gap-2 lg:grid-cols-2">
                <div v-for="list in lists" :key="list.id">
                    <FavoriteListCard :list="list" />
                </div>
            </section>
        </div>
    </AppLayout>
</template>

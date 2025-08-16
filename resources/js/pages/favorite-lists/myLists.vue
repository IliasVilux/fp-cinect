<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { FavoriteList } from '@/types/models';
import { useI18n } from 'vue-i18n';
import { BreadcrumbItem } from '@/types';
import AppFooter from '@/components/AppFooter.vue';
import FavoriteListDialog from '@/components/favoriteLists/favoriteListDialog.vue';
import ContentCard from '@/components/ContentCard.vue';

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
                    <p>{{ list.name }}</p>
                    <p>{{ list.description }}</p>
                    <ContentCard v-for="content in list.contents" :key="content.id" :content="content" />
                </div>
            </section>
        <AppFooter />
    </AppLayout>
</template>

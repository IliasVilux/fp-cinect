<script setup lang="ts">
import AppFooter from '@/components/app/AppFooter.vue';
import AppPagination from '@/components/app/AppPagination.vue';
import ContentCard from '@/components/common/ContentCard.vue';
import ExploreFilters from '@/components/explore/ExploreFilters.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, SelectItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();

const props = defineProps<{
    genresItems: SelectItem[];
    contents: any;
    filters: {
        orderBy: string | null;
        contentType: string | null;
        genreId: number | null;
        searchContent: string | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = messages.value[locale.value]['explore'].breadcrumbs as BreadcrumbItem[];
const orderItems: SelectItem[] = messages.value[locale.value]['explore'].filters.order as SelectItem[];
const contentTypesItems: SelectItem[] = messages.value[locale.value]['explore'].filters.contentTypes as SelectItem[];

const orderBy = ref<string | null>(props.filters.orderBy);
const contentType = ref<string | null>(props.filters.contentType);
const genreId = ref<number | null>(props.filters.genreId);
const searchContent = ref<string>(props.filters.searchContent ?? '');
watch([orderBy, contentType, genreId, searchContent], ([order, contentType, genre, search]) => {
    router.visit(route('contents.explore'), {
        method: 'get',
        data: {
            ...(order ? { orderBy: order } : {}),
            ...(contentType ? { contentType: contentType } : {}),
            ...(genre ? { genreId: genre } : {}),
            ...(search ? { searchContent: search } : {}),
        },
        preserveScroll: true,
        preserveState: true,
    });
});
</script>

<template>
    <Head :title="t('explore.head.title')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <ExploreFilters
            v-model:order-by="orderBy"
            v-model:content-type="contentType"
            v-model:genre-id="genreId"
            v-model:search-content="searchContent"
            :genresItems="genresItems"
            :orderItems="orderItems"
            :contentTypesItems="contentTypesItems"
        />

        <!-- CONTENT GRID -->
        <section class="grid grid-cols-2 gap-2 lg:grid-cols-4 xl:grid-cols-6 mx-5 lg:mx-11 mb-4">
            <ContentCard
                v-for="content in contents.data"
                :key="content.id"
                :content="content"
                class="group relative aspect-[5/7] overflow-hidden rounded-md transition duration-200 hover:scale-102 hover:border-indigo-600 hover:bg-[#120e28]"
            />
        </section>

        <AppPagination v-if="contents.total > 0" :contents="contents" class="mb-20" />

        <AppFooter />
    </AppLayout>
</template>

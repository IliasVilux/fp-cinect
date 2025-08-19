<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import ContentCarousel from '@/components/ContentCarousel.vue';
import FeaturedHero from '@/components/dashboard-type/FeaturedHero.vue';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Genre, Content } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();

const props = defineProps<{
    featuredContent: Content;
    contentType: string;
    trendingContents: Content[];
    contentsGroupedByGenre: {
        genre: Genre;
        contents: Content[];
    }[];
    topTen: Content[];
}>();

const breadcrumbs: BreadcrumbItem[] = messages.value[locale.value]['dashboard-content'].breadcrumbs[props.contentType] as BreadcrumbItem[];

const hoveredItemId = ref<string | null>(null);
const setHoveredItem = (id: string) => {
    hoveredItemId.value = id;
};
const clearHoveredItem = () => {
    hoveredItemId.value = null;
};
</script>

<template>
    <Head :title="`${contentType} Dashboard`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <FeaturedHero :featuredContent="featuredContent" />

        <!-- TRENDING -->
        <section class="z-10 mb-6 lg:-mt-40">
            <h2 class="text-xl font-semibold tracking-tight">{{ t('dashboard-content.trending') }}</h2>
            <ContentCarousel
                :contents="trendingContents"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- TOP 10 -->
        <section class="my-6">
            <h2 class="text-xl font-semibold tracking-tight">{{ t('dashboard-content.topTen') }}</h2>
            <ContentCarousel
                :contents="topTen"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- FOR YOU -->
        <section class="my-6">
            <h2 class="text-xl font-semibold tracking-tight">{{ t('dashboard-content.forYou') }}</h2>
            <div class="grid grid-cols-2 gap-2 px-2 md:grid-cols-4 lg:grid-cols-6 lg:px-4">
                <div v-for="(item, index) in 6" :key="index" class="border-sidebar-border/70 relative aspect-[5/8] overflow-hidden rounded-xl border">
                    <PlaceholderPattern />
                </div>
            </div>
            <!-- <ContentCarousel :contents="trendingContents" :hoveredItemId="hoveredItemId" :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem" /> -->
        </section>

        <!-- GENRE CAROUSEL -->
        <section v-for="(group, index) in contentsGroupedByGenre" :key="index" class="my-6">
            <h2 class="text-xl font-semibold tracking-tight">{{ group.genre.name }}</h2>
            <ContentCarousel
                :contents="group.contents"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <AppFooter />
    </AppLayout>
</template>

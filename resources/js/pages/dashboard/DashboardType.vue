<script setup lang="ts">
import AppFooter from '@/components/app/AppFooter.vue';
import ContentCarousel from '@/components/common/ContentCarousel.vue';
import Heading from '@/components/common/Heading.vue';
import FeaturedHero from '@/components/dashboard-type/FeaturedHero.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Content, Genre } from '@/types/models';
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
        <section class="z-10 mb-9 -mt-12 lg:-mt-8">
            <Heading :title="t('dashboard-content.trending')" class="ml-5 lg:ml-11" />
            <ContentCarousel
                :contents="trendingContents"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- TOP 10 -->
        <section class="mb-8">
            <Heading :title="t('dashboard-content.topTen')" class="ml-5 lg:ml-11" />
            <ContentCarousel
                :contents="topTen"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- FOR YOU -->
        <section class="mb-8">
            <Heading :title="t('dashboard-content.forYou')" class="ml-5 lg:ml-11" />

            <!-- ! TEMORAL ⚠️ -->
            <ContentCarousel
                :contents="topTen"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
            <!-- <ContentCarousel :contents="trendingContents" :hoveredItemId="hoveredItemId" :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem" /> -->
        </section>

        <!-- GENRE CAROUSEL -->
        <section v-for="(group, index) in contentsGroupedByGenre" :key="index" :class="index !== contentsGroupedByGenre.length - 1 ? 'mb-8' : 'mb-20'">
            <Heading :title="group.genre.name" class="ml-5 lg:ml-11" />
            <ContentCarousel
                :contents="group.contents"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>
    </AppLayout>
</template>

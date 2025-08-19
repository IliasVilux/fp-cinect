<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import ContentCarousel from '@/components/ContentCarousel.vue';
import TypeSelector from '@/components/dashboard/TypeSelector.vue';
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Content } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();
const breadcrumbs: BreadcrumbItem[] = messages.value[locale.value]['dashboard'].breadcrumbs as BreadcrumbItem[];

defineProps<{
    cardsbuttonContent: Content[];
    recentContents: Content[];
    series: Content[];
    movies: Content[];
    animes: Content[];
    topTen: Content[];
}>();

const hoveredImage = ref<string | null>(null);
const setHoveredImage = (img: string) => {
    hoveredImage.value = img;
};
const clearHoveredImage = () => {
    hoveredImage.value = null;
};

const hoveredItemId = ref<string | null>(null);
const setHoveredItem = (id: string) => {
    hoveredItemId.value = id;
};
const clearHoveredItem = () => {
    hoveredItemId.value = null;
};
</script>

<template>
    <Head :title="t('dashboard.head.title')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <TypeSelector
            :items="cardsbuttonContent"
            :hoveredImage="hoveredImage"
            @setHoveredImage="setHoveredImage"
            @clearHoveredImage="clearHoveredImage"
        />

        <!-- RECENT CONTENTS -->
        <section class="mt-20 mb-6">
            <h2 class="text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.addedRecently') }}</h2>
            <ContentCarousel
                :contents="recentContents"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- TOP 10 GLOBAL -->
        <section class="my-6">
            <h2 class="text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.topTen') }}</h2>
            <ContentCarousel
                :contents="topTen"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- TV SHOWS -->
        <section class="my-6">
            <div class="flex items-baseline justify-between">
                <h2 class="text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.tvShows') }}</h2>
                <TextLink :href="route('dashboard.showType', 'movie')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
            </div>
            <ContentCarousel
                :contents="series"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- MOVIES -->
        <section class="my-6">
            <div class="flex items-baseline justify-between">
                <h2 class="text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.movies') }}</h2>
                <TextLink :href="route('dashboard.showType', 'series')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
            </div>
            <ContentCarousel
                :contents="movies"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- ANIMES -->
        <section class="my-6">
            <div class="flex items-baseline justify-between">
                <h2 class="text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.animes') }}</h2>
                <TextLink :href="route('dashboard.showType', 'anime')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
            </div>
            <ContentCarousel
                :contents="animes"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>
        <AppFooter />
    </AppLayout>
</template>

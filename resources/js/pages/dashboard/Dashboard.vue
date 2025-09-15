<script setup lang="ts">
import TextLink from '@/components/base/TextLink.vue';
import ContentCarousel from '@/components/common/ContentCarousel.vue';
import Heading from '@/components/common/Heading.vue';
import TypeSelector from '@/components/dashboard/TypeSelector.vue';
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
        <section class="mt-20 mb-8">
            <Heading :title="t('dashboard.sections.carousel.addedRecently')" class="ml-5 lg:ml-11" />
            <ContentCarousel
                :contents="recentContents"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- TOP 10 GLOBAL -->
        <section class="mb-8">
            <Heading :title="t('dashboard.sections.carousel.topTen')" class="ml-5 lg:ml-11" />
            <ContentCarousel
                :contents="topTen"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- TV SHOWS -->
        <section class="mb-8">
            <div class="mx-5 flex items-baseline justify-between lg:mx-11">
                <Heading :title="t('dashboard.sections.carousel.tvShows')" />
                <TextLink :href="route('dashboard.type', 'movie')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
            </div>
            <ContentCarousel
                :contents="series"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- MOVIES -->
        <section class="mb-8">
            <div class="mx-5 flex items-baseline justify-between lg:mx-11">
                <Heading :title="t('dashboard.sections.carousel.movies')" />
                <TextLink :href="route('dashboard.type', 'series')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
            </div>
            <ContentCarousel
                :contents="movies"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <!-- ANIMES -->
        <section class="mb-20">
            <div class="mx-5 flex items-baseline justify-between lg:mx-11">
                <Heading :title="t('dashboard.sections.carousel.animes')" />
                <TextLink :href="route('dashboard.type', 'anime')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
            </div>
            <ContentCarousel
                :contents="animes"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>
    </AppLayout>
</template>

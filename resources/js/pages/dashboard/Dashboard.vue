<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import ContentCarousel from '@/components/ContentCarousel.vue';
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Content } from '@/types/models';
import { Head, Link  } from '@inertiajs/vue3';
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
        <div class="flex h-full flex-1 flex-col gap-10 rounded-xl">
            <!-- CATEGORY SELECTOR -->
            <div class="relative mb-7 grid auto-rows-min grid-cols-3 gap-2 p-2 lg:mb-10 lg:gap-4 lg:p-4">
                <Link
                    v-for="content in cardsbuttonContent"
                    :key="content.id"
                    :href="route('dashboard.category', content.type)"
                    class="border-sidebar-border/70 group relative z-10 aspect-[9/16] cursor-pointer overflow-hidden rounded-xl border transition-colors duration-300 hover:border-indigo-600 hover:shadow-2xl lg:aspect-video"
                    @mouseover="setHoveredImage(content.cover_image || '/images/welcome/hero-background.webp')"
                    @mouseleave="clearHoveredImage"
                >
                    <!-- Gradient -->
                    <div class="absolute bottom-0 z-10 h-1/3 w-full bg-gradient-to-t from-neutral-950/100 to-neutral-950/0"></div>

                    <img
                        :src="content.cover_image || '/images/welcome/hero-background.webp'"
                        alt="Interestellar"
                        class="absolute inset-0 z-0 h-full w-full object-cover transition duration-500 group-hover:scale-110"
                    />

                    <h3 class="absolute bottom-2 left-3 z-20 text-2xl font-medium capitalize lg:text-xl lg:font-bold">{{ content.type }}</h3>
                </Link>

                <!-- Hover background image -->
                <img
                    :src="hoveredImage || '/images/welcome/hero-background.webp'"
                    alt="Interestellar"
                    class="absolute inset-0 w-full mask-b-from-0% mask-b-to-100% object-cover opacity-0 transition-opacity duration-1000 lg:mask-b-to-50%"
                    :class="{ 'opacity-50': hoveredImage }"
                />
            </div>

            <!-- RECENT CONTENTS -->
            <section class="z-10">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.addedRecently') }}</h2>
                </div>
                <ContentCarousel
                    :contents="recentContents"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>

            <!-- TOP 10 GLOBAL -->
            <section class="z-10">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.topTen') }}</h2>
                </div>
                <ContentCarousel
                    :contents="topTen"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>

            <!-- TV SHOWS -->
            <section class="z-10">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.tvShows') }}</h2>
                    <TextLink :href="route('dashboard.category', 'movie')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
                </div>
                <ContentCarousel
                    :contents="series"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>

            <!-- MOVIES -->
            <section class="z-10">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.movies') }}</h2>
                    <TextLink :href="route('dashboard.category', 'series')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
                </div>
                <ContentCarousel
                    :contents="movies"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>

            <!-- ANIMES -->
            <section class="z-10">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">{{ t('dashboard.sections.carousel.animes') }}</h2>
                    <TextLink :href="route('dashboard.category', 'anime')">{{ t('dashboard.sections.carousel.watchMore') }}</TextLink>
                </div>
                <ContentCarousel
                    :contents="animes"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>
            <AppFooter />
        </div>
    </AppLayout>
</template>

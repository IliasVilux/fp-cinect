<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import ContentCarousel from '@/components/ContentCarousel.vue';
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Content } from '@/types/models';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps<{
    cardsbuttonContent: Content[];
    series: Content[];
    movies: Content[];
    animes: Content[];
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
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-10 rounded-xl">
            <div class="relative mb-7 grid auto-rows-min grid-cols-3 gap-2 lg:gap-4 p-2 lg:p-4 lg:mb-10">
                <Link
                    v-for="content in cardsbuttonContent"
                    :key="content.id"
                    :href="route('home')"
                    class="border-sidebar-border/70 group relative z-10 aspect-[9/16] cursor-pointer overflow-hidden rounded-xl border transition-colors duration-300 hover:border-indigo-600 hover:shadow-2xl lg:aspect-video"
                    @mouseover="setHoveredImage(content.cover_image || '/images/welcome/hero-background.webp')"
                    @mouseleave="clearHoveredImage"
                >
                    <div class="absolute bottom-0 z-10 h-1/3 w-full bg-gradient-to-t from-neutral-950/100 to-neutral-950/0"></div>
                    <img
                        src="/images/welcome/hero-background.webp"
                        alt="Interestellar background image"
                        class="absolute inset-0 z-0 h-full w-full object-cover transition duration-500 group-hover:scale-110"
                    />
                    <h3 class="absolute bottom-2 left-3 z-20 text-2xl font-medium capitalize lg:text-xl lg:font-bold">{{ content.type }}</h3>
                </Link>

                <img
                    src="/images/welcome/hero-background.webp"
                    alt="Interestellar background image"
                    class="absolute inset-0 w-full mask-b-from-0% mask-b-to-100% object-cover opacity-0 transition-opacity duration-1000 lg:mask-b-to-50%"
                    :class="{ 'opacity-50': hoveredImage }"
                />
            </div>

            <section class="z-10">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">Series</h2>
                    <TextLink :href="route('dashboard.category', 'movie')">Ver más</TextLink>
                </div>
                <ContentCarousel
                    :contents="series"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>

            <section class="z-10">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">Movies</h2>
                    <TextLink :href="route('dashboard.category', 'series')">Ver más</TextLink>
                </div>
                <ContentCarousel
                    :contents="movies"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>

            <section class="z-10">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">Animes</h2>
                    <TextLink :href="route('dashboard.category', 'anime')">Ver más</TextLink>
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

<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import ContentCarousel from '@/components/ContentCarousel.vue';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Category, Content } from '@/types/models';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Series',
        href: '/dashboard',
    },
];

defineProps<{
  featuredContent: Content;
  contentType: string;
  trendingContents: Content[];
  contentsGroupedByCategory: {
    category: Category;
    contents: Content[];
  }[];
}>();

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
        <div class="flex h-full flex-1 flex-col gap-10 rounded-xl">
            <section class="relative w-full aspect-[9/16] md:aspect-video">
                <img :src="featuredContent.cover_image || '/images/welcome/hero-background.webp'" alt="Hero Image"
                    class="absolute inset-0 z-0 size-full object-cover mask-b-from-15% lg:mask-b-from-55% mask-b-to-85% lg:mask-b-to-100% lg:mask-l-from-30% lg:mask-l-to-95%" />

                <div class="absolute inset-0 z-10 flex flex-col justify-end lg:justify-center px-2 lg:px-4 lg:-mt-40">
                    <h1 class="text-3xl lg:text-6xl font-bold mt-2 lg:mt-4 max-w-xl">{{ featuredContent.title }}</h1>
                    <p class="text-lg font-light my-3 lg:mt-10 lg:mb-5">{{ featuredContent.description }}</p>
                    <Link :href="route('login')"
                        class="w-fit text-neutral-900 rounded-full bg-neutral-50 hover:bg-neutral-200 px-5 py-1.5 leading-normal">
                    Ver más
                    </Link>
                </div>

                <span class="absolute z-10 bg-black/50 right-0 top-20 uppercase text-xs font-bold px-6 py-2 border-l-4 border-white">{{ featuredContent.category.name }}</span>
            </section>

            <section class="z-10 lg:-mt-40">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">Tendencias</h2>
                </div>
                <ContentCarousel :contents="trendingContents" :hoveredItemId="hoveredItemId" :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem" />
            </section>

            <section>
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">Para tí</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2 px-2 lg:px-4">
                    <div v-for="(item, index) in 6" :key="index" class="relative aspect-[5/8] overflow-hidden rounded-xl border border-sidebar-border/70">
                        <PlaceholderPattern />
                    </div>
                </div>
                <!-- <ContentCarousel :contents="trendingContents" :hoveredItemId="hoveredItemId" :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem" /> -->
            </section>

            <section v-for="(group, index) in contentsGroupedByCategory" :key="index">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">{{ group.category.name }}</h2>
                </div>
                <ContentCarousel :contents="group.contents" :hoveredItemId="hoveredItemId" :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem" />
            </section>
            <AppFooter />
        </div>
    </AppLayout>
</template>

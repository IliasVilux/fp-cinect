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
            <section class="relative aspect-[9/16] w-full md:aspect-video">
                <img
                    :src="featuredContent.cover_image || '/images/welcome/hero-background.webp'"
                    alt="Hero Image"
                    class="absolute inset-0 z-0 size-full mask-b-from-15% mask-b-to-85% object-cover lg:mask-b-from-55% lg:mask-b-to-100% lg:mask-l-from-30% lg:mask-l-to-95%"
                />

                <div class="absolute inset-0 z-10 flex flex-col justify-end px-2 lg:-mt-40 lg:justify-center lg:px-4">
                    <h1 class="mt-2 max-w-xl text-3xl font-bold lg:mt-4 lg:text-6xl">{{ featuredContent.title }}</h1>
                    <p class="my-3 text-lg font-light lg:mt-10 lg:mb-5">{{ featuredContent.description }}</p>
                    <Link
                        :href="route('login')"
                        class="w-fit rounded-full bg-neutral-50 px-5 py-1.5 leading-normal text-neutral-900 hover:bg-neutral-200"
                    >
                        Ver más
                    </Link>
                </div>

                <span class="absolute top-20 right-0 z-10 border-l-4 border-white bg-black/50 px-6 py-2 text-xs font-bold uppercase">{{
                    featuredContent.category.name
                }}</span>
            </section>

            <section class="z-10 lg:-mt-40">
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">Tendencias</h2>
                </div>
                <ContentCarousel
                    :contents="trendingContents"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>

            <section>
                <div class="mb-2 flex items-baseline justify-between px-2 lg:px-4">
                    <h2 class="z-10 text-xl font-semibold tracking-tight">Para tí</h2>
                </div>
                <div class="grid grid-cols-2 gap-2 px-2 md:grid-cols-4 lg:grid-cols-6 lg:px-4">
                    <div
                        v-for="(item, index) in 6"
                        :key="index"
                        class="border-sidebar-border/70 relative aspect-[5/8] overflow-hidden rounded-xl border"
                    >
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
                <ContentCarousel
                    :contents="group.contents"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                />
            </section>
            <AppFooter />
        </div>
    </AppLayout>
</template>

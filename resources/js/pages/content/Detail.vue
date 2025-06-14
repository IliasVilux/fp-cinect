<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import ContentCarousel from '@/components/ContentCarousel.vue';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { Separator } from '@/components/ui/separator';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import { Content, Season } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    content: Content;
    relatedContents: Content[];
}>();

const selectedSeason = ref<Season | null>(null);

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
    <AppLayout layout="header">
        <section class="w-full max-w-6xl flex flex-col gap-4 md:flex-row md:gap-10 mx-auto md:mt-20">
            <div class="w-full md:max-w-96">
                <div class="aspect-[6/8] w-full">
                    <img src="/images/welcome/hero-background.webp" :alt="content.title" class="size-full md:rounded-lg object-cover mask-b-from-80% mask-b-to-100% md:mask-none" />
                </div>
            </div>

            <div class="w-full -mt-12 md:-mt-0 px-2 lg:px-0 z-10">
                <h2 class="mb-2 text-xl font-bold uppercase md:text-3xl">{{ content.title }}</h2>
                <div class="mb-8 flex h-5 items-center space-x-2 text-sm text-neutral-400">
                    <p v-if="content.release_year">{{ content.release_year }}</p>
                    <Separator v-if="content.release_year" orientation="vertical" />
                    <p v-if="content.type == 'movie'">{{ content.duration }}</p>
                    <p v-else>{{ content.seasons.length > 0 ? content.seasons.length : 0 }} seasons</p>
                    <Separator orientation="vertical" />
                    <p>{{ content.category.name }}</p>
                </div>

                <Tabs class="w-full" defaultValue="overview" as="section">
                    <TabsList>
                        <TabsTrigger value="overview">Overview</TabsTrigger>
                        <TabsTrigger v-if="content.type != 'movie' && content.seasons.length > 0" value="seasons"> Seasons</TabsTrigger>
                        <TabsTrigger value="trailer">Trailer</TabsTrigger>
                    </TabsList>

                    <Separator orientation="horizontal" class="mb-4" />

                    <TabsContent value="overview">
                        <p>{{ content.description }}</p>
                    </TabsContent>

                    <TabsContent value="seasons">
                        <Carousel :opts="{ align: 'center' }" v-slot="{ canScrollPrev, canScrollNext }" class="relative w-full mb-4">
                            <CarouselContent>
                                <CarouselItem
                                    v-for="season in content.seasons"
                                    :key="season.id"
                                    @click="selectedSeason = selectedSeason?.id === season.id ? null : season"
                                    class="group basis-1/2 md:basis-1/4 pl-2 cursor-pointer"
                                >
                                    <Card
                                        class="flex h-full min-h-20 justify-end rounded-lg px-3 py-1 transition duration-200 group-hover:border-indigo-600 group-hover:bg-[#120e28]"
                                        :class="{
                                            'border-indigo-600': selectedSeason && selectedSeason?.id === season.id,
                                        }"
                                    >
                                        <CardContent class="p-0">
                                            <p class="text-xs font-light text-neutral-300">S{{ season.season_number }}</p>
                                            <p class="text-sm">{{ season.title || `Season ${season.season_number}` }}</p>
                                        </CardContent>
                                    </Card>
                                </CarouselItem>
                            </CarouselContent>

                            <CarouselNext v-if="canScrollNext" class="z-20" />
                            <CarouselPrevious v-if="canScrollPrev" class="z-20" />
                        </Carousel>

                        <section class="grid grid-cols-1 gap-2 md:grid-cols-3">
                            <div v-for="episode in selectedSeason?.episodes" :key="episode.id">
                                <div>
                                    <p class="text-xs font-light text-neutral-300">Ep{{ episode.episode_number }}</p>
                                    <p>{{ episode.title }}</p>
                                    <p class="text-end text-xs text-neutral-400 md:text-start">
                                        Duration: {{ episode.duration ? `${episode.duration} mins` : 'N/S' }}
                                    </p>
                                </div>
                                <Separator orientation="horizontal" class="my-2 md:hidden" />
                            </div>
                        </section>
                    </TabsContent>
                    <TabsContent value="trailer">
                        <p>Trailer</p>
                    </TabsContent>
                </Tabs>
            </div>
        </section>

        <section class="w-full max-w-6xl mx-auto mt-20 border-sidebar-border/70 relative h-56 rounded-xl border px-2 lg:px-0">
            <PlaceholderPattern />
        </section>

        <section v-if="relatedContents.length > 0" class="w-full max-w-6xl mx-auto mt-20">
            <div class="mb-2 flex items-baseline justify-between px-2 lg:px-0">
                <h2 class="z-10 text-xl font-semibold tracking-tight">More like this</h2>
            </div>
            <ContentCarousel
                :contents="relatedContents"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
                :isDetailPage="true"
                class="px-2 lg:px-0"
            />
        </section>

        <AppFooter />
    </AppLayout>
</template>

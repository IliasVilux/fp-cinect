<script setup lang="ts">
import AppFooter from '@/components/app/AppFooter.vue';
import HeadingLarge from '@/components/common/HeadingLarge.vue';
import RatingReviewDialog from '@/components/detail/RatingReviewDialog.vue';
import ReviewList from '@/components/detail/ReviewList.vue';
import FavoriteListSelect from '@/components/favoriteLists/favoriteListSelect.vue';
import Separator from '@/components/ui/separator/Separator.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion"
import AppLayout from '@/layouts/AppLayout.vue';
import { Content, FavoriteList, Review } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { useMediaQuery } from '@vueuse/core';
import { useI18n } from 'vue-i18n';
import EpisodeList from '@/components/detail/EpisodeList.vue';
import ContentCarousel from '@/components/common/ContentCarousel.vue';
import { ref } from 'vue';

const { t } = useI18n();

defineProps<{
    content: Content & {
        ratings_avg_rating?: string;
        user_rating?: { rating: number };
        user_review?: { review_text: string };
        reviews?: Review[];
    };
    relatedContents: Content[];
    favoriteLists: (FavoriteList & { has_content: boolean })[];
}>();

const isMobile = useMediaQuery('(max-width: 768px)');

const hoveredItemId = ref<string | null>(null);
const setHoveredItem = (id: string) => {
    hoveredItemId.value = id;
};
const clearHoveredItem = () => {
    hoveredItemId.value = null;
};
</script>

<template>
    <Head :title="content.title" />
    <AppLayout layout="header">
        <img src="/images/welcome/hero-background.webp" alt="Cinect" class="absolute inset-0 z-0 h-[600px] w-full mask-b-from-25% object-cover" />

        <div class="mx-5 mt-36 lg:mt-56">
            <section
                class="border-border relative z-10 w-full max-w-6xl rounded-lg border bg-gradient-to-bl from-black to-neutral-950/10 p-5 backdrop-blur-lg lg:mx-auto lg:bg-gradient-to-br lg:p-11"
            >
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between md:gap-5">
                    <HeadingLarge :title="content.title" />

                    <div class="mb-3 flex h-4 items-center justify-center space-x-2 text-sm text-neutral-300 md:hidden">
                        <p v-if="content.release_year">{{ content.release_year }}</p>
                        <Separator v-if="content.release_year" orientation="vertical" />
                        <p v-if="content.type == 'movie'">{{ content.duration }}</p>
                        <p v-else>{{ content.seasons.length > 0 ? content.seasons.length : 0 }} {{ t('detail.seasons', content.seasons.length) }}</p>
                        <Separator orientation="vertical" />
                        <p>{{ content.genre.name }}</p>
                    </div>

                    <div class="mb-3 flex space-x-2 self-end lg:mb-0 lg:self-center">
                        <FavoriteListSelect :lists="favoriteLists" :contentId="content.id" />
                        <RatingReviewDialog :content="content" />
                    </div>
                </div>

                <div class="mt-2 mb-5 hidden h-4 items-center justify-start space-x-2 text-sm text-neutral-300 md:flex">
                    <p v-if="content.release_year">{{ content.release_year }}</p>
                    <Separator v-if="content.release_year" orientation="vertical" />
                    <p v-if="content.type == 'movie'">{{ content.duration }}</p>
                    <p v-else>{{ content.seasons.length > 0 ? content.seasons.length : 0 }} {{ t('detail.seasons', content.seasons.length) }}</p>
                    <Separator orientation="vertical" />
                    <p>{{ content.genre.name }}</p>
                </div>

                <Separator orientation="horizontal" class="mb-0 bg-border flex lg:hidden" />

                <Tabs default-value="overview" class="w-full flex flex-col mt-4" :orientation="isMobile ? 'vertical' : 'horizontal'" as="section">
                    <TabsList :class="isMobile ? 'grid w-full grid-cols-1 gap-2' : ''">
                        <TabsTrigger value="overview">{{ t('detail.overview') }}</TabsTrigger>
                        <TabsTrigger v-if="content.type != 'movie' && content.seasons.length > 0" value="seasons">{{
                            t('detail.seasons')
                        }}</TabsTrigger>
                        <TabsTrigger value="trailer">{{ t('detail.trailer') }}</TabsTrigger>
                        <TabsTrigger value="related">Relacionado / Related</TabsTrigger>
                    </TabsList>

                    <Separator orientation="horizontal" class="mb-0 bg-border hidden lg:flex" />

                    <TabsContent value="overview">
                        <p>{{ content.description }}</p>
                    </TabsContent>

                    <TabsContent value="seasons">
                        <Accordion type="single" class="w-full" collapsible default-value="1">
                            <AccordionItem v-for="(season, index) in content.seasons" :key="season.id" :value="season.id.toString()">
                            <AccordionTrigger :class="index == 0 ? 'mb-2' : 'my-2'">Season {{ index }}</AccordionTrigger>
                            <AccordionContent>
                                <EpisodeList :selectedSeason="season" />
                            </AccordionContent>
                            </AccordionItem>
                        </Accordion>
                    </TabsContent>


                    <TabsContent value="trailer">
                        <iframe
                            class="size-full aspect-video rounded-lg mt-2"
                            :src="`https://www.youtube.com/embed/RYI-WG_HFV8`"
                            title="Trailer de {{ content.title }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy"
                        ></iframe>
                    </TabsContent>

                    <TabsContent value="related">
                        <ContentCarousel
                            :contents="relatedContents"
                            :hoveredItemId="hoveredItemId"
                            :setHoveredItem="setHoveredItem"
                            :clearHoveredItem="clearHoveredItem"
                        />
                    </TabsContent>
                </Tabs>
            </section>
        </div>

        <ReviewList :reviews="content.reviews ?? null" class="mx-auto my-20 max-w-6xl" />
    </AppLayout>
</template>

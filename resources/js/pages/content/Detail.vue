<script setup lang="ts">
import AppFooter from '@/components/app/AppFooter.vue';
import ContentCarousel from '@/components/common/ContentCarousel.vue';
import EpisodeList from '@/components/detail/EpisodeList.vue';
import RatingReviewDialog from '@/components/detail/RatingReviewDialog.vue';
import ReviewList from '@/components/detail/ReviewList.vue';
import SeasonCarousel from '@/components/detail/SeasonCarousel.vue';
import FavoriteListSelect from '@/components/favoriteLists/favoriteListSelect.vue';
import { Separator } from '@/components/ui/separator';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import { Content, FavoriteList, Review, Season } from '@/types/models';
import { Head } from '@inertiajs/vue3';

import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

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
    <Head :title="content.title" />
    <AppLayout layout="header">
        <!-- CONTENT INFORMATION -->
        <section class="flex flex-col gap-0 md:flex-row md:gap-0">
            <!-- Poster image -->
            <div class="aspect-[6/8] w-full md:max-w-96">
                <img
                    src="/images/welcome/hero-background.webp"
                    :alt="content.title"
                    class="size-full mask-b-from-80% mask-b-to-100% object-cover md:rounded-lg md:mask-none"
                />
            </div>

            <!-- Tabs -->
            <div class="z-10 -mt-12 w-full md:mt-0">
                <div>
                    <!-- Title and Rating -->
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold uppercase md:text-3xl">{{ content.title }}</h2>
                        <div class="flex items-center space-x-2">
                            <FavoriteListSelect :lists="favoriteLists" :contentId="content.id" />
                            <RatingReviewDialog :content="content" />
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex h-5 items-center space-x-2 text-sm text-neutral-400">
                        <p v-if="content.release_year">{{ content.release_year }}</p>
                        <Separator v-if="content.release_year" orientation="vertical" />
                        <p v-if="content.type == 'movie'">{{ content.duration }}</p>
                        <p v-else>{{ content.seasons.length > 0 ? content.seasons.length : 0 }} {{ t('detail.seasons', content.seasons.length) }}</p>
                        <Separator orientation="vertical" />
                        <p>{{ content.genre.name }}</p>
                    </div>
                </div>

                <Tabs class="w-full" defaultValue="overview" as="section">
                    <TabsList>
                        <TabsTrigger value="overview">{{ t('detail.overview') }}</TabsTrigger>
                        <TabsTrigger v-if="content.type != 'movie' && content.seasons.length > 0" value="seasons">{{
                            t('detail.seasons')
                        }}</TabsTrigger>
                        <TabsTrigger value="trailer">{{ t('detail.trailer') }}</TabsTrigger>
                    </TabsList>

                    <Separator orientation="horizontal" class="mb-0" />

                    <TabsContent value="overview">
                        <p>{{ content.description }}</p>
                    </TabsContent>

                    <TabsContent value="seasons">
                        <SeasonCarousel :content="content" v-model:selectedSeason="selectedSeason" />

                        <EpisodeList :selectedSeason="selectedSeason" />
                    </TabsContent>
                    <TabsContent value="trailer">
                        <p>Trailer</p>
                    </TabsContent>
                </Tabs>
            </div>
        </section>

        <ReviewList :reviews="content.reviews ?? null" />

        <!-- RELATED CONTENTS -->
        <section v-if="relatedContents.length > 0" class="my-6">
            <h2 class="text-xl font-semibold tracking-tight">{{ t('detail.moreLikeThis') }}</h2>
            <ContentCarousel
                :contents="relatedContents"
                :hoveredItemId="hoveredItemId"
                :setHoveredItem="setHoveredItem"
                :clearHoveredItem="clearHoveredItem"
            />
        </section>

        <AppFooter />
    </AppLayout>
</template>

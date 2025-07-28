<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import ContentCarousel from '@/components/ContentCarousel.vue';
import InputError from '@/components/InputError.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Textarea } from '@/components/ui/textarea';
import { getInitials } from '@/composables/useInitials';
import AppLayout from '@/layouts/AppLayout.vue';
import { cn } from '@/lib/utils';
import type { SharedData, User } from '@/types';
import { Content, Review, Season } from '@/types/models';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/en';
import 'dayjs/locale/es';
import relativeTime from 'dayjs/plugin/relativeTime';
import { LoaderCircle, Star, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();

dayjs.extend(relativeTime);
dayjs.locale(locale.value);

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const props = defineProps<{
    content: Content & {
        ratings_avg_rating?: string;
        user_rating?: { rating: number };
        user_review?: { review_text: string };
        reviews?: Review[];
    };
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

const form = useForm({
    rating: props.content.user_rating?.rating || 0,
    review: props.content.user_review?.review_text || '',
});
const dialogOpen = ref(false);
const handleDialogOpenChange = (open: boolean) => {
    dialogOpen.value = open;
    if (!open) {
        form.reset();
    }
};
const handleRating = (rating: number) => {
    if (form.rating === rating) {
        form.rating = 0;
        return;
    }

    form.rating = rating;
};
const submit = () => {
    form.post(route('content.store-review', props.content.id), {
        onFinish: () => {
            dialogOpen.value = false;
        },
        preserveScroll: true,
        preserveState: false,
    });
};
</script>

<template>
    <Head :title="content.title" />
    <AppLayout layout="header">
        <div class="flex flex-col w-full max-w-6xl mx-auto md:mt-20 gap-20">
            <!-- CONTENT INFORMATION -->
            <section class="flex flex-col px-0 md:px-2 lg:px-0 gap-4 md:flex-row md:gap-10">
                <!-- Poster image -->
                <div class="w-full md:max-w-96">
                    <div class="aspect-[6/8] w-full">
                        <img
                            src="/images/welcome/hero-background.webp"
                            :alt="content.title"
                            class="size-full mask-b-from-80% mask-b-to-100% object-cover md:rounded-lg md:mask-none"
                        />
                    </div>
                </div>

                <!-- Tabs -->
                <div class="z-10 -mt-12 w-full md:-mt-0 px-2 lg:px-0">
                    <div>
                        <!-- Title and Rating -->
                        <div class="mb-2 flex items-center justify-between">
                            <h2 class="text-xl font-bold uppercase md:text-3xl">{{ content.title }}</h2>
                            <div class="flex items-baseline space-x-1 md:space-x-2">
                                <Dialog :open="dialogOpen" @update:open="handleDialogOpenChange">
                                    <DialogTrigger as-child>
                                        <Button variant="outline">
                                            <h3 class="text-lg">
                                                {{ content.ratings_avg_rating ? Number(content.ratings_avg_rating).toFixed(1) : '—' }}
                                            </h3>
                                            <component :is="Star" class="size-4 fill-yellow-300 text-yellow-300" />
                                        </Button>
                                    </DialogTrigger>

                                    <DialogContent class="sm:max-w-md">
                                        <DialogHeader>
                                            <DialogTitle>{{ t('detail.review.title') }}</DialogTitle>
                                            <DialogDescription>
                                                {{ t('detail.review.description') }}
                                            </DialogDescription>
                                        </DialogHeader>

                                        <form id="form" @submit.prevent="submit">
                                            <div class="mb-3 flex w-full space-x-5 md:space-x-4 md:px-10">
                                                <Star
                                                    v-for="n in 5"
                                                    :key="n"
                                                    class="aspect-square size-full cursor-pointer"
                                                    :class="
                                                        cn(n <= form.rating ? 'fill-yellow-300 text-yellow-300' : 'fill-neutral-700 text-neutral-700')
                                                    "
                                                    @click="handleRating(n)"
                                                />
                                            </div>
                                            <p class="text-muted-foreground mb-4 text-center text-xs" v-show="form.rating !== 0">
                                                {{ t('detail.review.ratingHint') }}
                                            </p>

                                            <div class="grid gap-3">
                                                <Label for="review">{{ t('detail.review.reviewLabel') }}</Label>
                                                <Textarea id="review" v-model="form.review" :placeholder="t('detail.review.reviewPlaceholder')" />
                                                <p class="text-muted-foreground text-xs" v-show="form.review != ''">
                                                    {{ t('detail.review.reviewHint') }}
                                                </p>
                                                <InputError :message="form.errors.review" />
                                            </div>

                                            <DialogFooter>
                                                <Button type="submit" class="mt-4 sm:justify-start" :disabled="form.processing">
                                                    <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                                                    {{ t('detail.review.submit') }}
                                                </Button>
                                            </DialogFooter>
                                        </form>
                                    </DialogContent>
                                </Dialog>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="mb-8 flex h-5 items-center space-x-2 text-sm text-neutral-400">
                            <p v-if="content.release_year">{{ content.release_year }}</p>
                            <Separator v-if="content.release_year" orientation="vertical" />
                            <p v-if="content.type == 'movie'">{{ content.duration }}</p>
                            <p v-else>
                                {{ content.seasons.length > 0 ? content.seasons.length : 0 }} {{ t('detail.seasons', content.seasons.length) }}
                            </p>
                            <Separator orientation="vertical" />
                            <p>{{ content.category.name }}</p>
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

                        <Separator orientation="horizontal" class="mb-4" />

                        <TabsContent value="overview">
                            <p>{{ content.description }}</p>
                        </TabsContent>

                        <TabsContent value="seasons">
                            <Carousel :opts="{ align: 'center' }" v-slot="{ canScrollPrev, canScrollNext }" class="relative mb-4 w-full">
                                <CarouselContent>
                                    <CarouselItem
                                        v-for="season in content.seasons"
                                        :key="season.id"
                                        @click="selectedSeason = selectedSeason?.id === season.id ? null : season"
                                        class="group basis-1/2 cursor-pointer pl-2 md:basis-1/4"
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
                                            {{ t('detail.duration') }}: {{ episode.duration ? `${episode.duration} mins` : 'N/S' }}
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

            <!-- REVIEWS -->
            <section class="px-2 lg:px-0">
                <h2 class="text-xl font-semibold tracking-tight mb-2">{{ t('detail.reviewCount', { count: content.reviews?.length }) }}</h2>
                <div class="flex flex-col gap-y-6">
                    <div v-for="review in content.reviews" :key="review.id" class="flex gap-x-4">
                        <Avatar class="size-8 overflow-hidden rounded-full">
                            <AvatarImage v-if="review.user.avatar" :src="review.user.avatar" :alt="review.user.name" />
                            <AvatarFallback class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white">
                                {{ getInitials(review.user?.name) }}
                            </AvatarFallback>
                        </Avatar>
                        <div class="flex w-full flex-col gap-y-2">
                            <div class="flex items-baseline gap-x-2">
                                <p class="text-sm font-semibold">@{{ review.user.name }}</p>
                                <p class="text-muted-foreground text-xs">{{ dayjs(review.created_at).fromNow() }}</p>
                            </div>
                            <div class="w-full flex items-start justify-between">
                                <p class="text-pretty">{{ review.review_text }}</p>
                                <Link :href="route('review.delete', review.id)" method="delete" v-if="review.user.id === user.id">
                                    <Trash2 class="aspect-square size-full cursor-pointer" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- RELATED CONTENTS -->
            <section v-if="relatedContents.length > 0" class="px-2 lg:px-0">
                <h2 class="text-xl font-semibold tracking-tight mb-2">{{ t('detail.moreLikeThis') }}</h2>
                <ContentCarousel
                    :contents="relatedContents"
                    :hoveredItemId="hoveredItemId"
                    :setHoveredItem="setHoveredItem"
                    :clearHoveredItem="clearHoveredItem"
                    :isDetailPage="true"
                />
            </section>

            <AppFooter />
        </div>
    </AppLayout>
</template>

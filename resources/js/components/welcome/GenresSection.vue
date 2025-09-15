<script setup lang="ts">
import HeadingLarge from '@/components/common/HeadingLarge.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { Genre } from '@/types/models';

defineProps<{
    genres: Genre[];
}>();
</script>

<template>
    <section class="flex w-full flex-col gap-y-1 overflow-x-hidden">
        <HeadingLarge :title="$t('welcome.sections.genres.title')" class="ml-5 lg:ml-0 lg:text-center" />

        <Carousel class="group relative w-full" :opts="{ align: 'start' }">
            <CarouselContent class="ml-1 lg:ml-1">
                <CarouselItem
                    v-for="genre in genres"
                    :key="genre.id"
                    class="basis-[calc(100%/2.5)] md:basis-[calc(100%/4.5)] lg:basis-[calc(100%/6.5)]"
                >
                    <Card class="relative aspect-[5/7] overflow-hidden rounded-md">
                        <CardContent class="p-0">
                            <img
                                src="/images/welcome/hero-background.webp"
                                :alt="genre.content.title"
                                class="absolute inset-0 z-0 size-full mask-b-from-70% mask-b-to-95% object-cover"
                            />
                            <div class="absolute bottom-0 z-10 mb-4 w-full text-center">
                                <p class="text-lg font-bold capitalize">{{ genre.name }}</p>
                                <p class="text-[10px] font-medium">{{ genre.content.title }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>

            <CarouselPrevious />
            <CarouselNext />
        </Carousel>
    </section>
</template>

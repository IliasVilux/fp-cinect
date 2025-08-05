<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { Category } from '@/types/models';

defineProps<{
    categories: Category[];
}>();
</script>

<template>
    <section class="my-20 w-full overflow-x-hidden">
        <h2 class="mb-4 text-2xl font-medium lg:text-center lg:text-3xl lg:font-bold">
            {{ $t('welcome.sections.categories.title') }}
        </h2>

        <Carousel class="relative w-full" :opts="{ align: 'start' }" v-slot="{ canScrollPrev, canScrollNext }">
            <!-- Gradient overlays -->
            <div
                class="absolute left-0 z-10 hidden h-full w-32 bg-gradient-to-r from-[#101010] from-10% to-neutral-900/0 opacity-0 transition-opacity duration-300 md:flex"
                :class="{ 'opacity-100': canScrollPrev }"
            />
            <div
                class="absolute right-0 z-10 hidden h-full w-32 bg-gradient-to-l from-[#101010] from-10% to-neutral-900/0 opacity-0 transition-opacity duration-300 md:flex"
                :class="{ 'opacity-100': canScrollNext }"
            />

            <!-- Carousel content -->
            <CarouselContent>
                <CarouselItem v-for="category in categories" :key="category.id" class="basis-1/2 md:basis-1/4 lg:basis-1/6">
                    <Card class="relative aspect-[5/8] overflow-hidden rounded-lg">
                        <CardContent class="p-0">
                            <img
                                src="/images/welcome/hero-background.webp"
                                :alt="category.content.title"
                                class="absolute inset-0 z-0 size-full mask-b-from-70% mask-b-to-95% object-cover"
                            />
                            <div class="absolute bottom-0 z-10 mb-4 w-full text-center">
                                <p class="text-lg font-bold capitalize">{{ category.name }}</p>
                                <p class="text-[10px] font-medium">{{ category.content.title }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>

            <!-- Arrows -->
            <CarouselPrevious class="z-20 h-16" />
            <CarouselNext class="z-20 h-16" />
        </Carousel>
    </section>
</template>

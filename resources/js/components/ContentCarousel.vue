<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { cn } from '@/lib/utils';
import type { Content } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { WithClassAsProps } from './ui/carousel/interface';

const props = defineProps<
    {
        contents: Content[];
        hoveredItemId: string | null;
        setHoveredItem: (id: string) => void;
        clearHoveredItem: () => void;
        isDetailPage?: boolean;
    } & WithClassAsProps
>();
const isHovering = ref(false);
</script>

<template>
    <Carousel
        :class="cn('relative w-full', props.class)"
        :opts="{ align: 'center' }"
        v-slot="{ canScrollPrev, canScrollNext }"
        @mouseenter="isHovering = true"
        @mouseleave="isHovering = false"
    >
        <div
            class="pointer-events-none absolute z-10 hidden h-full w-24 bg-gradient-to-r from-neutral-950/75 to-neutral-950/0 transition-opacity duration-300 lg:block"
            :class="[canScrollPrev && !isHovering ? 'md:opacity-100' : 'opacity-0']"
        />
        <div
            class="pointer-events-none absolute right-0 z-10 hidden h-full w-24 bg-gradient-to-l from-neutral-950/75 to-neutral-950/0 transition-opacity duration-300 lg:block"
            :class="[canScrollNext && !isHovering ? 'md:opacity-100' : 'opacity-0']"
        />

        <CarouselContent :class="{ 'px-2 lg:px-4': !isDetailPage }">
            <CarouselItem
                v-for="content in contents"
                :key="content.id"
                class="group basis-1/2 transition duration-1000 md:basis-1/4 lg:basis-1/6"
                @mouseenter="setHoveredItem(String(content.id))"
                @mouseleave="clearHoveredItem"
                :class="{
                    'brightness-75': hoveredItemId && hoveredItemId !== String(content.id),
                }"
            >
                <Link :href="route('content.detail', content.id)">
                    <Card
                        class="relative aspect-[5/8] overflow-hidden rounded-lg transition duration-200 group-hover:-translate-y-1 group-hover:border-indigo-600 group-hover:bg-[#120e28]"
                    >
                        <CardContent>
                            <img
                                :src="content.cover_image || '/images/welcome/hero-background.webp'"
                                :alt="`${content.title} cover image`"
                                class="absolute inset-0 z-0 size-full object-cover"
                            />

                            <div
                                class="pointer-events-none absolute right-0 bottom-0 z-10 h-20 w-full bg-neutral-950 mask-t-from-10% mask-t-to-100% transition-opacity duration-300 group-hover:opacity-100 lg:opacity-0"
                            />

                            <div class="absolute bottom-0 left-0 z-20 mb-4 w-full text-center">
                                <p class="mx-2 truncate text-sm font-medium transition-opacity duration-200 group-hover:opacity-100 lg:opacity-0">
                                    {{ content.title }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </Link>
            </CarouselItem>
        </CarouselContent>

        <CarouselNext class="z-20 h-16" />
        <CarouselPrevious class="z-20 h-16" />
    </Carousel>
</template>

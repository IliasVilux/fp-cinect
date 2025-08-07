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
            class="pointer-events-none absolute left-0 top-0 hidden h-full w-24 bg-gradient-to-r from-neutral-950/75 to-neutral-950/0 transition-opacity duration-300 lg:block z-10"
            :class="[canScrollPrev && !isHovering ? 'md:opacity-100' : 'opacity-0']"
        />
        <div
            class="pointer-events-none absolute right-0 top-0 hidden h-full w-24 bg-gradient-to-l from-neutral-950/75 to-neutral-950/0 transition-opacity duration-300 lg:block z-10"
            :class="[canScrollNext && !isHovering ? 'md:opacity-100' : 'opacity-0']"
        />

        <CarouselContent>
            <CarouselItem
                v-for="content in contents"
                :key="content.id"
                :class="cn(
                    'group transition duration-1000 basis-1/2 md:basis-1/4 lg:basis-1/6',
                    hoveredItemId && hoveredItemId !== String(content.id) && 'brightness-75'
                )"
                @mouseenter="setHoveredItem(String(content.id))"
                @mouseleave="clearHoveredItem"
            >
                <Link :href="route('content.detail', content.id)">
                    <Card
                        class="relative aspect-[5/8] overflow-hidden rounded-lg transition duration-200 group-hover:-translate-y-1 group-hover:border-indigo-600 group-hover:bg-[#120e28]"
                    >
                        <CardContent>
                            <img
                                :src="content.cover_image || '/images/welcome/hero-background.webp'"
                                :alt="`${content.title} cover image`"
                                class="absolute inset-0 size-full object-cover"
                            />

                            <div
                                class="pointer-events-none absolute bottom-0 right-0 h-20 w-full bg-neutral-950 mask-t-from-10% mask-t-to-100% transition-opacity duration-300 group-hover:opacity-100 lg:opacity-0 z-10"
                            />

                            <div class="absolute bottom-0 left-0 w-full mb-4 text-center z-20">
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

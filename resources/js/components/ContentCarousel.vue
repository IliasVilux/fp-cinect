<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { cn } from '@/lib/utils';
import type { Content } from '@/types/models';
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
            class="pointer-events-none absolute top-0 left-0 z-10 hidden h-full w-24 bg-gradient-to-r from-neutral-950/75 to-neutral-950/0 transition-opacity duration-300 lg:block"
            :class="[canScrollPrev && !isHovering ? 'md:opacity-100' : 'opacity-0']"
        />
        <div
            class="pointer-events-none absolute top-0 right-0 z-10 hidden h-full w-24 bg-gradient-to-l from-neutral-950/75 to-neutral-950/0 transition-opacity duration-300 lg:block"
            :class="[canScrollNext && !isHovering ? 'md:opacity-100' : 'opacity-0']"
        />

        <CarouselContent>
            <CarouselItem
                v-for="content in contents"
                :key="content.id"
                :class="
                    cn(
                        'group basis-1/2 transition duration-1000 md:basis-1/4 lg:basis-1/6',
                        hoveredItemId && hoveredItemId !== String(content.id) && 'brightness-75',
                    )
                "
                @mouseenter="setHoveredItem(String(content.id))"
                @mouseleave="clearHoveredItem"
            >
                <ContentCard
                    :content="content"
                    class="relative aspect-[5/8] overflow-hidden rounded-lg transition duration-200 group-hover:-translate-y-1 group-hover:border-indigo-600 group-hover:bg-[#120e28]"
                />
            </CarouselItem>
        </CarouselContent>

        <CarouselNext class="z-20 h-16" />
        <CarouselPrevious class="z-20 h-16" />
    </Carousel>
</template>

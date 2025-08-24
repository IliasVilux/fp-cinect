<script setup lang="ts">
import ContentCard from '@/components/common/ContentCard.vue';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { cn } from '@/lib/utils';
import type { Content } from '@/types/models';
import { ref } from 'vue';
import { WithClassAsProps } from '../ui/carousel/interface';

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
        :opts="{ align: 'start' }"
        @mouseenter="isHovering = true"
        @mouseleave="isHovering = false"
    >
        <CarouselContent>
            <CarouselItem
                v-for="content in contents"
                :key="content.id"
                :class="
                    cn(
                        'group basis-[calc(100%/2.5)] md:basis-[calc(100%/4.5)] lg:basis-[calc(100%/6.5)] transition duration-1000',
                        hoveredItemId && hoveredItemId !== String(content.id) && 'brightness-75',
                    )
                "
                @mouseenter="setHoveredItem(String(content.id))"
                @mouseleave="clearHoveredItem"
            >
                <ContentCard
                    :content="content"
                    class="relative aspect-[5/7] overflow-hidden rounded-md transition duration-200 group-hover:scale-102 group-hover:border-indigo-600 group-hover:bg-[#120e28]"
                />
            </CarouselItem>
        </CarouselContent>

        <CarouselNext />
        <CarouselPrevious />
    </Carousel>
</template>

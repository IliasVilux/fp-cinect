<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { Content, Season } from '@/types/models';

const props = defineProps<{
    content: Content;
    selectedSeason?: Season | null;
}>();
const emit = defineEmits<{
  (e: 'update:selectedSeason', value: Season | null): void;
}>();

const toggleSeason = (season: Season) => {
  emit('update:selectedSeason', props.selectedSeason?.id === season.id ? null : season);
};
</script>

<template>
    <Carousel :opts="{ align: 'center' }" v-slot="{ canScrollPrev, canScrollNext }" class="relative w-full">
        <CarouselContent>
            <CarouselItem
                v-for="season in content.seasons"
                :key="season.id"
                @click="toggleSeason(season)"
                class="group basis-1/2 cursor-pointer pl-2 md:basis-1/4"
            >
                <Card
                    class="flex justify-end h-full min-h-20 rounded-lg px-3 py-1 transition duration-200 group-hover:border-indigo-600 group-hover:bg-[#120e28]"
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
</template>

<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import type { Content } from '@/types/models'
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    contents: Content[],
    hoveredItemId: string | null,
    setHoveredItem: (id: string) => void,
    clearHoveredItem: () => void,
}>()
const isHovering = ref(false)
</script>

<template>
    <Carousel class="relative w-full" :opts="{ align: 'center' }" v-slot="{ canScrollPrev, canScrollNext }"
        @mouseenter="isHovering = true" @mouseleave="isHovering = false">
        <div class="absolute z-10 w-24 h-full bg-gradient-to-r from-neutral-950/75 to-neutral-950/0 transition-opacity duration-300 pointer-events-none hidden lg:block"
            :class="[
                canScrollPrev && !isHovering ? 'md:opacity-100' : 'opacity-0'
            ]" />
        <div class="absolute right-0 z-10 w-24 h-full bg-gradient-to-l from-neutral-950/75 to-neutral-950/0 transition-opacity duration-300 pointer-events-none hidden lg:block"
            :class="[
                canScrollNext && !isHovering ? 'md:opacity-100' : 'opacity-0'
            ]" />

        <CarouselContent>
            <CarouselItem v-for="content in contents" :key="content.id"
                class="basis-1/2 md:basis-1/4 lg:basis-1/6 group transition duration-1000" @mouseenter="setHoveredItem(String(content.id))"
                @mouseleave="clearHoveredItem" :class="{
                    'brightness-75': hoveredItemId && hoveredItemId !== String(content.id)
                }">
                <Link :href="route('dashboard', content.id)">
                <Card
                    class="relative rounded-lg overflow-hidden aspect-[5/8] group-hover:border-indigo-600 group-hover:bg-[#120e28] group-hover:-translate-y-1 transition duration-200">
                    <CardContent>
                        <img :src="content.cover_image || '/images/welcome/hero-background.webp'"
                            :alt="`${content.title} cover image`" class="absolute inset-0 size-full object-cover z-0" />

                        <div
                            class="absolute right-0 bottom-0 z-10 w-full h-20 pointer-events-none bg-neutral-950 mask-t-from-10% mask-t-to-100% lg:opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

                        <div class="absolute z-20 mb-4 bottom-0 left-0 w-full text-center">
                            <p
                                class="text-sm font-medium truncate mx-2 lg:opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                {{ content.title }}</p>
                        </div>
                    </CardContent>
                </Card>
                </Link>
            </CarouselItem>
        </CarouselContent>

        <CarouselNext class="h-16 z-20" />
        <CarouselPrevious class="h-16 z-20" />
    </Carousel>
</template>

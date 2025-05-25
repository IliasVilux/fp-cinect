<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import type { Content } from '@/types/models'

defineProps<{
  contents: Content[]
}>()
</script>

<template>
  <Carousel class="relative w-full" :opts="{ align: 'center' }" v-slot="{ canScrollPrev, canScrollNext }">
    <div class="absolute z-10 w-24 h-full bg-gradient-to-r from-[#101010]/100 to-[#101010]/0 opacity-0 transition-opacity duration-300" :class="{'md:opacity-100' : canScrollPrev}"></div>
    <div class="absolute right-0 z-10 w-24 h-full bg-gradient-to-l from-[#101010]/100 to-[#101010]/0 opacity-0 transition-opacity duration-300" :class="{'md:opacity-100' : canScrollNext}"></div>

    <CarouselContent class="-ml-2">
      <CarouselItem v-for="content in contents" :key="content.id" class="basis-1/2 md:basis-1/4 lg:basis-1/6 pl-2">
        <Card class="relative rounded-lg overflow-hidden aspect-[5/8]">
          <CardContent>
            <img :src="content.cover_image || '/images/welcome/hero-background.webp'" :alt="`${content.title} cover image`"
              class="absolute inset-0 size-full object-cover z-0 mask-b-from-70% mask-b-to-95%" />

            <div class="absolute z-10 mb-4 bottom-0 left-0 w-full text-center">
              <p class="text-[10px] font-medium">{{ content.title }}</p>
            </div>
          </CardContent>
        </Card>
      </CarouselItem>
    </CarouselContent>

    <CarouselNext class="h-16 z-20" />
    <CarouselPrevious class="h-16 z-20" />
  </Carousel>
</template>

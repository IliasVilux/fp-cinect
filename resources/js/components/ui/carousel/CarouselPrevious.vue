<script setup lang="ts">
import type { WithClassAsProps } from './interface'
import { ChevronLeft } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import { Button, type ButtonVariants } from '@/components/ui/button'
import { useCarousel } from './useCarousel'

const props = withDefaults(defineProps<{
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
}
& WithClassAsProps>(), {
  variant: 'link',
  size: 'icon',
})

const { orientation, canScrollPrev, scrollPrev } = useCarousel()
</script>

<template>
  <Button
    data-slot="carousel-previous"
    :class="cn(
      'absolute w-32 h-full bg-gradient-to-r from-neutral-950/65 to-neutral-950/0 opacity-0 hover:opacity-100 flex items-center justify-start',
      orientation === 'horizontal'
        ? 'top-1/2 left-0 -translate-y-1/2'
        : '-top-12 left-1/2 -translate-x-1/2 rotate-90',
      !canScrollPrev && 'opacity-0 pointer-events-none',
      props.class,
    )"
    :variant="variant"
    :size="size"
    @click="scrollPrev"
  >
    <slot>
      <ChevronLeft class="size-14" />
      <span class="sr-only">Previous Slide</span>
    </slot>
  </Button>
</template>

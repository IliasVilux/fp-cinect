<script setup lang="ts">
import type { WithClassAsProps } from './interface'
import { ChevronRight } from 'lucide-vue-next'
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

const { orientation, canScrollNext, scrollNext } = useCarousel()
</script>

<template>
  <Button
    data-slot="carousel-next"
    :class="cn(
      'absolute w-32 h-full bg-gradient-to-l from-neutral-950/65 to-neutral-950/0 opacity-0 hover:opacity-100 flex items-center justify-end rounded-none',
      orientation === 'horizontal'
        ? 'top-1/2 right-0 -translate-y-1/2'
        : '-bottom-12 left-1/2 -translate-x-1/2 rotate-90',
      !canScrollNext && 'opacity-0 pointer-events-none',
      props.class,
    )"
    :variant="variant"
    :size="size"
    @click="scrollNext"
  >
    <slot>
      <ChevronRight class="size-10 lg:size-14" />
      <span class="sr-only">Next Slide</span>
    </slot>
  </Button>
</template>

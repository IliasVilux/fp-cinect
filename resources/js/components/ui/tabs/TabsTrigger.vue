<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { reactiveOmit } from '@vueuse/core'
import { TabsTrigger, type TabsTriggerProps, useForwardProps } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps<TabsTriggerProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = reactiveOmit(props, 'class')

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <TabsTrigger
    data-slot="tabs-trigger"
    v-bind="forwardedProps"
    :class="cn(
      `text-neutral-300 data-[state=active]:text-indigo-500 inline-flex h-[calc(100%-1px)] flex-1 items-center justify-center gap-1.5 py-1 text-sm font-semibold whitespace-nowrap transition-[color] uppercase cursor-pointer`,
      props.class,
    )"
  >
    <slot />
  </TabsTrigger>
</template>

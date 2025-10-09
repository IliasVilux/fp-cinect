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
      `text-neutral-300 data-[state=active]:text-indigo-500 inline-flex h-[calc(100%-1px)] flex-1 items-center justify-start gap-1.5 px-5 py-2 lg:px-0 rounded-lg lg:rounded-none lg:py-1 text-sm font-semibold whitespace-nowrap transition-[color] uppercase cursor-pointer bg-neutral-900 border border-border data-[state=active]:border-indigo-500 lg:bg-transparent lg:border-none w-full mx-auto`,
      props.class,
    )"
  >
    <slot />
  </TabsTrigger>
</template>

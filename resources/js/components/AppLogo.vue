<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <img src="/images/logo-full.webp" alt="Cinect logo full" :class="cn('w-full', props.class)" />
</template>

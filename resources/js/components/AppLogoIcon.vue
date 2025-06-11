<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <img
        src="/images/logo-mini.svg"
        alt="Cinect logo Mini"
        :class="cn('w-full', props.class)"
    />
</template>

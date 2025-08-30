<script setup lang="ts">
interface Props {
    title: string;
}

defineProps<Props>();
</script>

<template>
    <div class="mb-1 space-y-0.5">
        <h1 class="text-2xl lg:text-3xl font-bold tracking-normal">{{ title }}</h1>
    </div>
</template>

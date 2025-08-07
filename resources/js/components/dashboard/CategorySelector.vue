<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Content } from '@/types/models';

defineProps<{
    items: Content[];
    hoveredImage: string | null;
}>();
const emit = defineEmits<{
    (e: 'setHoveredImage', value: string): void;
    (e: 'clearHoveredImage'): void;
}>();

const onMouseOver = (image: string) => {
  emit('setHoveredImage', image)
}
const onMouseLeave = () => {
  emit('clearHoveredImage')
}
</script>

<template>
    <div class="relative grid auto-rows-min grid-cols-3">
        <Link
            v-for="item in items"
            :key="item.id"
            :href="route('dashboard.category', item.type)"
            class="relative z-10 aspect-[9/16] lg:aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 cursor-pointer transition-colors duration-300 hover:border-indigo-600 hover:shadow-2xl"
            @mouseover="onMouseOver(item.cover_image || '/images/welcome/hero-background.webp')"
            @mouseleave="onMouseLeave"
        >
            <!-- Gradient -->
            <div class="absolute bottom-0 z-10 w-full h-1/3 bg-gradient-to-t from-neutral-950/100 to-neutral-950/0"></div>

            <!-- Cover image -->
            <img
                :src="item.cover_image || '/images/welcome/hero-background.webp'"
                alt="Interestellar"
                class="absolute inset-0 z-0 w-full h-full object-cover transition duration-500 group-hover:scale-110"
            />

            <!-- Title -->
            <h3 class="absolute bottom-2 left-3 z-20 text-2xl font-medium capitalize lg:text-xl lg:font-bold">{{ item.type }}</h3>
        </Link>

        <!-- Hover background image -->
        <img
            :src="hoveredImage || '/images/welcome/hero-background.webp'"
            alt="Background"
            class="absolute inset-0 w-full object-cover opacity-0 mask-b-from-0% mask-b-to-100% transition-opacity duration-1000 lg:mask-b-to-50%"
            :class="{ 'opacity-50': hoveredImage }"
        />
    </div>
</template>

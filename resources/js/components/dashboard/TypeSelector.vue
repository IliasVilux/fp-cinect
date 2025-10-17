<script setup lang="ts">
import Heading from '@/components/common/Heading.vue';
import { Content } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import { getImage } from '@/composables/getImage';

defineProps<{
    items: Content[];
    hoveredImage: string | null;
}>();
const emit = defineEmits<{
    (e: 'setHoveredImage', value: string): void;
    (e: 'clearHoveredImage'): void;
}>();

const onMouseOver = (image: string) => {
    emit('setHoveredImage', image);
};
const onMouseLeave = () => {
    emit('clearHoveredImage');
};
</script>

<template>
    <div class="relative grid auto-rows-min grid-cols-3 gap-2 p-5 pb-0 lg:p-11 lg:pb-0">
        <Link
            v-for="item in items"
            :key="item.id"
            :href="route('dashboard.type', item.type)"
            class="border-sidebar-border/70 relative z-10 aspect-[5/8] cursor-pointer overflow-hidden rounded-xl border transition-colors duration-300 hover:border-indigo-600 hover:shadow-2xl lg:aspect-video"
            @mouseover="onMouseOver(getImage(item.backdrop_image ?? '', 'backdrop', item.type, 'original'))"
            @mouseleave="onMouseLeave"
        >
            <!-- Gradient -->
            <div class="absolute bottom-0 z-10 h-1/3 w-full bg-gradient-to-t from-neutral-950/100 to-neutral-950/0"></div>

            <!-- Cover image -->
            <img
                :src="getImage(item.poster_image, 'poster', item.type)"
                alt="Interestellar"
                class="absolute inset-0 z-0 h-full w-full object-cover transition duration-500 group-hover:scale-110"
            />

            <!-- Title -->
            <Heading :title="item.type" class="absolute bottom-2 left-3 z-20" />
        </Link>

        <!-- Hover background image -->
        <img
            :src="hoveredImage || ''"
            class="absolute inset-0 w-full h-auto mask-b-from-0% mask-b-to-100% object-cover opacity-0 transition-opacity duration-1000 lg:mask-b-to-50%"
            :class="{ 'opacity-50': hoveredImage }"
        />
    </div>
</template>

<script setup lang="ts">
import { Season } from '@/types/models';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    selectedSeason?: Season | null;
}>();
</script>

<template>
    <section class="grid grid-cols-1 gap-2">
        <div
            v-for="episode in selectedSeason?.episodes"
            :key="episode.id"
            class="border-border flex flex-col rounded-md border bg-gradient-to-l from-neutral-950/80 to-neutral-950/0 p-4 transition-colors duration-300 hover:border-indigo-600 hover:bg-neutral-800 md:flex-row md:items-center md:justify-between"
        >
            <div class="flex flex-col gap-1 md:flex-row md:items-center md:gap-4">
                <p class="text-xs font-light text-neutral-600 md:text-neutral-300">{{ episode.episode_number }}</p>
                <p>{{ episode.title }}</p>
            </div>

            <p class="mt-2 text-end text-xs text-neutral-400 md:mt-0 md:text-start">
                {{ t('detail.duration') }}: {{ episode.duration ? `${episode.duration} mins` : 'N/S' }}
            </p>
        </div>
    </section>
</template>

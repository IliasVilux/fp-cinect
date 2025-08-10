<script setup lang="ts">
import { Season } from '@/types/models';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    selectedSeason?: Season | null;
}>();
</script>

<template>
    <section class="grid grid-cols-1 gap-2 md:grid-cols-3">
        <div v-for="episode in selectedSeason?.episodes" :key="episode.id">
            <div>
                <p class="text-xs font-light text-neutral-300">Ep{{ episode.episode_number }}</p>
                <p>{{ episode.title }}</p>
                <p class="text-end text-xs text-neutral-400 md:text-start">
                    {{ t('detail.duration') }}: {{ episode.duration ? `${episode.duration} mins` : 'N/S' }}
                </p>
            </div>
        </div>
    </section>
</template>

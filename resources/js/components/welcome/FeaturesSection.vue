<script setup lang="ts">
import Heading from '@/components/common/Heading.vue';

defineProps<{
    features: {
        title: string;
        description: string;
        icon: any;
    }[];
}>();
</script>

<template>
    <section class="my-20 flex flex-col gap-y-3 mx-5 lg:mx-2">
        <Heading :title="$t('welcome.sections.features.title')" class="lg:text-center" />

        <div class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="(feature, i) in features"
                :key="i"
                class="group flex h-full flex-col justify-between rounded-xl border border-neutral-800 p-4 hover:border-indigo-600 transition-colors duration-300 bg-gradient-to-br lg:bg-gradient-to-b from-neutral-900 to-neutral-950"
            >
                <div class="mb-6">
                    <p class="mb-1 text-lg font-bold">{{ feature.title }}</p>
                    <p class="text-muted-foreground">{{ feature.description }}</p>
                </div>
                <component :is="feature.icon" class="ml-auto size-6 lg:size-8 group-hover:text-indigo-600 group-hover:rotate-12 group-hover:scale-110 transition-all duration-500" />
            </div>
        </div>
    </section>
</template>

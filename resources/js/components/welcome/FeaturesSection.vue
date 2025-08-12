<script setup lang="ts">
defineProps<{
    features: {
        title: string;
        description: string;
        icon: any;
    }[];
}>();
</script>

<template>
    <section class="my-20 w-full px-2 lg:px-4">
        <h2 class="mb-4 text-2xl font-medium lg:text-center lg:text-3xl lg:font-bold">
            {{ $t('welcome.sections.features.title') }}
        </h2>

        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="(feature, i) in features"
                :key="i"
                class="flex h-full flex-col justify-between rounded-xl border border-neutral-800 bg-neutral-900 p-4"
            >
                <div class="mb-6">
                    <p class="mb-2 text-lg font-bold">{{ feature.title }}</p>
                    <p class="text-neutral-300">{{ feature.description }}</p>
                </div>
                <component :is="feature.icon" class="ml-auto size-6 lg:size-8" />
            </div>
        </div>
    </section>
</template>

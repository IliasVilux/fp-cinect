<script setup lang="ts">
import { Content } from '@/types/models';

defineProps<{
    trendingContents: Content[];
}>();
</script>

<template>
    <section class="relative mb-20 min-h-[400px] w-full items-end lg:flex lg:min-h-[600px]">
        <img
            src="/images/welcome/hero-background.webp"
            :alt="trendingContents[0].title"
            class="inset-0 z-0 size-full min-h-96 mask-b-from-20% object-cover lg:absolute lg:mask-x-from-65% lg:mask-b-from-70% lg:mask-b-to-95%"
        />

        <div class="relative z-10 mx-4 -mt-36 flex max-w-4xl flex-col gap-y-8 lg:mx-auto">
            <div class="text-center md:text-start">
                <h1 class="text-xl font-black uppercase">
                    {{ $t('welcome.sections.trending.title') }}
                </h1>
                <p class="max-w-2xl text-4xl font-extralight md:text-6xl lg:text-7xl">
                    {{ $t('welcome.sections.trending.subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2 md:grid-cols-4">
                <img
                    v-for="content in trendingContents.slice(1)"
                    :key="content.id"
                    src="/images/welcome/hero-background.webp"
                    :alt="content.title"
                    class="aspect-square size-full overflow-hidden rounded-md object-cover"
                />
            </div>
        </div>
    </section>
</template>

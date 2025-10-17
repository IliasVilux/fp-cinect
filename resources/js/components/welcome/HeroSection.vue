<script setup lang="ts">
import AppLogo from '@/components/app/AppLogo.vue';
import { getTMDBImage } from '@/composables/getImageTmdb';
import { Content } from '@/types/models';

defineProps<{
    content: Content;
}>();
</script>

<template>
    <section class="relative w-full">
        <img
            :src="getTMDBImage(content.backdrop_image, 'backdrop')"
            :alt="content.title"
            class="absolute inset-0 z-0 size-full mask-b-from-25% object-cover brightness-[.7]"
        />
        <div class="relative z-10 mx-auto flex min-h-[750px] max-w-4xl flex-col items-center justify-center p-8 text-center">
            <AppLogo class="max-w-80" />
            <h1 class="mt-2 mb-5 text-3xl font-black lg:mt-4 lg:mb-10 lg:text-6xl">{{ $t('welcome.hero.title') }}</h1>
            <p class="lg:text-2xl">{{ $t('welcome.hero.subtitle') }}</p>
        </div>
    </section>
</template>

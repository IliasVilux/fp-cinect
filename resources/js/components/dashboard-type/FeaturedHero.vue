<script setup lang="ts">
import { Content } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    featuredContent: Content;
}>();
</script>

<template>
    <section class="relative aspect-[5/7] w-full lg:aspect-video">
        <!-- Background image -->
        <img
            :src="featuredContent.cover_image || '/images/welcome/hero-background.webp'"
            alt="Hero Image"
            class="absolute inset-0 z-0 size-full mask-b-from-15% mask-b-to-95% object-cover lg:mask-b-from-55% lg:mask-b-to-100% lg:mask-l-from-30% lg:mask-l-to-95%"
        />

        <!-- Content -->
        <div class="absolute inset-0 z-10 flex flex-col justify-end mx-5 lg:mx-11 mb-32 lg:mb-28">
            <h1 class="max-w-xl text-4xl font-bold lg:text-5xl">{{ featuredContent.title }}</h1>
            <p class="my-3 max-w-xl text-lg font-light lg:mt-7 lg:mb-5">{{ featuredContent.description }}</p>
            <Link :href="route('login')" class="w-fit rounded-full bg-neutral-50 text-neutral-900 px-7 py-3 leading-none hover:bg-neutral-300 transition-colors duration-200 font-semibold">
                {{ t('dashboard-content.watchMore') }}
            </Link>
        </div>

        <!-- Genre Tag -->
        <span class="absolute top-20 right-0 z-10 border-l-4 border-indigo-600 bg-gradient-to-r from-neutral-950/75 to-neutral-950/40 px-6 py-2 text-xs font-bold uppercase">{{
            featuredContent.genre.name
        }}</span>
    </section>
</template>

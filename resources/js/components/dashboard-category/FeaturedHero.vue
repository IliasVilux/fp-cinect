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
    <section class="relative w-full aspect-[9/16] md:aspect-video">
        <!-- Background image -->
        <img
            :src="featuredContent.cover_image || '/images/welcome/hero-background.webp'"
            alt="Hero Image"
            class="absolute inset-0 size-full object-cover z-0 mask-b-from-15% mask-b-to-85% lg:mask-l-from-30% lg:mask-l-to-95% lg:mask-b-from-55% lg:mask-b-to-100%"
        />

        <!-- Content -->
        <div class="absolute inset-0 z-10 flex flex-col justify-end px-2 lg:-mt-40 lg:justify-center lg:px-4">
            <h1 class="mt-2 max-w-xl text-3xl font-bold lg:text-6xl">{{ featuredContent.title }}</h1>
            <p class="my-3 max-w-xl text-lg font-light lg:mt-7 lg:mb-5">{{ featuredContent.description }}</p>
            <Link
                :href="route('login')"
                class="w-fit rounded-full px-5 py-1.5 bg-neutral-50 text-neutral-900 leading-normal hover:bg-neutral-200"
            >
                {{ t('dashboard-content.watchMore') }}
            </Link>
        </div>

        <!-- Category Tag -->
        <span class="absolute top-20 right-0 z-10 border-l-4 border-white bg-black/50 px-6 py-2 text-xs font-bold uppercase">{{
            featuredContent.category.name
        }}</span>
    </section>
</template>

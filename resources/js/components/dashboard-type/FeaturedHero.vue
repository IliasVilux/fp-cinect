<script setup lang="ts">
import { Content } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { getImage } from '@/composables/getImage';

const { t } = useI18n();

defineProps<{
    featuredContent: Content;
}>();
</script>

<template>
    <section class="relative aspect-[5/7] w-full lg:aspect-video">
        <!-- Background image -->
        <img
            :src="getImage(featuredContent.backdrop_image, 'backdrop', featuredContent.type)"
            alt="Hero Image"
            class="absolute inset-0 z-0 size-full mask-b-from-15% mask-b-to-95% object-cover lg:mask-b-from-55% lg:mask-b-to-100% lg:mask-l-from-30% lg:mask-l-to-95%"
        />

        <!-- Content -->
        <div class="absolute inset-0 z-10 mx-5 mb-32 flex flex-col justify-end lg:mx-11 lg:mb-28">
            <h1 class="max-w-xl text-4xl font-bold lg:text-5xl">{{ featuredContent.title }}</h1>
            <p class="my-3 max-w-xl text-lg font-light lg:mt-7 lg:mb-5 line-clamp-4">{{ featuredContent.description }}</p>
            <Link
                :href="route('login')"
                class="w-fit rounded-full bg-neutral-50 px-7 py-3 leading-none font-semibold text-neutral-900 transition-colors duration-200 hover:bg-neutral-300"
            >
                {{ t('dashboard-type.watchMore') }}
            </Link>
        </div>

        <!-- Genre Tag -->
        <span
            class="absolute top-20 right-0 z-10 border-l-4 border-indigo-600 bg-gradient-to-r from-neutral-950/75 to-neutral-950/40 px-6 py-2 text-xs font-bold uppercase"
            >{{ featuredContent.genre.name }}</span>
    </section>
</template>

<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import GenresSection from '@/components/welcome/GenresSection.vue';
import FeaturesSection from '@/components/welcome/FeaturesSection.vue';
import HeroSection from '@/components/welcome/HeroSection.vue';
import TrendingSection from '@/components/welcome/TrendingSection.vue';
import WelcomeHeader from '@/components/WelcomeHeader.vue';
import type { Genre, Content } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { Bookmark, BookMarked, Star, Telescope } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();

defineProps<{
    genres: Genre[];
    trendingContents: Content[];
}>();

const icons = {
    Telescope,
    Bookmark,
    Star,
    BookMarked,
};
const rawItems = messages.value[locale.value]['welcome'].sections.features.items as {
    title: string;
    description: string;
    iconKey: keyof typeof icons;
}[];

const features = rawItems.map(({ iconKey, ...item }) => ({
    ...item,
    icon: icons[iconKey] || iconKey,
}));
</script>

<template>
    <Head :title="t('welcome.head.title')">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <WelcomeHeader />

    <HeroSection />
    <GenresSection :genres="genres" />
    <TrendingSection :trendingContents="trendingContents" />
    <FeaturesSection :features="features" />

    <AppFooter />
</template>

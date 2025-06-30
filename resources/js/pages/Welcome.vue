<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import WelcomeHeader from '@/components/WelcomeHeader.vue';
import type { Category, Content } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();

defineProps<{
    categories: Category[];
    trendingContents: Content[];
}>();

const features = messages.value[locale.value].welcome.features;
</script>

<template>
    <Head :title="t('welcome.head.title')">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <WelcomeHeader />
    <section class="relative mb-16 w-full">
        <!-- Imagen de fondo -->
        <img
            src="/images/welcome/hero-background.webp"
            alt="Interestellar background image"
            class="absolute inset-0 z-0 h-full w-full mask-b-from-25% object-cover brightness-[.7]"
        />

        <!-- Contenido -->
        <div class="relative z-10 mx-auto flex min-h-[500px] max-w-4xl flex-col items-center justify-center p-8 pt-44 text-center text-neutral-50">
            <img src="/images/logo-full.webp" alt="Cinect logo full" class="w-60 lg:w-96" />
            <h1 class="mt-2 mb-5 text-3xl font-black lg:mt-4 lg:mb-10 lg:text-6xl">{{ t('welcome.hero.title') }}</h1>
            <p class="lg:text-2xl">
                {{ t('welcome.hero.subtitle') }}
            </p>
        </div>
    </section>

    <section class="mb-5 w-full overflow-x-hidden">
        <h2 class="mb-4 text-2xl font-medium lg:text-center lg:text-3xl lg:font-bold">{{ t('welcome.block1.title') }}</h2>

        <Carousel
            class="relative w-full"
            :opts="{
                align: 'start',
            }"
            v-slot="{ canScrollPrev, canScrollNext }"
        >
            <div
                class="absolute left-0 z-10 hidden h-full w-32 bg-gradient-to-r from-[#101010] from-10% to-neutral-900/0 opacity-0 transition-opacity duration-300 md:flex"
                :class="{ 'opacity-100': canScrollPrev }"
            ></div>
            <div
                class="absolute right-0 z-10 hidden h-full w-32 bg-gradient-to-l from-[#101010] from-10% to-neutral-900/0 opacity-0 transition-opacity duration-300 md:flex"
                :class="{ 'opacity-100': canScrollNext }"
            ></div>

            <!-- Contenido -->
            <CarouselContent>
                <CarouselItem v-for="category in categories" :key="category.id" class="basis-1/2 md:basis-1/4 lg:basis-1/6">
                    <Card class="relative aspect-[5/8] overflow-hidden rounded-lg">
                        <CardContent>
                            <!-- Imagen de fondo -->
                            <img
                                src="/images/welcome/hero-background.webp"
                                :alt="`${category.content.title} cover image`"
                                class="absolute inset-0 z-0 size-full mask-b-from-70% mask-b-to-95% object-cover"
                            />

                            <!-- Contenido -->
                            <div class="absolute bottom-0 left-0 z-10 mb-4 w-full text-center">
                                <p class="text-lg font-bold capitalize">{{ category.name }}</p>
                                <p class="text-[10px] font-medium">{{ category.content.title }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious class="z-20 h-16" />
            <CarouselNext class="z-20 h-16" />
        </Carousel>
    </section>

    <section class="relative mb-16 min-h-[400px] w-full items-end overflow-hidden lg:flex lg:min-h-[600px]">
        <!-- Imagen de fondo -->
        <img
            v-if="trendingContents.length && trendingContents[0].cover_image"
            src="/images/welcome/hero-background.webp"
            :alt="trendingContents[0].title"
            class="inset-0 z-0 h-full w-full mask-b-from-20% object-cover lg:absolute lg:mask-x-from-65% lg:mask-b-from-70% lg:mask-b-to-95%"
        />

        <!-- Contenido -->
        <div class="z-10 mx-auto -mt-20 size-full max-w-4xl px-2 lg:my-0 lg:px-4">
            <!-- Textos -->
            <div class="relative mb-4">
                <h1 class="space text-center text-2xl font-black uppercase md:text-start">{{ t('welcome.block2.title') }}</h1>
                <p class="max-w-2xl text-4xl font-extralight md:text-6xl lg:text-7xl">{{ t('welcome.block2.subtitle') }}</p>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-2 gap-2 md:grid-cols-4">
                <img
                    v-for="content in trendingContents.slice(1)"
                    :key="content.id"
                    src="/images/welcome/hero-background.webp"
                    :alt="content.title"
                    class="aspect-square size-full overflow-hidden rounded-xl object-cover"
                />
            </div>
        </div>
    </section>

    <section class="mb-16 w-full px-2 lg:px-4">
        <h2 class="mb-4 text-2xl font-medium lg:text-center lg:text-3xl lg:font-bold">{{ t('welcome.block3.title') }}</h2>
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-2 lg:grid-cols-4">
            <div
                v-for="(feature, i) in features"
                :key="i"
                class="flex h-full flex-col justify-between rounded-xl border border-neutral-800 bg-neutral-900 p-4"
            >
                <div class="mb-6">
                    <p class="mb-2 text-lg font-bold">{{ feature.title }}</p>
                    <p class="text-neutral-300">{{ feature.description }}</p>
                </div>
                <!-- <div class="ml-auto size-8 bg-indigo-500">{{ feature.icon }}</div> -->
            </div>
        </div>
    </section>
    <AppFooter />
</template>

<script setup lang="ts">
import WelcomeHeader from '@/components/WelcomeHeader.vue';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent } from '@/components/ui/card'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import type { Category, Content } from '@/types/models'
import AppFooter from '@/components/AppFooter.vue';

defineProps<{
    categories: Category[]
    trendingContents: Content[]
}>()

const features = [
    {
        title: 'Descubre nuevo contenido',
        description: 'Explora películas, series y animes recomendados para ti según tus gustos.',
        icon: ''
    },
    {
        title: 'Guarda tu contenido según tus preferencias',
        description: 'Crea listas personalizadas y accede a tu contenido favorito fácilmente.',
        icon: ''
    },
    {
        title: 'Vota y haz reviews',
        description: 'Comparte tu opinión y vota el contenido que más te gusta.',
        icon: ''
    },
    {
        title: 'Tu catálogo, a tu manera',
        description: 'Organiza, filtra y descubre nuevo contenido como nunca antes.',
        icon: ''
    }
]
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <WelcomeHeader />
    <section class="relative w-full mb-16">
        <!-- Imagen de fondo -->
        <img src="/images/welcome/hero-background.webp" alt="Interestellar background image"
            class="absolute inset-0 h-full w-full object-cover z-0 mask-b-from-25% brightness-[.7]" />

        <!-- Contenido -->
        <div
            class="max-w-4xl mx-auto relative z-10 flex flex-col items-center justify-center min-h-[500px] p-8 text-center text-neutral-50 pt-44">
            <img src="/images/logo-full.webp" alt="Cinect logo full" class="w-60 lg:w-96" />
            <h1 class="text-3xl lg:text-6xl font-black mt-2 lg:mt-4 mb-5 lg:mb-10">Descubre películas, series, animes y
                mucho más</h1>
            <p class="lg:text-2xl">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Impedit doloremque laudantium repellat!
            </p>
        </div>
    </section>

    <section class="w-full px-2 lg:px-4 overflow-x-hidden mb-5">
        <h2 class="text-2xl lg:text-3xl lg:text-center font-medium lg:font-bold mb-4">Entretenimiento incomparable</h2>

        <Carousel class="relative w-full" :opts="{
            align: 'start',
        }" v-slot="{ canScrollPrev, canScrollNext }">
            <div class="hidden md:flex absolute left-0 h-full w-32 bg-gradient-to-r from-[#101010] from-10% to-neutral-900/0 z-10 opacity-0 transition-opacity duration-300" :class="{ 'opacity-100': canScrollPrev }"></div>
            <div class="hidden md:flex absolute right-0 h-full w-32 bg-gradient-to-l from-[#101010] from-10% to-neutral-900/0 z-10 opacity-0 transition-opacity duration-300" :class="{ 'opacity-100': canScrollNext }"></div>

            <!-- Contenido -->
            <CarouselContent class="-ml-2">
                <CarouselItem v-for="category in categories" :key="category.id"
                    class="basis-1/2 md:basis-1/4 lg:basis-1/6 pl-2">
                    <Card class="relative rounded-lg overflow-hidden aspect-[5/8]">
                        <CardContent>
                            <!-- Imagen de fondo -->
                            <img src="/images/welcome/hero-background.webp"
                                :alt="`${category.content.title} cover image`"
                                class="absolute inset-0 size-full object-cover z-0 mask-b-from-70% mask-b-to-95%" />

                            <!-- Contenido -->
                            <div class="absolute z-10 mb-4 bottom-0 left-0 w-full text-center">
                                <p class="capitalize text-lg font-bold">{{ category.name }}</p>
                                <p class="text-[10px] font-medium">{{ category.content.title }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious class="h-16 z-20" />
            <CarouselNext class="h-16 z-20" />
        </Carousel>
    </section>

    <section class="w-full min-h-[400px] lg:min-h-[600px] relative lg:flex items-end overflow-hidden mb-16">
        <!-- Imagen de fondo -->
        <img v-if="trendingContents.length && trendingContents[0].cover_image"
            src="/images/welcome/hero-background.webp" :alt="trendingContents[0].title"
            class="lg:absolute inset-0 w-full h-full object-cover z-0 mask-b-from-20% lg:mask-b-from-70% lg:mask-b-to-95% lg:mask-x-from-65%" />

        <!-- Contenido -->
        <div class="z-10 max-w-4xl mx-auto -mt-20 lg:my-0 size-full px-2 lg:px-4">
            <!-- Textos -->
            <div class="relative mb-4">
                <h1 class="text-2xl font-black uppercase space text-center md:text-start">Tendencias</h1>
                <p class="max-w-2xl text-4xl md:text-6xl lg:text-7xl font-extralight">Lo que todos están viendo ahora
                    mismo</p>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <img v-for="content in trendingContents.slice(1)" :key="content.id"
                    src="/images/welcome/hero-background.webp" :alt="content.title"
                    class="object-cover size-full rounded-xl overflow-hidden aspect-square">
            </div>
        </div>
    </section>

    <section class="w-full px-2 lg:px-4 mb-16">
        <h2 class="text-2xl lg:text-3xl lg:text-center font-medium lg:font-bold mb-4">Más motivos para unirte</h2>
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-2">
            <div v-for="(feature, i) in features" :key="i"
                class="bg-neutral-900 rounded-xl border border-neutral-800 p-4 h-full flex flex-col justify-between">
                <div class="mb-6">
                    <p class="text-lg font-bold mb-2">{{ feature.title }}</p>
                    <p class="text-neutral-300">{{ feature.description }}</p>
                </div>
                <div class="size-8 bg-indigo-500 ml-auto">{{ feature.icon }}</div>
            </div>
        </div>
    </section>
    <AppFooter />
</template>

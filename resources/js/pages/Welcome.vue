<script setup lang="ts">
import WelcomeHeader from '@/components/WelcomeHeader.vue';
import { Head } from '@inertiajs/vue3';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import type { Category } from '@/types/models'

defineProps<{
    categories: Category[]
}>()
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

    <section class="w-full px-2 lg:px-4 overflow-x-hidden mb-16">
        <h2 class="text-2xl lg:text-3xl lg:text-center font-medium lg:font-bold mb-4">Entretenimiento incomparable</h2>

        <Carousel class="relative w-full" :opts="{
            align: 'start',
        }">
            <CarouselContent class="-ml-2">
                <CarouselItem v-for="category in categories" :key="category.id"
                    class="basis-1/2 md:basis-1/4 lg:basis-1/6 pl-2">
                    <Card>
                        <CardContent
                            class="flex flex-col relative bg-black rounded-lg border border-neutral-800 aspect-[5/8] overflow-hidden items-center justify-center p-6">
                            <!-- Imagen de fondo -->
                            <img src="/images/welcome/hero-background.webp"
                                :alt="`${category.content.title} cover image`"
                                class="absolute inset-0 size-full object-cover z-0 mask-b-from-70% mask-b-to-95%" />

                            <!-- Contenido -->
                            <div class="absolute z-10 text-center flex flex-col bottom-0">
                                <p class="capitalize text-lg font-bold">{{ category.name }}</p>
                                <p class="text-[10px] font-medium mb-4">{{ category.content.title }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious />
            <CarouselNext />
        </Carousel>
    </section>

    <h2 class="text-2xl lg:text-3xl lg:text-center font-medium lg:font-bold mb-4">Entretenimiento incomparable</h2>
    <section class="w-full min-h-[400px] lg:min-h-[600px] relative text-center md:text-start lg:flex items-end overflow-hidden mb-16">
        <!-- Imagen de fondo -->
        <img src="/images/welcome/hero-background.webp" alt="Fondo"
            class="lg:absolute inset-0 w-full h-full object-cover z-0 mask-b-from-20% lg:mask-b-from-70% lg:mask-b-to-95% lg:mask-x-from-65%" />

        <!-- Contenido -->
        <div class="z-10 max-w-4xl mx-auto -mt-20 lg:my-0 size-full px-2 lg:px-4">
            <!-- Textos -->
            <div class="mb-4">
                <h1 class="text-2xl font-black uppercase space">Título principal</h1>
                <p class="max-w-2xl text-7xl font-extralight">Subtítulo o descripción</p>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <div v-for="i in 4" :key="i" class="bg-indigo-500 rounded-xl overflow-hidden aspect-square"></div>
            </div>
        </div>
    </section>

    <section class="w-full px-2 lg:px-4 mb-16">
        <h2 class="text-2xl lg:text-3xl lg:text-center font-medium lg:font-bold mb-4">Más motivos para unirte</h2>
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-2">
            <div v-for="i in 4" :key="i" class="rounded-xl border border-neutral-800 p-4">
                <div class="mb-6">
                    <p class="text-lg font-bold mb-4">Lorem, ipsum dolor.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, quas?</p>
                </div>
                <div class="size-8 bg-indigo-500 ml-auto"></div>
            </div>
        </div>
    </section>
</template>

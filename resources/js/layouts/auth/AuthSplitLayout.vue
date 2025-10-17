<script setup lang="ts">
import AppLogoIcon from '@/components/app/AppLogoIcon.vue';
import { getImage } from '@/composables/getImage';
import { Content } from '@/types/models';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
    content: Content;
}>();
</script>

<template>
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="relative hidden h-full flex-col bg-neutral-950 p-10 text-white lg:flex dark:border-r">
            <!-- Background Image -->
            <img
                :src="getImage(content.backdrop_image, 'backdrop', 'original')"
                :alt="content.title"
                class="absolute inset-0 size-full mask-t-from-50% object-cover brightness-[.8]"
            />

            <Link :href="route('home')" class="relative z-20 flex items-center text-lg font-medium">
                <AppLogoIcon class="mr-3 h-10 w-auto" />
                {{ name }}
            </Link>
        </div>
        <div class="lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">{{ title }}</h1>
                    <p class="text-muted-foreground text-sm" v-if="description">{{ description }}</p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { Content } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    content: Content;
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <Link :href="route('content.detail', content.id)">
        <Card
            :class="
                cn( props.class,)
            "
        >
            <CardContent>
                <img
                    :src="content.cover_image || '/images/welcome/hero-background.webp'"
                    :alt="`${content.title} cover image`"
                    class="absolute inset-0 size-full object-cover"
                />

                <div
                    class="pointer-events-none absolute right-0 bottom-0 z-10 h-20 w-full bg-neutral-950 mask-t-from-10% mask-t-to-100% transition-opacity duration-300 group-hover:opacity-100 lg:opacity-0"
                />

                <div class="absolute bottom-0 left-0 z-20 mb-4 w-full text-center">
                    <p class="mx-2 truncate text-sm font-medium transition-opacity duration-200 group-hover:opacity-100 lg:opacity-0">
                        {{ content.title }}
                    </p>
                </div>
            </CardContent>
        </Card>
    </Link>
</template>

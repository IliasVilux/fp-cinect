<script setup lang="ts">
import Heading from '@/components/common/Heading.vue';
import FavoriteListDialog from '@/components/favoriteLists/favoriteListDialog.vue';
import { FavoriteList } from '@/types/models';
import { Badge } from '@/components/ui/badge';
import ContentCarousel from '../common/ContentCarousel.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    list: FavoriteList;
}>();
</script>

<template>
    <div class="relative flex h-full flex-col rounded-xl bg-gradient-to-br from-black to-neutral-950/10 border border-border">
        <div class="flex-1">
            <div class="flex items-center justify-between gap-2 px-4 pt-4">
                <Heading :title="list.name" />
                <Badge v-if="list.is_public" variant="cinect">Pública</Badge>
                <Badge v-else variant="outline">Privada</Badge>
            </div>

            <p class="text-pretty px-4">{{ list.description }}</p>

            <ContentCarousel v-if="list.contents" :contents="list.contents" class="mt-4" basis="basis-[calc(100%/1.5)] md:basis-[calc(100%/2.5)] lg:basis-[calc(100%/3.5)]" />
            <div v-else>
                <Link :href="route('favorite-lists.show', list.id)" class="flex h-48 items-center justify-center rounded-b-xl bg-neutral-900 text-center hover:bg-neutral-800">
                    <p class="text-pretty">No contents yet. Click to view and add some!</p>
                </Link>
            </div>
        </div>

        <FavoriteListDialog :list="list" class="justify-end p-4" />
    </div>
</template>

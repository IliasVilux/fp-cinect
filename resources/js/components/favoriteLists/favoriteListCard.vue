<script setup lang="ts">
import Heading from '@/components/common/Heading.vue';
import FavoriteListDialog from '@/components/favoriteLists/favoriteListDialog.vue';
import { FavoriteList } from '@/types/models';
import { Badge } from '@/components/ui/badge';
import ContentCarousel from '../common/ContentCarousel.vue';

defineProps<{
    list: FavoriteList;
}>();
</script>

<template>
    <div class="relative flex flex-col gap-0 rounded-xl bg-gradient-to-br from-black to-neutral-950/10">
        <div class="flex items-center justify-between gap-2 px-4 pt-4">
            <Heading :title="list.name" />
            <Badge v-if="list.is_public" variant="cinect">Pública</Badge>
            <Badge v-else variant="outline">Privada</Badge>
        </div>

        <p class="text-pretty px-4">{{ list.description }}</p>

        <ContentCarousel v-if="list.contents" :contents="list.contents" class="mt-4" basis="basis-[calc(100%/1.5)] md:basis-[calc(100%/2.5)] lg:basis-[calc(100%/3.5)]" />

        <FavoriteListDialog :list="list" class="justify-end p-4" />
    </div>
</template>

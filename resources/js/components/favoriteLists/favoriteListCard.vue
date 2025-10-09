<script setup lang="ts">
import Heading from '@/components/common/Heading.vue';
import FavoriteListDialog from '@/components/favoriteLists/favoriteListDialog.vue';
import { Badge } from '@/components/ui/badge';
import { FavoriteList } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import ContentCarousel from '../common/ContentCarousel.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    list: FavoriteList;
}>();
</script>

<template>
    <div class="border-border relative flex h-full flex-col rounded-xl border bg-gradient-to-br from-black to-neutral-950/10">
        <div class="flex-1">
            <div class="flex items-center justify-between gap-2 px-4 pt-4">
                <Heading :title="list.name" />
                <Badge v-if="list.is_public" variant="cinect">{{ t('favoriteLists.card.badge.public') }}</Badge>
                <Badge v-else variant="outline">{{ t('favoriteLists.card.badge.private') }}</Badge>
            </div>

            <p class="px-4 text-pretty">{{ list.description }}</p>

            <ContentCarousel
                v-if="list.contents && list.contents.length > 0"
                :contents="list.contents"
                class="mt-4"
                basis="basis-[calc(100%/1.5)] md:basis-[calc(100%/2.5)] lg:basis-[calc(100%/3.5)]"
            />
            <Link v-else :href="route('contents.explore')">
                <div
                    class="group relative mt-6 ml-4 flex aspect-[5/7] h-48 items-center justify-center overflow-hidden rounded-md bg-gradient-to-b from-neutral-950 to-neutral-900"
                >
                    <component :is="Plus" class="size-16 text-neutral-600 transition-colors duration-200 group-hover:text-indigo-600" />
                </div>
            </Link>
        </div>

        <FavoriteListDialog :list="list" class="justify-end p-4" />
    </div>
</template>

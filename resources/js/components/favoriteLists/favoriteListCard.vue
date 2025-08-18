<script setup lang="ts">
import { FavoriteList } from '@/types/models';
import FavoriteListDialog from '@/components/favoriteLists/favoriteListDialog.vue';
import { Eye, EyeClosed } from 'lucide-vue-next';
import ContentCard from '@/components/ContentCard.vue';

defineProps<{
    list: FavoriteList;
}>();
</script>

<template>
    <div>
        <p>{{ list.name }}</p>
        <p>{{ list.description }}</p>
        <Eye v-if="list.is_public" />
        <EyeClosed v-else />
        <ContentCard v-for="content in list.contents" :key="content.id" :content="content" />
        <FavoriteListDialog :list="list" />
    </div>
</template>

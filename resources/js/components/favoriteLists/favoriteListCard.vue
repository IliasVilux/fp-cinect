<script setup lang="ts">
import { FavoriteList } from '@/types/models';
import FavoriteListDialog from '@/components/favoriteLists/favoriteListDialog.vue';

defineProps<{
    list: FavoriteList;
}>();
</script>

<template>
    <FavoriteListDialog :list="list" />
</template>

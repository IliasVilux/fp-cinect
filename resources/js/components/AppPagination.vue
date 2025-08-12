<script setup lang="ts">
import Pagination from '@/components/ui/pagination/Pagination.vue';
import PaginationContent from '@/components/ui/pagination/PaginationContent.vue';
import PaginationEllipsis from '@/components/ui/pagination/PaginationEllipsis.vue';
import PaginationItem from '@/components/ui/pagination/PaginationItem.vue';
import PaginationNext from '@/components/ui/pagination/PaginationNext.vue';
import PaginationPrevious from '@/components/ui/pagination/PaginationPrevious.vue';
import { router } from '@inertiajs/vue3';

defineProps<{
    contents: any;
}>();
</script>

<template>
    <Pagination :items-per-page="contents.per_page" :total="contents.total" :default-page="contents.current_page">
        <PaginationContent v-slot="{ items }">
            <PaginationPrevious :disabled="!contents.prev_page_url" class="cursor-pointer" @click="router.visit(contents.prev_page_url)" />

            <template v-for="(item, index) in items" :key="index">
                <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === contents.current_page"
                    @click="router.visit(`?page=${item.value}`)"
                    class="cursor-pointer"
                >
                    {{ item.value }}
                </PaginationItem>
            </template>

            <PaginationEllipsis
                v-if="contents.current_page < contents.last_page"
                class="cursor-pointer"
                @click="router.visit(`?page=${contents.last_page}`)"
            />

            <PaginationNext :disabled="!contents.next_page_url" class="cursor-pointer" @click="router.visit(contents.next_page_url)" />
        </PaginationContent>
    </Pagination>
</template>

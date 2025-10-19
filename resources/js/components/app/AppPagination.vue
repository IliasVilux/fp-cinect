<script setup lang="ts">
import Pagination from '@/components/ui/pagination/Pagination.vue';
import PaginationContent from '@/components/ui/pagination/PaginationContent.vue';
import PaginationEllipsis from '@/components/ui/pagination/PaginationEllipsis.vue';
import PaginationItem from '@/components/ui/pagination/PaginationItem.vue';
import PaginationNext from '@/components/ui/pagination/PaginationNext.vue';
import PaginationPrevious from '@/components/ui/pagination/PaginationPrevious.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    contents: any;
    filters: Record<string, any>;
}>();

const goToPage = (page: number) => {
    const params = new URLSearchParams();

    for (const [key, value] of Object.entries(props.filters)) {
        if (value !== null && value !== undefined && value !== '') {
            params.set(key, value.toString());
        }
    }

    params.set('page', page.toString());

    router.visit(`${route('contents.explore')}?${params.toString()}`, {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>

<template>
    <Pagination :items-per-page="contents.per_page" :total="contents.total" :default-page="contents.current_page">
        <PaginationContent v-slot="{ items }">
            <PaginationPrevious :disabled="!contents.prev_page_url" class="cursor-pointer" @click="goToPage(contents.prev_page_url)" />

            <template v-for="(item, index) in items" :key="index">
                <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === contents.current_page"
                    @click="goToPage(item.value)"
                    class="cursor-pointer"
                >
                    {{ item.value }}
                </PaginationItem>
            </template>

            <PaginationEllipsis
                v-if="contents.current_page < contents.last_page"
                class="cursor-pointer"
                @click="goToPage(contents.last_page)"
            />

            <PaginationNext :disabled="!contents.next_page_url" class="cursor-pointer" @click="goToPage(contents.next_page_url)" />
        </PaginationContent>
    </Pagination>
</template>

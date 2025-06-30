<script setup lang="ts">
import AppFooter from '@/components/AppFooter.vue';
import CustomSelect from '@/components/CustomSelect.vue';
import SearchInput from '@/components/SearchInput.vue';
import { Card, CardContent } from '@/components/ui/card';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import PaginationContent from '@/components/ui/pagination/PaginationContent.vue';
import PaginationEllipsis from '@/components/ui/pagination/PaginationEllipsis.vue';
import PaginationItem from '@/components/ui/pagination/PaginationItem.vue';
import PaginationNext from '@/components/ui/pagination/PaginationNext.vue';
import PaginationPrevious from '@/components/ui/pagination/PaginationPrevious.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, SelectItem } from '@/types';
import { Category } from '@/types/models';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();

const props = defineProps<{
    categoriesItems: Category[];
    contents: any;
    filters: {
        orderBy: string | null;
        contentType: string | null;
        categoryId: number | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = messages.value[locale.value]['explore-breadcrumbs'] as BreadcrumbItem[];

const orderItems: SelectItem[] = messages.value[locale.value]['explore-filters'].order as SelectItem[]; // Best and worst rated, most and least popular, etc.

const contentTypesItems: SelectItem[] = messages.value[locale.value]['explore-filters'].contentTypes as SelectItem[];

const orderBy = ref<string | null>(props.filters.orderBy);
const contentType = ref<string | null>(props.filters.contentType);
const categoryId = ref<number | null>(props.filters.categoryId);
const searchContent = ref<string>('');
watch([orderBy, contentType, categoryId, searchContent], ([order, contentType, category, search]) => {
    router.visit(route('explore'), {
        method: 'get',
        data: {
            ...(order ? { orderBy: order } : {}),
            ...(contentType ? { contentType: contentType } : {}),
            ...(category ? { categoryId: category } : {}),
            ...(search ? { searchContent: search } : {}),
        },
        preserveScroll: true,
        preserveState: true,
    });
});

const hoveredItemId = ref<string | null>(null);
const setHoveredItem = (id: string) => {
    hoveredItemId.value = id;
};
const clearHoveredItem = () => {
    hoveredItemId.value = null;
};
</script>

<template>
    <Head :title="t('explore.head.title')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-10 rounded-xl">
            <section class="mt-2 flex flex-col gap-2 px-2 xl:mt-4 xl:flex-row xl:items-end xl:justify-between xl:px-4">
                <div class="flex w-full flex-col gap-2 xl:flex-row xl:items-end">
                    <SearchInput @search="(value) => (searchContent = value)" />
                    <div class="flex w-full gap-2">
                        <CustomSelect :selectItems="contentTypesItems" :placeholder="t('explore-filters.contentTypeTitle')" v-model="contentType" />
                        <CustomSelect :selectItems="categoriesItems" :placeholder="t('explore-filters.categoryTitle')" v-model="categoryId" class="w-full lg:w-48" />
                    </div>
                </div>

                <CustomSelect :selectItems="orderItems" :placeholder="t('explore-filters.orderTitle')" v-model="orderBy" />
            </section>

            <section class="grid grid-cols-2 gap-2 px-2 lg:grid-cols-4 lg:px-4 xl:grid-cols-6">
                <Link :href="route('content.detail', content.id)" v-for="content in contents.data" :key="content.id">
                    <Card
                        class="group relative aspect-[5/8] overflow-hidden rounded-lg transition duration-200 hover:-translate-y-1 hover:border-indigo-600 hover:bg-[#120e28]"
                        @mouseenter="setHoveredItem(String(content.id))"
                        @mouseleave="clearHoveredItem"
                        :class="{
                            'brightness-75': hoveredItemId && hoveredItemId !== String(content.id),
                        }"
                    >
                        <CardContent>
                            <img
                                :src="content.cover_image || '/images/welcome/hero-background.webp'"
                                :alt="`${content.title} cover image`"
                                class="absolute inset-0 z-0 size-full object-cover"
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
            </section>

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
            <AppFooter />
        </div>
    </AppLayout>
</template>

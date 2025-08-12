<script setup lang="ts">
import CustomSelect from '@/components/CustomSelect.vue';
import SearchInput from '@/components/SearchInput.vue';
import { SelectItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    categoriesItems: SelectItem[];
    orderItems: SelectItem[];
    contentTypesItems: SelectItem[];
}>();

const orderBy = defineModel<string | null>('orderBy');
const contentType = defineModel<string | null>('contentType');
const categoryId = defineModel<number | null>('categoryId');
const searchContent = defineModel<string>('searchContent');
</script>

<template>
    <section class="flex flex-col gap-2 xl:flex-row xl:items-end xl:justify-between">
        <div class="flex w-full flex-col gap-2 xl:flex-row xl:items-end">
            <SearchInput v-model="searchContent" :placeholder="t('explore.filters.search')" />
            <div class="flex w-full gap-2">
                <CustomSelect v-model="contentType" :selectItems="contentTypesItems" :placeholder="t('explore.filters.contentTypeTitle')" />
                <CustomSelect
                    v-model="categoryId"
                    :selectItems="categoriesItems"
                    :placeholder="t('explore.filters.categoryTitle')"
                    class="w-full lg:w-48"
                />
            </div>
        </div>

        <CustomSelect :selectItems="orderItems" :placeholder="t('explore.filters.orderTitle')" v-model="orderBy" />
    </section>
</template>

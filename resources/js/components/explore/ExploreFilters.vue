<script setup lang="ts">
import CustomSelect from '@/components/base/CustomSelect.vue';
import SearchInput from '@/components/base/SearchInput.vue';
import { SelectItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    genresItems: SelectItem[];
    orderItems: SelectItem[];
    contentTypesItems: SelectItem[];
}>();

const orderBy = defineModel<string | null>('orderBy');
const contentType = defineModel<string | null>('contentType');
const genreId = defineModel<number | null>('genreId');
const searchContent = defineModel<string>('searchContent');
</script>

<template>
    <section class="m-5 mb-11 grid grid-cols-3 items-center gap-2 md:grid-cols-6 lg:m-11 lg:mt-5">
        <SearchInput v-model="searchContent" :placeholder="t('explore.filters.search')" class="col-span-3 w-full md:col-span-2" />
        <CustomSelect v-model="contentType" :selectItems="contentTypesItems" :placeholder="t('explore.filters.contentTypeTitle')" class="w-full" />
        <CustomSelect v-model="genreId" :selectItems="genresItems" :placeholder="t('explore.filters.genreTitle')" class="w-full" />
        <CustomSelect :selectItems="orderItems" :placeholder="t('explore.filters.orderTitle')" v-model="orderBy" class="w-full md:col-start-6" />
    </section>
</template>

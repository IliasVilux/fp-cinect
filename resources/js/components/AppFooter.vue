<script setup lang="ts">
import { Separator } from '@/components/ui/separator';
import { SelectItem, type NavItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import CustomSelect from './CustomSelect.vue';

const navItems: NavItem[] = [
    {
        title: 'Home',
        href: 'home',
    },
    {
        title: 'Dashboard',
        href: 'dashboard',
    },
    {
        title: 'Series',
        href: 'dashboard.category',
        category: 'series',
    },
    {
        title: 'Movies',
        href: 'dashboard.category',
        category: 'movies',
    },
    {
        title: 'Animes',
        href: 'dashboard.category',
        category: 'animes',
    },
];

const { locale } = useI18n();
const langOptions: SelectItem[] = [
    { label: 'EN', value: 'en' },
    { label: 'ES', value: 'es' },
];
const langValue = ref<string | null>(locale.value);
watch(langValue, (langValue) => {
    if (!langValue) return;

    locale.value = langValue;
    router.post(
        '/localization',
        { locale: langValue },
        {
            preserveScroll: true,
            preserveState: false,
        },
    );
});
</script>

<template>
    <CustomSelect :selectItems="langOptions" placeholder="Todos los tipos" v-model="langValue" />
    <footer class="text-center">
        <Separator />
        <div class="p-10">
            <div class="flex flex-col items-start gap-2 lg:flex-row lg:items-center lg:justify-center lg:gap-x-4">
                <Link
                    v-for="item in navItems"
                    :key="item.title"
                    :href="item.category ? route(item.href, { category: item.category }) : route(item.href)"
                    class="text-sm font-semibold uppercase transition-colors duration-100 hover:text-neutral-200"
                >
                    {{ item.title }}
                </Link>
            </div>
            <p class="mt-4 text-xs text-neutral-500">© {{ new Date().getFullYear() }} Cinect. All rights reserved.</p>
        </div>
    </footer>
</template>

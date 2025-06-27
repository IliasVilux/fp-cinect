<script setup lang="ts">
import { Separator } from '@/components/ui/separator';
import { SelectItem, type NavItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import CustomSelect from './CustomSelect.vue';
import { Languages } from 'lucide-vue-next';

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
    { label: 'English', value: 'en' },
    { label: 'Español', value: 'es' },
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
    <footer class="text-center">
        <Separator />
        <div class="flex flex-col gap-6 px-2 lg:px-4 py-4 lg:py-8">
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
            <CustomSelect :selectItems="langOptions" placeholder="Todos los tipos" v-model="langValue" class="lg:mx-auto">
                <template v-slot:icon>
                    <Languages class="size-4" />
                </template>
            </CustomSelect>
            <p class="text-xs text-neutral-500">© {{ new Date().getFullYear() }} Cinect. All rights reserved.</p>
        </div>
    </footer>
</template>

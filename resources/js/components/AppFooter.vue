<script setup lang="ts">
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Separator } from '@/components/ui/separator';

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
</script>

<template>
    <footer class="text-center">
        <Separator />
        <div class="p-10">
            <div class="flex flex-col lg:flex-row items-start lg:items-center lg:justify-center gap-2 lg:gap-x-4">
                <Link v-for="item in navItems" :key="item.title" :href="item.category ? route(item.href, { category: item.category }) : route(item.href)" class="uppercase text-sm font-semibold hover:text-neutral-200 transition-colors duration-100">
                {{ item.title }}
                </Link>
            </div>
            <p class="mt-4 text-xs text-neutral-500">© {{ new Date().getFullYear() }} Cinect. All rights reserved.</p>
        </div>
    </footer>
</template>

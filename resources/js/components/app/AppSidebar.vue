<script setup lang="ts">
import NavMain from '@/components/app/NavMain.vue';
import NavUser from '@/components/app/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Compass, Fan, Film, LayoutGrid, Tv } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';
import AppLogo from './AppLogo.vue';

const { locale, messages } = useI18n();

const icons = {
    LayoutGrid,
    Tv,
    Film,
    Fan,
    Compass,
};
const rawItems = messages.value[locale.value]['app-layout'].navItems as {
    title: string;
    href: string;
    type?: string;
    iconKey: keyof typeof icons;
}[];

const mainNavItems: NavItem[] = rawItems.map(({ iconKey, ...item }) => ({
    ...item,
    icon: icons[iconKey] || iconKey,
}));
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="h-20 px-10" variant="cinect">
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

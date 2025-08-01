<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { NavigationMenu, NavigationMenuItem, NavigationMenuList } from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, NavItem } from '@/types';
import { Link, usePage, router } from '@inertiajs/vue3';
import { Compass, Fan, Film, LayoutGrid, Menu, Search, Tv } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import AppLogoIcon from './AppLogoIcon.vue';
import SearchInput from './SearchInput.vue';

const { t, locale, messages } = useI18n();

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);

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
    iconKey: keyof typeof icons;
}[];

const mainNavItems: NavItem[] = rawItems.map(({ iconKey, ...item }) => ({
    ...item,
    icon: icons[iconKey] || iconKey,
}));

const isSearching = ref(false);
const searchQuery = ref('');
function submitSearch() {
    const trimedInput = searchQuery.value.trim();

    if (trimedInput.length > 0) {
        router.visit(route('explore'), {
            method: 'get',
            data: {
                searchContent: trimedInput,
            }
        });
    }
}
</script>

<template>
    <div>
        <div class="border-sidebar-border/80 border-b">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <!-- Mobile Menu -->
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button variant="ghost" size="icon" class="mr-2 h-9 w-9">
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-[300px] p-6">
                            <SheetTitle class="sr-only">Navigation Menu</SheetTitle>
                            <SheetHeader class="flex justify-start text-left">
                                <AppLogo />
                            </SheetHeader>
                            <div class="flex h-full flex-1 flex-col justify-between space-y-4 py-6">
                                <nav class="-mx-3 space-y-1">
                                    <Link
                                        v-for="item in mainNavItems"
                                        :key="item.title"
                                        :href="item.href"
                                        class="hover:bg-accent flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium"
                                        :class="activeItemStyles(item.href)"
                                    >
                                        <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                        {{ item.title }}
                                    </Link>
                                </nav>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>

                <Link :href="route('home')" class="flex items-center gap-x-2">
                    <AppLogoIcon class="h-10 md:hidden" />
                    <AppLogo class="hidden h-10 md:flex" />
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden h-full lg:flex lg:flex-1">
                    <NavigationMenu class="ml-16 flex h-full">
                        <NavigationMenuList class="flex h-full space-x-8">
                            <NavigationMenuItem v-for="(item, index) in mainNavItems" :key="index" class="relative flex items-center">
                                <Link :href="item.href" class="transition-colors duration-75 hover:text-indigo-500">
                                    {{ item.title }}
                                </Link>
                                <div
                                    v-if="isCurrentRoute(item.href)"
                                    class="absolute bottom-0 left-0 h-0.5 w-full translate-y-px bg-black dark:bg-white"
                                ></div>
                            </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>
                </div>

                <div class="ml-auto flex items-center space-x-2">
                    <div>
                        <Button v-if="!isSearching" @click="isSearching = true" variant="ghost" size="icon" class="group h-9 w-9 cursor-pointer">
                            <Search class="size-5 opacity-80 group-hover:opacity-100" />
                        </Button>

                        <SearchInput v-else v-model="searchQuery" @search="submitSearch" @blur="isSearching = false" :placeholder="t('app-layout.searchPlaceholder')" :closeOnBlur="true" />
                    </div>

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="focus-within:ring-primary relative size-10 w-auto rounded-full p-1 focus-within:ring-2"
                            >
                                <Avatar class="size-8 overflow-hidden rounded-full">
                                    <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
                                    <AvatarFallback class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white">
                                        {{ getInitials(auth.user?.name) }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <UserMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </div>

        <div v-if="props.breadcrumbs.length > 1" class="border-sidebar-border/70 flex w-full border-b">
            <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>

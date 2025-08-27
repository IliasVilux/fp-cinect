<script setup lang="ts">
import AppLogo from '@/components/app/AppLogo.vue';
import UserMenuContent from '@/components/app/UserMenuContent.vue';
import SearchInput from '@/components/base/SearchInput.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { NavigationMenu, NavigationMenuItem, NavigationMenuList } from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTrigger } from '@/components/ui/sheet';
import { getInitials } from '@/composables/useInitials';
import type { NavItem } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Compass, Fan, Film, LayoutGrid, Menu, Search, Tv } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();

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
    type?: string;
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
        router.visit(route('contents.explore'), {
            method: 'get',
            data: {
                searchContent: trimedInput,
            },
        });
    }
}
</script>

<template>
    <header class="w-full max-w-7xl font-medium text-neutral-50">
        <div class="flex items-center px-4 py-6">
            <!-- Mobile Menu -->
            <div class="lg:hidden">
                <Sheet>
                    <SheetTrigger :as-child="true" class="cursor-pointer">
                        <Button variant="ghost" size="icon" class="mr-2 size-9">
                            <Menu class="size-6" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="top" class="w-full">
                        <SheetHeader>
                            <Link :href="route('home')" class="mt-6">
                                <AppLogo class="mx-auto w-44" />
                            </Link>
                        </SheetHeader>
                        <div class="mb-12">
                            <nav class="space-y-1">
                                <Link
                                    v-for="item in mainNavItems"
                                    :key="item.title"
                                    :href="item.type ? route(item.href, { type: item.type }) : route(item.href)"
                                    class="group mx-4 flex items-center gap-x-3 rounded-lg px-3 py-3 text-sm font-medium hover:bg-neutral-900"
                                    :class="activeItemStyles(item.href)"
                                >
                                    <component
                                        v-if="item.icon"
                                        :is="item.icon"
                                        class="size-6 transition-colors duration-300 group-hover:text-indigo-600"
                                    />
                                    {{ item.title }}
                                </Link>
                            </nav>
                        </div>
                    </SheetContent>
                </Sheet>
            </div>

            <Link :href="route('home')">
                <AppLogo class="hidden h-9 md:flex" />
            </Link>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex lg:flex-1">
                <NavigationMenu class="ml-16 flex h-full">
                    <NavigationMenuList class="flex h-full space-x-8">
                        <NavigationMenuItem v-for="(item, index) in mainNavItems" :key="index" class="relative flex items-center">
                            <Link
                                :href="item.type ? route(item.href, { type: item.type }) : route(item.href)"
                                class="font-semibold transition-colors duration-150 hover:text-indigo-600"
                            >
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

                    <SearchInput
                        v-else
                        v-model="searchQuery"
                        @search="submitSearch"
                        @blur="isSearching = false"
                        :placeholder="t('app-layout.searchPlaceholder')"
                        :closeOnBlur="true"
                    />
                </div>

                <DropdownMenu>
                    <DropdownMenuTrigger :as-child="true">
                        <Button variant="cinect" size="icon" class="cursor-pointer">
                            <Avatar class="size-8 overflow-hidden">
                                <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
                                <AvatarFallback class="font-semibold">
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
    </header>
</template>

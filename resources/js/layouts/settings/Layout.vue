<script setup lang="ts">
import HeadingLarge from '@/components/common/HeadingLarge.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale, messages } = useI18n();

const sidebarNavItems: NavItem[] = messages.value[locale.value]['settings'].layout.navItems as NavItem[];

const page = usePage();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="mt-5 mb-20 px-5 lg:mt-11 lg:px-11">
        <HeadingLarge class="mb-4 md:mb-8" :title="t('settings.layout.base.title')" />

        <div class="flex flex-col lg:flex-row lg:space-y-0 lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-48">
                <nav class="flex flex-col space-y-1 space-x-0">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="ghost"
                        :class="['w-full justify-start rounded-lg', { 'bg-muted': currentPath === item.href }]"
                        as-child
                    >
                        <Link :href="item.href" class="group/link py-5">
                            <span>{{ item.title }}</span>
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator orientation="horizontal" class="bg-border mt-2 mb-6 flex md:hidden" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>

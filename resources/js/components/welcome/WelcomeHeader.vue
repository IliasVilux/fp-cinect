<script setup lang="ts">
import AppLogo from '@/components/app/AppLogo.vue';
import AppLogoIcon from '@/components/app/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <header class="absolute top-0 left-0 z-20 w-full bg-gradient-to-b from-black to-transparent p-8 font-medium text-neutral-50">
        <nav class="flex items-center justify-between gap-4">
            <div>
                <!-- Logo reducido para mobile -->
                <AppLogoIcon class="h-10 md:hidden" />

                <!-- Logo completo para pantallas grandes -->
                <AppLogo class="hidden h-10 md:flex" />
            </div>

            <Link
                :href="route($page.props.auth.user ? 'dashboard.index' : 'login')"
                class="rounded-full bg-indigo-600 px-7 py-3 leading-none hover:bg-indigo-700 transition-colors duration-200 font-semibold"
            >
                {{ t($page.props.auth.user ? 'welcome.header.dashboard' : 'welcome.header.login') }}
            </Link>
        </nav>
    </header>
</template>

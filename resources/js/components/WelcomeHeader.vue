<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLogo from './AppLogo.vue';
import AppLogoIcon from './AppLogoIcon.vue';

const { t } = useI18n();
</script>

<template>
    <header class="w-full bg-neutral-950 p-5 text-sm font-medium text-neutral-900">
        <nav class="flex items-center justify-between gap-4">
            <div>
                <!-- Logo reducido para mobile -->
                <AppLogoIcon class="h-10 md:hidden" />

                <!-- Logo completo para pantallas grandes -->
                <AppLogo class="hidden h-10 md:flex" />
            </div>

            <Link
                :href="route($page.props.auth.user ? 'dashboard.show' : 'login')"
                class="inline-block rounded-full bg-neutral-50 px-5 py-1.5 leading-normal hover:bg-neutral-200"
            >
                {{ t($page.props.auth.user ? 'welcome.header.dashboard' : 'welcome.header.login') }}
            </Link>
        </nav>
    </header>
</template>

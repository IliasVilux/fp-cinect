<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from './AppLogoIcon.vue';
import AppLogo from './AppLogo.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <header class="w-full text-neutral-900 bg-neutral-950 p-5 text-sm font-medium">
            <nav class="flex items-center justify-between gap-4">
                <div>
                    <!-- Logo reducido para mobile -->
                    <AppLogoIcon class="h-10 md:hidden" />

                    <!-- Logo completo para pantallas grandes -->
                    <AppLogo class="h-10 hidden md:flex" />
                </div>
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-full bg-neutral-50 hover:bg-neutral-200 px-5 py-1.5 leading-normal"
                >
                    {{ t('welcome.header.dashboard') }}
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-full bg-neutral-50 hover:bg-neutral-200 px-5 py-1.5 leading-normal"
                    >
                        {{ t('welcome.header.login') }}
                    </Link>
                </template>
            </nav>
        </header>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <header class="w-full text-neutral-900 bg-neutral-950 p-5 text-sm font-medium">
            <nav class="flex items-center justify-between gap-4">
                <div>
                    <!-- Logo reducido para mobile -->
                    <img
                        src="/images/logo-mini.webp"
                        alt="Cinect logo Mini"
                        class="h-12 block lg:hidden"
                    />

                    <!-- Logo completo para pantallas grandes -->
                    <img
                        src="/images/logo-full.webp"
                        alt="Cinect logo full"
                        class="h-12 hidden lg:block"
                    />
                </div>
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-full bg-neutral-50 hover:bg-neutral-200 px-5 py-1.5 leading-normal"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-full bg-neutral-50 hover:bg-neutral-200 px-5 py-1.5 leading-normal"
                    >
                        Log in
                    </Link>
                </template>
            </nav>
        </header>
</template>

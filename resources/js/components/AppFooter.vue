<script setup lang="ts">
import { Separator } from '@/components/ui/separator';
import languages from '@/lang/languages.json';
import { SelectItem, type NavItem } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Languages } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import CustomSelect from './CustomSelect.vue';

const { t, locale, messages } = useI18n();
const page = usePage();
const backendLocale = page.props.locale as string;

locale.value = backendLocale;
const navItems: NavItem[] = messages.value[locale.value]['footer'].navItems as NavItem[];

const langOptions: SelectItem[] = languages;
const langValue = ref<string | null>(backendLocale);
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
        <div class="flex flex-col gap-6 px-2 py-4 lg:px-4 lg:py-8">
            <div class="flex flex-col items-start gap-2 lg:flex-row lg:items-center lg:justify-center lg:gap-x-4">
                <Link
                    v-for="item in navItems"
                    :key="item.title"
                    :href="item.type ? route(item.href, { type: item.type }) : route(item.href)"
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
            <p class="text-xs text-neutral-500">© {{ new Date().getFullYear() }} {{ t('footer.copyright') }}</p>
        </div>
    </footer>
</template>

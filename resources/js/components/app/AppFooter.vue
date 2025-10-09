<script setup lang="ts">
import CustomSelect from '@/components/base/CustomSelect.vue';
import languages from '@/lang/languages.json';
import { SelectItem, type NavItem } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Languages } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

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
    <footer class="border-border rounded-t-3xl border-t bg-neutral-900 px-20 py-10 text-center lg:px-0 lg:py-8">
        <div class="flex items-center justify-between gap-6 text-neutral-300 lg:flex-col">
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

            <CustomSelect :selectItems="langOptions" :allowDeselect="false" placeholder="Todos los tipos" v-model="langValue" class="lg:mx-auto">
                <template v-slot:icon>
                    <Languages class="size-4" />
                </template>
            </CustomSelect>
        </div>
        <p class="mt-6 text-xs text-neutral-500">© {{ new Date().getFullYear() }} {{ t('footer.copyright') }}</p>
    </footer>
</template>

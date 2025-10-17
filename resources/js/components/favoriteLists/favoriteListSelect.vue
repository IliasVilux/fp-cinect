<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import type { FavoriteList } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import { BookMarked } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    lists: (FavoriteList & { has_content: boolean })[];
    contentId: number;
}>();
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="outline">
                <BookMarked class="size-full" />
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>{{ t('favoriteLists.addToListDialog.dialog.title') }}</DialogTitle>
                <DialogDescription>{{ t('favoriteLists.addToListDialog.dialog.description') }}</DialogDescription>
            </DialogHeader>

            <section>
                <div v-for="(list) in lists" :key="list.id">
                    <div
                        class="border-border my-2 flex flex-row items-center justify-between rounded-md border bg-gradient-to-l from-neutral-950/80 to-neutral-950/0 px-4 py-2 transition-colors duration-300 hover:border-indigo-600 hover:bg-neutral-800"
                    >
                        <p class="truncate">{{ list.name }}</p>
                        <Link :href="route('favoriteLists.toggleContent', { list: list.id, content: contentId })" method="post">
                            <Button :variant="list.has_content ? 'destructive' : 'secondary'">
                                {{ list.has_content ? t('favoriteLists.addToListDialog.btn.remove') : t('favoriteLists.addToListDialog.btn.add') }}
                            </Button>
                        </Link>
                    </div>
                </div>
            </section>

            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" variant="secondary">{{ t('favoriteLists.addToListDialog.dialog.close') }}</Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

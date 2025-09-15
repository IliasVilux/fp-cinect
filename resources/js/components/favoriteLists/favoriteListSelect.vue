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
                <DialogTitle>add</DialogTitle>
                <DialogDescription> desc </DialogDescription>
            </DialogHeader>

            <section>
                <div v-for="(list, i) in lists" :key="list.id">
                    <div class="flex border-border rounded-md border bg-gradient-to-l from-neutral-950/80 to-neutral-950/0 p-4 my-2 transition-colors duration-300 hover:border-indigo-600 hover:bg-neutral-800 flex-row items-center justify-between">
                        <p class="truncate">{{ list.name }}</p>
                        <Link :href="route('favoriteLists.toggleContent', { list: list.id, content: contentId })" method="post">
                            <Button :variant="list.has_content ? 'destructive' : 'secondary'">
                                {{ list.has_content ? 'Remove' : 'Add' }}
                            </Button>
                        </Link>
                    </div>
                </div>
            </section>

            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" variant="secondary"> Close </Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

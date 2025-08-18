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
import { Separator } from '@/components/ui/separator';
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
                    <div class="flex items-center justify-between">
                        <p>{{ list.name }}</p>
                        <Link :href="route('favoriteLists.toggleContent', { list: list.id, content: contentId })" method="post">
                            <Button variant="secondary">
                                {{ list.has_content ? 'Remove' : 'Add' }}
                            </Button>
                        </Link>
                    </div>

                    <Separator v-if="i < lists.length - 1" orientation="horizontal" />
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

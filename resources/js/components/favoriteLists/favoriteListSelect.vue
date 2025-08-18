<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger, DialogClose } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { BookMarked } from 'lucide-vue-next';
import type { FavoriteList } from '@/types/models';
import { Separator } from '@/components/ui/separator';
import { Link } from '@inertiajs/vue3';

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
                <DialogDescription>
                    desc
                </DialogDescription>
            </DialogHeader>

            <section>
                <div v-for="(list, i) in lists" :key="list.id">
                    <div class="flex items-center justify-between">
                        <p>{{ list.name }}</p>
                        <Link :href="route('favoriteLists.toggleContent', { list: list.id, content: contentId })" method="post">
                            <Button
                                variant="secondary"
                            >
                                {{ list.has_content ? 'Remove' : 'Add' }}
                            </Button>
                        </Link>
                    </div>

                    <Separator v-if="i < lists.length - 1" orientation="horizontal" />
                </div>
            </section>

            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" variant="secondary">
                        Close
                    </Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

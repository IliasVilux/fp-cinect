<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { FavoriteList } from '@/types/models';
import { Link, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    list?: FavoriteList;
}>();

const form = useForm({
    name: props.list?.name || '',
    description: props.list?.description || '',
    is_public: props.list?.is_public || false,
});
const dialogOpen = ref(false);
const handleDialogOpenChange = (open: boolean) => {
    dialogOpen.value = open;
    if (!open) {
        form.reset();
    }
};

const changeListVisibility = (value: boolean) => {
    form.is_public = value;
};

const submit = () => {
    if (props.list) {
        form.put(route('favoriteLists.update', props.list.id), {
            onFinish: () => {
                dialogOpen.value = false;
            },
            preserveScroll: true,
            preserveState: false,
        });
    } else {
        form.post(route('favoriteLists.store'), {
            onFinish: () => {
                dialogOpen.value = false;
            },
            preserveScroll: true,
            preserveState: false,
        });
    }
};
const deleteList = () => {
    if (props.list) {
        form.delete(route('favoriteLists.destroy', props.list.id), {
            onFinish: () => {
                dialogOpen.value = false;
            },
            preserveScroll: true,
            preserveState: false,
        });
    }
};
</script>

<template>
    <div class="flex">
        <Dialog :open="dialogOpen" @update:open="handleDialogOpenChange">
            <DialogTrigger as-child>
                <Button variant="outline">
                    <h3 class="text-lg">
                        {{ list ? t('favoriteLists.btn.editList') : t('favoriteLists.btn.addList') }}
                    </h3>
                </Button>
            </DialogTrigger>

            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>{{ list ? t('favoriteLists.dialog.title.editList') : t('favoriteLists.dialog.title.addList') }}</DialogTitle>
                    <DialogDescription>
                        {{ list ? t('favoriteLists.dialog.description.editList') : t('favoriteLists.dialog.description.addList') }}
                    </DialogDescription>
                </DialogHeader>

                <form id="form" @submit.prevent="submit">
                    <div class="grid gap-2">
                        <Label for="title">{{ t('favoriteLists.dialog.form.name.label') }}</Label>
                        <Input id="title" type="text" required v-model="form.name" :placeholder="t('favoriteLists.dialog.form.name.placeholder')" />
                    </div>

                    <div class="mt-6 grid gap-2">
                        <Label for="description">{{ t('favoriteLists.dialog.form.description.label') }}</Label>
                        <Textarea id="description" v-model="form.description" :placeholder="t('favoriteLists.dialog.form.description.placeholder')" />
                    </div>

                    <div v-if="list && list.contents && list.contents.length > 0" class="mt-6 grid gap-2">
                        <Label>Contents</Label>
                        <div>
                            <div
                                v-for="content in list.contents"
                                :key="content.id"
                                class="border-border my-2 flex flex-row items-center justify-between gap-x-2 rounded-md border bg-gradient-to-l from-neutral-950/80 to-neutral-950/0 p-2 transition-colors duration-300 hover:bg-neutral-800"
                            >
                                <p class="max-w-56 truncate text-sm lg:max-w-72">{{ content.title }}</p>
                                <Link
                                    :href="route('favoriteLists.toggleContent', { list: list.id, content: content.id })"
                                    method="post"
                                    class="text-destructive cursor-pointer px-2 text-xs"
                                >
                                    {{ t('favoriteLists.dialog.form.contents.remove') }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <DialogFooter class="mt-6 sm:justify-between">
                        <div class="flex items-center space-x-2">
                            <Switch :model-value="form.is_public" @update:model-value="changeListVisibility" />
                            <Label for="is_public" class="cursor-pointer select-none"
                                ><Label for="is_public">{{ t('favoriteLists.dialog.form.isPublic.label') }}</Label></Label
                            >
                        </div>

                        <div class="flex justify-end gap-2">
                            <Button v-if="list" variant="destructive" :disabled="form.processing" @click="deleteList">
                                <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                                {{ t('favoriteLists.btn.deleteList') }}
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                                {{ t('favoriteLists.btn.submit') }}
                            </Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>

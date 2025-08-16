<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { FavoriteList } from '@/types/models';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { LoaderCircle } from 'lucide-vue-next';
import { Textarea } from '@/components/ui/textarea';

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
                    {{ form }}
                    <DialogTitle>{{ list ? t('favoriteLists.dialog.title.editList') : t('favoriteLists.dialog.title.addList') }}</DialogTitle>
                    <DialogDescription>
                        {{ list ? t('favoriteLists.dialog.description.editList') : t('favoriteLists.dialog.description.addList') }}
                    </DialogDescription>
                </DialogHeader>

                <form id="form" @submit.prevent="submit">
                    <div>
                        <Label for="title">title</Label>
                        <Input
                            id="title"
                            type="text"
                            required
                            v-model="form.name"
                            placeholder="List Name" />
                    </div>

                    <div>
                        <Label for="description">description</Label>
                        <Textarea
                            id="description"
                            v-model="form.description"
                            placeholder="asd" />
                    </div>

                    <Switch :model-value="form.is_public" @update:model-value="changeListVisibility" />

                    <DialogFooter>
                        <Button type="submit" class="sm:justify-start" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                            submit
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
